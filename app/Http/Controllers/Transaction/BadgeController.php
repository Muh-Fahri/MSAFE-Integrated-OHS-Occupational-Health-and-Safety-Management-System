<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\BadgeRequest;
use App\Models\BadgeRequestFlow;
use App\Models\BadgeRequestLine;
use App\Models\BadgeRequestLog;
use App\Models\BadgeRequestNextApprover;
use App\Models\Badge;
use App\Models\Delegation;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Exports\BadgeRequestExport;
use Maatwebsite\Excel\Facades\Excel;

class BadgeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = BadgeRequest::query();

        // logic shorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $action = !empty($request->action) ? strtoupper($request->action) : '';
        if ($action == 'MONITORING') {
            //
        } else if ($action == 'APPROVAL') {
            $user_ids = [$user->id];
            $delegation = Delegation::where('type', 'ALL')
                ->where('delegatee', $user->id)
                ->where('begin_date', '<=', date('Y-m-d'))
                ->where('end_date', '>=', date('Y-m-d'))
                ->first();
            if ($delegation != null) {
                $user_ids[] = $delegation->delegator;
            }
            $res = BadgeRequestNextApprover::whereIn('user_id', $user_ids)->get(['request_id']);
            $ids = [];
            foreach ($res as $v) {
                $ids[] = $v->request_id;
            }
            $query->whereIn('status', ['APPROVAL_REQUIRED'])->whereIn('id', $ids);
        } else if ($action == 'PRINTING') {
            $user_ids = [$user->id];
            $delegation = Delegation::where('type', 'ALL')
                ->where('delegatee', $user->id)
                ->where('begin_date', '<=', date('Y-m-d'))
                ->where('end_date', '>=', date('Y-m-d'))
                ->first();
            if ($delegation != null) {
                $user_ids[] = $delegation->delegator;
            }
            $res = BadgeRequestNextApprover::whereIn('user_id', $user_ids)->get(['request_id']);
            $ids = [];
            foreach ($res as $v) {
                $ids[] = $v->request_id;
            }
            $query->whereIn('status', ['WAITING_TO_PRINT'])->whereIn('id', $ids);
        } else if ($action == 'APPROVAL_HISTORY') {
            $query->whereRaw("(id IN (SELECT DISTINCT request_id FROM badge_request_logs WHERE (user_id = " . $user->id . " OR delegator_uid = " . $user->id . ") AND event NOT IN ('CREATE', 'UPDATE')))");
        } else {
            $query->where('requestor_id', $user->id);
        }
        if ($request->filled('request_date')) {
            $request_date   = explode(' to ', $request->request_date);
            $request_date_1 = date('Y-m-d', strtotime($request_date[0]));
            $request_date_2 = date('Y-m-d', strtotime($request_date[1]));
            $query->whereBetween('request_date', [$request_date_1, $request_date_2]);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('requestor_name')) {
            $query->where('requestor_name', 'LIKE', '%' . $request->requestor_name . '%');
        }
        if ($request->filled('request_no')) {
            $query->where('request_no', 'LIKE', '%' . $request->request_no . '%');
        }
        if ($request->filled('company_name')) {
            $query->where('company_name', 'LIKE', '%' . $request->company_name . '%');
        }
        $badgesReq = $query->orderBy($sortBy, $sortOrder)->paginate(10)->withQueryString();
        $is_able_to_monitor = in_array($user->role_id, [1, 4]);
        return view('transaction.badge.index', compact('badgesReq', 'action', 'is_able_to_monitor'));
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        return Excel::download(new BadgeRequestExport($request, $user), 'badge_request.xlsx');
    }

    public function create()
    {
        $com = Company::all();
        $lastReport = Badge::orderBy('id', 'desc')->first();
        $nextNo = $lastReport ? $lastReport->id + 1 : 1;
        $loc = [
            'Head Office Jakarta',
            'Site Awak Mas',
        ];

        $stat = [
            'Permanent',
            'Contract',
            'Visitor'
        ];
        return view('transaction.badge.create', compact('com', 'loc', 'stat', 'nextNo'));
    }

    public function store(Request $request)
    {
        $requestor = Auth::user();
        $company = Company::find($request->company_id);
        $subCompany = Company::find($request->sub_company_id);

        $nextUser = $this->get_next_approver($requestor->id, 0);
        $badgeRequest = BadgeRequest::create([
            'requestor_id'      => $requestor->id,
            'requestor_name'    => $requestor->name,
            'company_id'        => $request->company_id,
            'company_name'      => $company->name ?? null,
            'request_date'      => $request->request_date,
            'location'          => $request->location,
            'sub_company_id'    => $request->sub_company_id,
            'sub_company_name'  => $subCompany->name ?? null,
            'status'            => 'APPROVAL_REQUIRED',
            'request_no'        => $request->request_no,
            'approval_level'    => 0,
            'last_action'       => 'CREATE',
            'last_user_id'      => $requestor->id,
            'last_user_name'    => $requestor->name,
            'next_action'       => 'APPROVAL',
            'next_user_id'      => $nextUser->id ?? $requestor->id,
            'next_user_name'    => $nextUser->name ?? $requestor->name,
        ]);

        BadgeRequestLog::create([
            'request_id' => $badgeRequest->id,
            'user_id'    => $requestor->id,
            'user_name'  => $requestor->name,
            'status'     => 'DRAFT',
            'event'      => 'CREATE',
        ]);

        if ($request->has('employee_id')) {
            foreach ($request->employee_id as $key => $empId) {
                $period = isset($request->active_period[$key]) ? intval($request->active_period[$key]) : 0;
                $startDate = now();
                $endDate = now()->addMonths($period);
                $lineData = [
                    'request_id'    => $badgeRequest->id,
                    'employee_id'   => $empId,
                    'citizen_id'    => $request->citizen_id[$key] ?? null,
                    'name'          => $request->name[$key] ?? null,
                    'title'         => $request->title[$key] ?? null,
                    'status'        => $request->status[$key] ?? null,
                    'active_period' => $period,
                ];
                $fileFields = ['file_path_photo', 'file_path_ftw', 'file_path_ktp', 'file_path_induksi', 'file_path_mcu', 'file_path_covid', 'file_path_domicile', 'file_path_skck'];
                $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'badge_request';
                foreach ($fileFields as $field) {
                    if ($request->hasFile($field) && isset($request->file($field)[$key])) {
                        $file = $request->file($field)[$key];
                        $fileName = 'BR_' . $badgeRequest->id . '_' . $key . '_' . $field . '.' . $file->getClientOriginalExtension();
                        if (!File::isDirectory($basePath))
                            File::makeDirectory($basePath, 0777, true, true);
                        $file->move($basePath, $fileName);
                        $lineData[$field] = $fileName;
                        $typeField = str_replace('path', 'type', $field);
                        $lineData[$typeField] = $file->getClientOriginalExtension();
                    }
                }
                BadgeRequestLine::create($lineData);
            }
        }

        BadgeRequestNextApprover::where('request_id', $badgeRequest->id)->delete();
        $next_user_ids = explode(',', $nextUser->value);
        foreach ($next_user_ids as $next_user_id) {
            $nextuser              = User::find($next_user_id);
            $brna               = new BadgeRequestNextApprover;
            $brna->request_id   = $badgeRequest->id;
            $brna->user_id      = $nextuser->id;
            $brna->user_name    = $nextuser->name;
            $brna->save();

            // send email to next user
            Mail::send('mail.badge_approval', ['data' => $badgeRequest, 'nextuser' => $nextuser], function ($message) use ($badgeRequest, $nextuser) {
                $message->to($nextuser->email);
                $message->subject('Badge Approval for Request No : ' . $badgeRequest->request_no);
            });
        }

        return redirect()->route('transaction-badge.index')->with('success', 'Badge Request submitted successfully.');
    }

    public function show(string $id)
    {
        $user = Auth::user();
        $data = BadgeRequest::with('lines')->findOrFail($id);
        $next_user_ids = BadgeRequestNextApprover::where('request_id', $id)
            ->pluck('user_id')->toArray();

        $delegated = Delegation::where('type', 'ALL')
            ->whereIn('delegator', $next_user_ids)
            ->where('delegatee', $user->id)
            ->whereDate('begin_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->exists();
        return view('transaction.badge.show', compact(
            'data',
            'user',
            'next_user_ids',
            'delegated'
        ));
    }

    public function edit(string $id)
    {
        $badgeReq = BadgeRequest::with('lines')->findOrFail($id);
        $com = Company::all();
        $loc = ['Head Office Jakarta', 'Site Awak Mas'];
        $stat = [
            'Permanent',
            'Contract',
            'Visitor'
        ];
        return view('transaction.badge.edit', compact('badgeReq', 'com', 'loc', 'stat'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'company_id'   => 'required',
            'request_date' => 'required|date',
            'location'     => 'required',
            'name'         => 'required|array',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $badgeReq = BadgeRequest::findOrFail($id);
            $company = Company::find($request->company_id);

            $badgeReq->update([
                'request_date'     => $request->request_date,
                'company_id'       => $request->company_id,
                'company_name'     => $company->name ?? null,
                'sub_company_id'   => $request->sub_company_id,
                'location'         => $request->location,
                'last_user_name'   => Auth::user()->name,
                'last_action'      => 'Updated',
            ]);

            $oldLines = $badgeReq->lines;

            $badgeReq->lines()->delete();

            $linesData = [];
            $fields = ['file_path_photo', 'file_path_ktp', 'file_path_ftw', 'file_path_induksi'];

            foreach ($request->name as $i => $val) {
                $existingLine = $oldLines->get($i);

                $currentLine = [
                    'employee_id'   => $request->employee_id[$i] ?? null,
                    'citizen_id'    => $request->citizen_id[$i] ?? null,
                    'name'          => $val,
                    'title'         => $request->title[$i] ?? null,
                    'status'        => $request->status[$i] ?? 'active',
                    'active_period' => $request->active_period[$i] ?? 0,
                ];

                $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'badge_request';
                foreach ($fields as $field) {
                    $typeField = str_replace('path', 'type', $field);
                    if ($request->hasFile("$field.$i")) {
                        $file = $request->file("$field.$i");
                        $fileName = 'BR_' . $badgeReq->id . '_' . $i . '_' . $field . '.' . $file->getClientOriginalExtension();
                        if (!File::isDirectory($basePath))
                            File::makeDirectory($basePath, 0777, true, true);
                        $file->move($basePath, $fileName);
                        $currentLine[$field] = $fileName;
                        $currentLine[$typeField] = $file->getClientMimeType();
                    } else {
                        $currentLine[$field] = $existingLine->$field ?? null;
                        $currentLine[$typeField] = $existingLine->$typeField ?? null;
                    }
                }
                $linesData[] = $currentLine;
            }
            $badgeReq->lines()->createMany($linesData);

            return redirect()
                ->route('transaction-badge.index')
                ->with('success', 'Data berhasil diperbarui tanpa hilang.');
        });
    }

    public function destroy(string $id)
    {
        $badgeReq = BadgeRequest::with('lines')->findOrFail($id);
        $badgeReq->lines()->delete();
        $badgeReq->delete();
        return redirect()
            ->route('transaction-badge.index')
            ->with('success', 'Badge Request and its personnel data deleted successfully.');
    }

    function get_next_approver($requestor_id, $level)
    {
        $next_user = null;
        $flow = BadgeRequestFlow::where('level', '>', $level)->orderBy('level', 'ASC')->first();
        if ($flow != null) {
            if ($flow->type == 'HOD') {
                $requestor             = User::find($requestor_id);
                $next_user             = User::find($requestor->hod2);
                $next_user->value   = $requestor->hod2;
            } else {
                $next_user             = new User;
                $next_user->id         = null;
                $next_user->name     = $flow->type;
                $next_user->value   = $flow->value;
            }
            $next_user->level   = $flow->level;
            $next_user->type    = $flow->type;
            $next_user->action  = $flow->action;
        }
        return $next_user;
    }

    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $br = BadgeRequest::find($id);
            $br->last_action        = $request->action;
            $br->last_user_id       = $user->id;
            $br->last_user_name     = $user->name;
            $delegator_uid          = $br->next_user_id;
            $next_user              = null;
            $next_status            = 'REJECTED';
            $next_approval_level    = 0;
            if ($request->action == 'PRINT') {
                $next_status     = 'COMPLETED';
                $br->taken_by     = $request->taken_by;
                $br->remarks     = $request->remarks;
            } else if ($request->action == 'APPROVE') {
                $next_user = $this->get_next_approver($br->requestor_id, $br->approval_level);
                if ($next_user == null) {
                    $next_status = 'APPROVED';
                } else {
                    if ($next_user->action == 'PRINTING') {
                        $next_status = 'WAITING_TO_PRINT';
                    } else {
                        $next_status = 'APPROVAL_REQUIRED';
                    }
                    $next_approval_level = $next_user->level;
                }
            }
            $br->status             = $next_status;
            $br->approval_level     = $next_approval_level;
            $br->next_action        = $next_user == null ? null : $next_user->action;
            $br->next_user_id       = $next_user == null ? null : $next_user->id;
            $br->next_user_name     = $next_user == null ? null : $next_user->name;
            $br->save();

            $log                = new BadgeRequestLog;
            $log->request_id    = $br->id;
            $log->user_id       = $user->id;
            $log->user_name     = $user->name;
            $log->status        = $br->status;
            $log->remarks       = $request->remarks;
            $log->event         = $br->last_action;
            $log->delegator_uid = $delegator_uid;
            $log->save();

            BadgeRequestNextApprover::where('request_id', $br->id)->delete();
            if (!empty($br->next_action)) {
                $next_user_ids = explode(',', $next_user->value);
                foreach ($next_user_ids as $next_user_id) {
                    $nextuser          = User::find($next_user_id);
                    $frna               = new BadgeRequestNextApprover;
                    $frna->request_id   = $br->id;
                    $frna->user_id      = $nextuser->id;
                    $frna->user_name    = $nextuser->name;
                    $frna->save();

                    // SEND EMAIL NOTIFICATION
                    Mail::send('mail.badge_approval', ['data' => $br, 'nextuser' => $nextuser], function ($message) use ($br, $nextuser) {
                        $message->to($nextuser->email);
                        $message->subject('Badge Approval for Request No : ' . $br->request_no);
                    });
                }
            }

            if ($br->status == 'COMPLETED') {
                $res = BadgeRequestLine::where('request_id', $br->id)->get();
                foreach ($res as $brl) {
                    $b = Badge::where('company_id', $br->company_id)->where('employee_id', $brl->employee_id)->first();
                    if ($b == null) {
                        $b                  = new Badge;
                        $b->company_id        = $br->company_id;
                        $b->sub_company_id  = $br->sub_company_id;
                        $b->employee_id       = $brl->employee_id;
                    }
                    $b->citizen_id          = $brl->citizen_id;
                    $b->name                = $brl->name;
                    $b->title               = $brl->title;
                    $b->status              = $brl->status;
                    $b->active_period       = $brl->active_period;
                    $b->active_from         = date('Y-m-d');
                    $b->active_to           = date('Y-m-d', strtotime("+" . $b->active_period . " months", strtotime($b->active_from)));
                    $b->contract_period     = $brl->contract_period;
                    $b->time_unit           = $brl->time_unit;
                    $b->file_type_photo     = $brl->file_type_photo;
                    $b->file_path_photo     = $brl->file_path_photo;
                    $b->file_type_ftw       = $brl->file_type_ftw;
                    $b->file_path_ftw       = $brl->file_path_ftw;
                    $b->file_type_mcu       = $brl->file_type_mcu;
                    $b->file_path_mcu       = $brl->file_path_mcu;
                    $b->file_type_covid     = $brl->file_type_covid;
                    $b->file_path_covid     = $brl->file_path_covid;
                    $b->file_type_ktp       = $brl->file_type_ktp;
                    $b->file_path_ktp       = $brl->file_path_ktp;
                    $b->file_type_domicile  = $brl->file_type_domicile;
                    $b->file_path_domicile  = $brl->file_path_domicile;
                    $b->file_type_skck      = $brl->file_type_skck;
                    $b->file_path_skck      = $brl->file_path_skck;
                    $b->file_type_induksi   = $brl->file_type_induksi;
                    $b->file_path_induksi   = $brl->file_path_induksi;
                    $b->save();
                }
            }
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
        return redirect()->route('transaction-badge.index', ['action' => 'APPROVAL'])->with('success', 'Updated successfully');
    }
}
