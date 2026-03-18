<?php

namespace App\Http\Controllers\Transaction;

use App\Models\Flow;
use App\Models\User;
use App\Models\Company;
use App\Models\Delegation;
use App\Models\Department;
use App\Models\License;
use App\Models\LicenseItem;
use App\Models\LicenseLog;
use App\Models\LicenseZone;
use App\Models\OhsMaster;
use Illuminate\Http\Request;
use App\Models\LicenseNextApprover;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\LicenseAttachment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LicenseExport;

class LicenseController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = License::selectRaw("licenses.*, (SELECT GROUP_CONCAT(user_name) FROM license_next_approvers na WHERE na.license_id = licenses.id) as next_user");

        // logic shorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $sort = $request->get('sort', '');
        $action = !empty($request->action) ? strtoupper($request->action) : '';
        if ($action == 'MONITORING') {
            //
        } else if ($action == 'DEPARTMENT_MONITORING') {
            $res = User::where('department_id', $user->department_id)->get(['id']);
            $list_user_id = [];
            foreach ($res as $v) {
                $list_user_id[] = $v->id;
            }
            $query->whereIn('requestor_id', $list_user_id);
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
            $res = LicenseNextApprover::whereIn('user_id', $user_ids)->get(['license_id']);
            $ids = [];
            foreach ($res as $v) {
                $ids[] = $v->license_id;
            }
            $query->whereIn('status', ['APPROVAL_REQUIRED'])->whereIn('id', $ids);
        } else if ($action == 'APPROVAL_HISTORY') {
            $query->whereRaw("(id IN (SELECT DISTINCT license_id FROM license_logs WHERE (user_id = " . $user->id . " OR delegator_uid = " . $user->id . ") AND event NOT IN ('CREATE', 'UPDATE')))");
        } else {
            $query->where('requestor_id', $user->id);
        }
        if ($request->filled('request_date')) {
            $request_date   = explode(' to ', $request->request_date);
            $request_date_1 = date('Y-m-d', strtotime($request_date[0]));
            $request_date_2 = date('Y-m-d', strtotime($request_date[1]));
            $query->whereBetween('date', [$request_date_1, $request_date_2]);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', 'LIKE', '%' . $request->employee_id . '%');
        }
        if ($request->filled('company_name')) {
            $query->where('company_name', 'LIKE', '%' . $request->company_name . '%');
        }
        if ($request->filled('no')) {
            $query->where('no', 'LIKE', '%' . $request->no . '%');
        }
        if ($request->filled('requestor_name')) {
            $query->where('requestor_name', 'LIKE', '%' . $request->requestor_name . '%');
        }
        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }
        $licen = $query->orderBy($sortBy, $sortOrder)->paginate(10)->withQueryString();
        return view('transaction.license_operation.index', compact('licen'));
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        return Excel::download(new LicenseExport($request, $user), 'license.xlsx');
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $list_user2 = User::where('status', 'active')
            ->pluck('name', 'id')
            ->toArray();
        $list_department = Department::pluck('name', 'id')->toArray();
        $list_company = Company::pluck('name', 'id')->toArray();
        $type = $request->type ?? 'KIMPER';
        $list_type = [
            'KIMPER' => 'KIMPER',
            'KIMPAK' => 'KIMPAK'
        ];
        $list_zone = [];
        $list_item = [];
        $list_item_group = [];
        if ($type === 'KIMPER') {
            $list_zone = OhsMaster::where('type', 'LICENSE_ZONE')->get();
            $list_item = OhsMaster::where('type', 'LICENSE_KIMPER')->get();
            foreach ($list_item as $v) {
                $key = $v->value2 . '_' . $v->value3;
                $list_item_group[$key][] = $v;
            }
        } else {
            $list_item = OHSMaster::where('type', 'LICENSE_KIMPAK')->get();
        }
        return view('transaction.license_operation.create', compact(
            'user',
            'list_user2',
            'list_department',
            'list_company',
            'list_type',
            'list_zone',
            'list_item',
            'list_item_group',
            'type',
        ));
    }

    function get_next_approver($license, $level)
    {
        $next_user = null;
        $flow = Flow::where('process', 'LTO_' . $license->type)->where('level', '>', $level)->orderBy('level', 'ASC')->first();
        if ($flow != null) {
            if ($flow->type == 'HEAD_OF_DEPARTMENT') {
                $requestor          = User::find($license->requestor_id);
                $next_user          = User::find($requestor->hod);
                $next_user->value   = $requestor->hod;
            } else if ($flow->type == 'THEORY_TESTER') {
                $next_user          = User::find($license->theory_tester_id);
                $next_user->value   = $next_user->id;
            } else if ($flow->type == 'PRACTICE_TESTER') {
                $next_user          = User::find($license->practice_tester_id);
                $next_user->value   = $next_user->id;
            } else if ($flow->type == 'FIRST_AID_TRAINER') {
                $next_user          = User::find($license->first_aid_trainer_id);
                $next_user->value   = $next_user->id;
            } else if ($flow->type == 'DDC_TRAINER') {
                $next_user          = User::find($license->ddc_trainer_id);
                $next_user->value   = $next_user->id;
            } else {
                $next_user          = new User;
                $next_user->id      = null;
                $next_user->name    = $flow->type;
                $next_user->value   = $flow->value;
            }
            $next_user->level   = $flow->level;
            $next_user->type    = $flow->type;
            $next_user->action  = $flow->action;
        }
        return $next_user;
    }


    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'type'                        => 'required|in:KIMPER,KIMPAK',
            'employee_id'                 => 'required',
            'name'                        => 'required|string|max:100',
            'position'                    => 'required|string|max:100',
            'department_id'               => 'required|exists:departments,id',
            'company_id'                  => 'required|exists:companies,id',
            'reason'                      => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $license                            = new License;
            $license->type                      = $request->type;
            $license->no                        = $this->getNextNo();
            $license->date                      = date('Y-m-d');
            $license->employee_id               = $request->employee_id;
            $license->name                      = $request->name;
            $license->position                  = $request->position;
            $license->department_id             = $request->department_id;
            $department                         = Department::findOrFail($request->department_id);
            $license->department_name           = $department->name;
            $license->company_id                = $request->company_id;
            $company                            = Company::findOrFail($request->company_id);
            $license->company_name              = $company->name;

            $license->requestor_id              = $user->id;
            $license->requestor_name            = $user->name;
            $license->requestor_department_id   = $user->department_id;
            $department                         = Department::find($user->department_id);
            $license->requestor_department_name = $department->name;
            $license->reason                    = $request->reason;
            if ($license->type == "KIMPER") {
                $license->driving_license_expiry_date = $request->driving_license_expiry_date;
            }
            $license->save();

            $license->last_action       = 'CREATE';
            $license->last_user_id      = $user->id;
            $license->last_user_name    = $user->name;
            $license->next_action       = 'APPROVAL';
            $license->last_approval_level   = 0;
            $next_user                  = $this->get_next_approver($license, 0);
            $license->next_user_id      = $next_user->id;
            $license->next_user_name    = $next_user->name;
            $license->status            = 'APPROVAL_REQUIRED';
            $license->approval_level    = 1;
            $license->save();

            $log                = new LicenseLog;
            $log->license_id    = $license->id;
            $log->user_id       = $user->id;
            $log->user_name     = $user->name;
            $log->status        = $license->status;
            $log->remarks       = $request->remarks;
            $log->event         = $license->last_action;
            $log->delegator_uid = null;
            $log->save();

            $item_ids = $request->item_id != null ? $request->item_id : [];
            foreach ($item_ids as $v) {
                $licenseitem                = new LicenseItem;
                $licenseitem->license_id    = $license->id;
                $ohsmaster                  = OHSMaster::find($v);
                $licenseitem->code          = $ohsmaster->code;
                $line_item_name             = $ohsmaster->name;
                if ($ohsmaster->type == "LICENSE_KIMPAK" && $ohsmaster->code == "99") {
                    $item_name_99 = !empty($request->item_name_99) ? $request->item_name_99 : '';
                    $line_item_name = $ohsmaster->name . ' : ' . $item_name_99;
                }
                $licenseitem->name          = $line_item_name;
                $licenseitem->save();
            }
            if ($license->type == 'KIMPER') {
                $zone_ids = $request->zone_id != null ? $request->zone_id : [];
                foreach ($zone_ids as $v) {
                    $licensezone                = new LicenseZone;
                    $licensezone->license_id    = $license->id;
                    $ohsmaster                  = OHSMaster::find($v);
                    $licensezone->code          = $ohsmaster->code;
                    $licensezone->color_code    = $ohsmaster->value3;
                    $licensezone->remarks       = $ohsmaster->name;
                    $licensezone->save();
                }
            }

            $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'license';
            if ($request->hasFile('attachment_files')) {
                foreach ($request->file('attachment_files') as $key => $file) {
                    $fileName = 'OHS_LTO_' . $license->id . '_PHOTO_' . $key . '.' . $file->getClientOriginalExtension();
                    if (!File::isDirectory($basePath))
                        File::makeDirectory($basePath, 0777, true, true);
                    $file->move($basePath, $fileName);
                    $docName = $request->attachment_names[$key] ?? 'Attachment-' . ($key + 1);
                    $license->attachments()->create([
                        'name'      => $docName,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientMimeType(),
                        'file_path' => $fileName,
                    ]);
                }
            }

            LicenseNextApprover::where('license_id', $license->id)->delete();
            if ($next_user != null) {
                $next_user_ids = explode(',', $next_user->value);
                foreach ($next_user_ids as $next_user_id) {
                    $nextuser                           = User::find($next_user_id);
                    $licensenextapprover                = new LicenseNextApprover;
                    $licensenextapprover->license_id    = $license->id;
                    $licensenextapprover->user_id       = $nextuser->id;
                    $licensenextapprover->user_name     = $nextuser->name;
                    $licensenextapprover->save();

                    // send email to next user
                    Mail::send('mail.license_approval', ['data' => $license, 'nextuser' => $nextuser], function ($message) use ($license, $nextuser) {
                        $message->to($nextuser->email);
                        $message->subject('License to Operate (LTO)_' . $license->type . '_Approval Request');
                    });
                }
            }

            DB::commit();
            return redirect()->route('transaction-license.index')->with('message', 'Saved successfully');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            echo $e->getMessage();
            exit;
            return back()->with('Error', 'Failed to save : ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $data = License::with('licenseItems')->findOrFail($id);
        $logs = LicenseLog::where('license_id', $data->id)->orderBy('created_at', 'asc')->get();
        $attachments = LicenseAttachment::where('license_id', $data->id)->get();
        $next_user_ids = LicenseNextApprover::where('license_id', $data->id)->pluck('user_id')->toArray();
        $delegated = Delegation::where('type', 'ALL')
            ->whereIn('delegator', $next_user_ids)
            ->where('delegatee', $user->id)
            ->whereDate('begin_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->exists();
        $list_user = ['' => '-'] + User::where('status', 'active')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
        $is_able_to_view_pdf = in_array($user->role_id, [1, 2]) && $data->status == 'COMPLETED';
        $is_able_to_admin_edit = in_array($user->role_id, [1, 2]);
        $is_able_to_admin_delete = in_array($user->role_id, [1, 2]);
        return view('transaction.license_operation.show', compact(
            'user',
            'data',
            'logs',
            'attachments',
            'next_user_ids',
            'list_user',
            'delegated',
            'is_able_to_view_pdf',
            'is_able_to_admin_edit',
            'is_able_to_admin_delete',
        ));
    }

    public function edit(Request $request, string $id)
    {
        $user = Auth::user();
        $data = License::with('licenseItems', 'licenseZones')->findOrFail($id);
        $list_user2 = User::where('status', 'active')
            ->pluck('name', 'id')
            ->toArray();
        $list_department = Department::pluck('name', 'id')->toArray();
        $list_company = Company::pluck('name', 'id')->toArray();
        $type = $request->type ?? $data->type;
        $list_type = [
            'KIMPER' => 'KIMPER',
            'KIMPAK' => 'KIMPAK'
        ];
        $list_zone = [];
        $list_item = [];
        $list_item_group = [];
        if ($type === 'KIMPER') {
            $list_zone = OHSMaster::where('type', 'LICENSE_ZONE')->get();
            $list_item = OHSMaster::where('type', 'LICENSE_KIMPER')->get();
            foreach ($list_item as $v) {
                $list_item_group[$v->value2 . '_' . $v->value3][] = $v;
            }
        } else {
            $list_item = OHSMaster::where('type', 'LICENSE_KIMPAK')->get();
        }
        $data->saved_item_names = LicenseItem::where('license_id', $data->id)
            ->orderBy('id', 'ASC')
            ->pluck('name', 'code')
            ->toArray();

        $data->saved_zone_codes = LicenseZone::where('license_id', $data->id)
            ->orderBy('id', 'ASC')
            ->pluck('code')
            ->toArray();
        return view('transaction.license_operation.edit', compact(
            'data',
            'user',
            'list_user2',
            'list_department',
            'list_company',
            'list_type',
            'list_zone',
            'list_item',
            'list_item_group',
            'type',
        ));
    }

    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $request->validate([
            'type'          => 'required|in:KIMPER,KIMPAK',
            'employee_id'   => 'required',
            'name'          => 'required|string|max:100',
            'position'      => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'company_id'    => 'required|exists:companies,id',
            'reason'        => 'required',
        ]);
        DB::beginTransaction();
        try {
            $license                            = License::findOrFail($id);
            $license->type                      = $request->type;
            $license->no                        = $this->getNextNo();
            $license->date                      = date('Y-m-d');
            $license->employee_id               = $request->employee_id;
            $license->name                      = $request->name;
            $license->position                  = $request->position;
            $license->department_id             = $request->department_id;
            $department                         = Department::find($request->department_id);
            $license->department_name           = $department->name;
            $license->company_id                = $request->company_id;
            $company                            = Company::find($request->company_id);
            $license->company_name              = $company->name;

            $license->requestor_id              = $user->id;
            $license->requestor_name            = $user->name;
            $license->requestor_department_id   = $user->department_id;
            $department                         = Department::find($user->department_id);
            $license->requestor_department_name = $department->name;
            $license->reason                    = $request->reason;
            if ($license->type == "KIMPER") {
                $license->driving_license_expiry_date = $request->driving_license_expiry_date;
            }
            $license->save();

            $approval_level             = !empty($license->last_approval_level) ? $license->last_approval_level - 1 : 0;
            $next_user                  = $this->get_next_approver($license, $approval_level);
            $license->next_user_id      = $next_user->id;
            $license->next_user_name    = $next_user->name;
            $license->next_action       = $next_user->action;
            $license->status            = 'APPROVAL_REQUIRED';
            $license->approval_level    = $next_user->level;

            $license->last_action       = 'UPDATE';
            $license->last_user_id      = $user->id;
            $license->last_user_name    = $user->name;
            $license->last_approval_level   = 0;

            $license->save();

            $log                = new LicenseLog;
            $log->license_id    = $license->id;
            $log->user_id       = $user->id;
            $log->user_name     = $user->name;
            $log->status        = $license->status;
            $log->remarks       = $request->remarks;
            $log->event         = $license->last_action;
            $log->delegator_uid = null;
            $log->save();

            LicenseItem::where('license_id', $license->id)->delete();
            $item_ids = $request->item_id != null ? $request->item_id : [];
            foreach ($item_ids as $v) {
                $licenseitem                = new LicenseItem;
                $licenseitem->license_id    = $license->id;
                $ohsmaster                  = OHSMaster::find($v);
                $licenseitem->code          = $ohsmaster->code;
                $line_item_name             = $ohsmaster->name;
                if ($ohsmaster->type == "LICENSE_KIMPAK" && $ohsmaster->code == "99") {
                    $item_name_99 = !empty($request->item_name_99) ? $request->item_name_99 : '';
                    $line_item_name = $ohsmaster->name . ' : ' . $item_name_99;
                }
                $licenseitem->name          = $line_item_name;
                $licenseitem->save();
            }
            if ($license->type == 'KIMPER') {
                LicenseZone::where('license_id', $license->id)->delete();
                $zone_ids = $request->zone_id != null ? $request->zone_id : [];
                foreach ($zone_ids as $v) {
                    $licensezone                = new LicenseZone;
                    $licensezone->license_id    = $license->id;
                    $ohsmaster                  = OHSMaster::find($v);
                    $licensezone->code          = $ohsmaster->code;
                    $licensezone->color_code    = $ohsmaster->value3;
                    $licensezone->remarks       = $ohsmaster->name;
                    $licensezone->save();
                }
            }

            $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'license';
            if ($request->has('delete_attachments')) {
                foreach ($request->delete_attachments as $attachmentId) {
                    $attachment = $license->attachments()->find($attachmentId);
                    if ($attachment) {
                        $path = $basePath . '/' . $attachment->file_path;
                        if (File::exists($path)) {
                            File::delete($path);
                        }
                        $attachment->delete();
                    }
                }
            }

            $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'license';
            if ($request->hasFile('attachment_files')) {
                foreach ($request->file('attachment_files') as $key => $file) {
                    $fileName = 'OHS_LTO_' . $license->id . '_PHOTO_' . $key . '.' . $file->getClientOriginalExtension();
                    if (!File::isDirectory($basePath))
                        File::makeDirectory($basePath, 0777, true, true);
                    $file->move($basePath, $fileName);
                    $docName = $request->attachment_names[$key] ?? 'Attachment-' . ($key + 1);
                    $license->attachments()->create([
                        'name'      => $docName,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientMimeType(),
                        'file_path' => $fileName,
                    ]);
                }
            }

            LicenseNextApprover::where('license_id', $license->id)->delete();
            if ($next_user != null) {
                $next_user_ids = explode(',', $next_user->value);
                foreach ($next_user_ids as $next_user_id) {
                    $nextuser                           = User::find($next_user_id);
                    $licensenextapprover                = new LicenseNextApprover;
                    $licensenextapprover->license_id    = $license->id;
                    $licensenextapprover->user_id       = $nextuser->id;
                    $licensenextapprover->user_name     = $nextuser->name;
                    $licensenextapprover->save();

                    // send email to next user
                    Mail::send('mail.license_approval', ['data' => $license, 'nextuser' => $nextuser], function ($message) use ($license, $nextuser) {
                        $message->to($nextuser->email);
                        $message->subject('License to Operate (LTO)_' . $license->type . '_Approval Request');
                    });
                }
            }

            DB::commit();

            return redirect()->route('transaction-license.index')->with('success', 'Data has been updated successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function admin_edit(Request $request, string $id)
    {
        $user = Auth::user();
        $data = License::with('licenseItems', 'licenseZones')->findOrFail($id);
        $list_user = User::where('status', 'active')
            ->pluck('name', 'id')
            ->toArray();
        $list_department = Department::pluck('name', 'id')->toArray();
        $list_company = Company::pluck('name', 'id')->toArray();
        $type = $request->type ?? $data->type;
        $list_type = [
            'KIMPER' => 'KIMPER',
            'KIMPAK' => 'KIMPAK'
        ];
        $list_zone = [];
        $list_item = [];
        $list_item_group = [];
        if ($type === 'KIMPER') {
            $list_zone = OHSMaster::where('type', 'LICENSE_ZONE')->get();
            $list_item = OHSMaster::where('type', 'LICENSE_KIMPER')->get();
            foreach ($list_item as $v) {
                $list_item_group[$v->value2 . '_' . $v->value3][] = $v;
            }
        } else {
            $list_item = OHSMaster::where('type', 'LICENSE_KIMPAK')->get();
        }
        $data->saved_item_names = LicenseItem::where('license_id', $data->id)
            ->orderBy('id', 'ASC')
            ->pluck('name', 'code')
            ->toArray();

        $data->saved_zone_codes = LicenseZone::where('license_id', $data->id)
            ->orderBy('id', 'ASC')
            ->pluck('code')
            ->toArray();
        $flow_type = Flow::where('process', 'LTO_' . $data->type)
            ->where('level', $data->approval_level)
            ->value('type') ?? '';
        return view('transaction.license_operation.admin_edit', compact(
            'data',
            'user',
            'list_user',
            'list_department',
            'list_company',
            'list_type',
            'list_zone',
            'list_item',
            'list_item_group',
            'type',
            'flow_type',
        ));
    }

    public function admin_update(Request $request, string $id)
    {
        $user = Auth::user();
        $request->validate([
            'type'          => 'required|in:KIMPER,KIMPAK',
            'employee_id'   => 'required',
            'name'          => 'required|string|max:100',
            'position'      => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'company_id'    => 'required|exists:companies,id',
            'reason'        => 'required',
        ]);
        DB::beginTransaction();
        try {
            $license                            = License::find($request->id);
            $license->employee_id               = $request->employee_id;
            $license->name                      = $request->name;
            $license->position                  = $request->position;
            $license->department_id             = $request->department_id;
            $department                         = Department::find($request->department_id);
            $license->department_name           = $department->name;
            $license->company_id                = $request->company_id;
            $company                            = Company::find($request->company_id);
            $license->company_name              = $company->name;
            $license->reason                    = $request->reason;

            $license->theory_tester_id          = $request->theory_tester_id;
            $theory_tester                      = User::find($license->theory_tester_id);
            $license->theory_tester_name        = !empty($theory_tester->name) ? $theory_tester->name : null;
            $license->practice_tester_id        = $request->practice_tester_id;
            $practice_tester                    = User::find($license->practice_tester_id);
            $license->practice_tester_name      = !empty($practice_tester->name) ? $practice_tester->name : null;
            if ($license->type == "KIMPER") {
                $license->driving_license_expiry_date   = $request->driving_license_expiry_date;
                $license->first_aid_trainer_id          = $request->first_aid_trainer_id;
                $first_aid_trainer                      = User::find($license->first_aid_trainer_id);
                $license->first_aid_trainer_name        = !empty($first_aid_trainer->name) ? $first_aid_trainer->name : null;
                $license->ddc_trainer_id                = $request->ddc_trainer_id;
                $ddc_trainer                            = User::find($license->ddc_trainer_id);
                $license->ddc_trainer_name              = !empty($ddc_trainer->name) ? $ddc_trainer->name : null;
            }
            $flow = Flow::where('process', 'LTO_' . $license->type)->where('level', $license->approval_level)->first();
            if ($flow != null && in_array($flow->type, ['THEORY_TESTER', 'PRACTICE_TESTER', 'FIRST_AID_TRAINER', 'DDC_TRAINER'])) {
                if ($flow->type == 'THEORY_TESTER') {
                    $license->next_user_id = $license->theory_tester_id;
                    $license->next_user_name = $license->theory_tester_name;
                } else if ($flow->type == 'PRACTICE_TESTER') {
                    $license->next_user_id = $license->practice_tester_id;
                    $license->next_user_name = $license->practice_tester_name;
                } else if ($flow->type == 'FIRST_AID_TRAINER') {
                    $license->next_user_id = $license->first_aid_trainer_id;
                    $license->next_user_name = $license->first_aid_trainer_name;
                } else if ($flow->type == 'DDC_TRAINER') {
                    $license->next_user_id = $license->ddc_trainer_id;
                    $license->next_user_name = $license->ddc_trainer_name;
                }
                if (!empty($license->next_user_id)) {
                    LicenseNextApprover::where('license_id', $license->id)->delete();
                    $licensenextapprover                = new LicenseNextApprover;
                    $licensenextapprover->license_id    = $license->id;
                    $licensenextapprover->user_id       = $license->next_user_id;
                    $licensenextapprover->user_name     = $license->next_user_name;
                    $licensenextapprover->save();
                }
            }
            $license->last_action       = 'ADMIN_UPDATE';
            $license->last_user_id      = $user->id;
            $license->last_user_name    = $user->name;
            $license->save();

            $log                = new LicenseLog;
            $log->license_id    = $license->id;
            $log->user_id       = $user->id;
            $log->user_name     = $user->name;
            $log->status        = $license->status;
            $log->remarks       = $request->remarks;
            $log->event         = $license->last_action;
            $log->delegator_uid = null;
            $log->save();

            LicenseItem::where('license_id', $license->id)->delete();
            $item_ids = $request->item_id != null ? $request->item_id : [];
            foreach ($item_ids as $v) {
                $licenseitem                = new LicenseItem;
                $licenseitem->license_id    = $license->id;
                $ohsmaster                  = OHSMaster::find($v);
                $licenseitem->code          = $ohsmaster->code;
                $line_item_name             = $ohsmaster->name;
                if ($ohsmaster->type == "LICENSE_KIMPAK" && $ohsmaster->code == "99") {
                    $item_name_99 = !empty($request->item_name_99) ? $request->item_name_99 : '';
                    $line_item_name = $ohsmaster->name . ' : ' . $item_name_99;
                }
                $licenseitem->name          = $line_item_name;
                $licenseitem->save();
            }
            if ($license->type == 'KIMPER') {
                LicenseZone::where('license_id', $license->id)->delete();
                $zone_ids = $request->zone_id != null ? $request->zone_id : [];
                foreach ($zone_ids as $v) {
                    $licensezone                = new LicenseZone;
                    $licensezone->license_id    = $license->id;
                    $ohsmaster                  = OHSMaster::find($v);
                    $licensezone->code          = $ohsmaster->code;
                    $licensezone->color_code    = $ohsmaster->value3;
                    $licensezone->remarks       = $ohsmaster->name;
                    $licensezone->save();
                }
            }

            $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'license';
            if ($request->has('delete_attachments')) {
                foreach ($request->delete_attachments as $attachmentId) {
                    $attachment = $license->attachments()->find($attachmentId);
                    if ($attachment) {
                        $path = $basePath . '/' . $attachment->file_path;
                        if (File::exists($path)) {
                            File::delete($path);
                        }
                        $attachment->delete();
                    }
                }
            }

            $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'license';
            if ($request->hasFile('attachment_files')) {
                foreach ($request->file('attachment_files') as $key => $file) {
                    $fileName = 'OHS_LTO_' . $license->id . '_PHOTO_' . $key . '.' . $file->getClientOriginalExtension();
                    if (!File::isDirectory($basePath))
                        File::makeDirectory($basePath, 0777, true, true);
                    $file->move($basePath, $fileName);
                    $docName = $request->attachment_names[$key] ?? 'Attachment-' . ($key + 1);
                    $license->attachments()->create([
                        'name'      => $docName,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientMimeType(),
                        'file_path' => $fileName,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('transaction-license.index')->with('success', 'Data has been updated successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $license = License::findOrFail($id);
        $basePath = rtrim(env('FILE_PATH', '/data/msafe/'), '/') . '/license';
        DB::transaction(function () use ($license, $basePath) {
            foreach ($license->attachments as $file) {
                if (File::exists($basePath . '/' . $file->file_path)) {
                    File::delete($basePath . '/' . $file->file_path);
                }
            }
            $license->licenseItems()->delete();
            $license->licenseZones()->delete();
            $license->attachments()->delete();
            $license->logs()->delete();
            $license->delete();
        });
        return redirect()->route('transaction-license.index')->with('success', 'Data has been deleted successfully.');
    }

    private function getNextNo()
    {
        $prefix = 'LTO-' . date('m') . '-' . date('Y') . '-';
        $license = License::selectRaw("CAST(SUBSTRING(no,13,4) AS SIGNED INTEGER) AS no2")
            ->whereRaw("SUBSTRING(no,8,4) = YEAR(CURRENT_DATE)")
            ->orderBy('no2', 'DESC')
            ->first();
        if ($license == null) {
            return $prefix . '0001';
        } else {
            $licenseno = $license->no2;
            $licenseno = intval($licenseno);
            $licenseno += 1;
            return sprintf($prefix . "%04d", $licenseno);
        }
    }

    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $license = License::find($id);
            $license->last_action           = $request->action;
            $license->last_user_id          = $user->id;
            $license->last_user_name        = $user->name;
            $license->last_approval_level   = $license->approval_level;
            $delegator_uid                  = $license->next_user_id;
            $next_user                      = null;
            $next_status                    = 'REJECTED';
            $next_approval_level            = 0;
            if ($request->action == 'APPROVE') {
                if ($license->next_action == 'APPROVAL_AND_ASSIGNMENT') {
                    if (!empty($request->theory_tester_id)) {
                        $license->theory_tester_id      = $request->theory_tester_id;
                        $theory_tester                  = User::find($license->theory_tester_id);
                        $license->theory_tester_name    = $theory_tester->name;
                    } else {
                        $license->theory_tester_id      = null;
                        $license->theory_tester_name    = null;
                        $license->approval_level        += 1;
                    }

                    if (!empty($request->practice_tester_id)) {
                        $license->practice_tester_id    = $request->practice_tester_id;
                        $practice_tester                = User::find($license->practice_tester_id);
                        $license->practice_tester_name  = $practice_tester->name;
                    } else {
                        $license->practice_tester_id      = null;
                        $license->practice_tester_name    = null;
                        $license->approval_level          += 1;
                    }

                    if ($license->type == 'KIMPER') {
                        if (!empty($request->first_aid_trainer_id)) {
                            $license->first_aid_trainer_id      = $request->first_aid_trainer_id;
                            $first_aid_trainer                  = User::find($license->first_aid_trainer_id);
                            $license->first_aid_trainer_name    = $first_aid_trainer->name;
                        } else {
                            $license->first_aid_trainer_id      = null;
                            $license->first_aid_trainer_name    = null;
                            $license->approval_level            += 1;
                        }

                        if (!empty($request->ddc_trainer_id)) {
                            $license->ddc_trainer_id            = $request->ddc_trainer_id;
                            $ddc_trainer                        = User::find($license->ddc_trainer_id);
                            $license->ddc_trainer_name          = $ddc_trainer->name;
                        } else {
                            $license->ddc_trainer_id            = null;
                            $license->ddc_trainer_name          = null;
                            $license->approval_level            += 1;
                        }
                    }
                }
                $next_user = $this->get_next_approver($license, $license->approval_level);
                if ($next_user == null) {
                    $next_status = 'COMPLETED';
                    $license->license_status = 'ACTIVE';
                } else {
                    $next_status = 'APPROVAL_REQUIRED';
                    $next_approval_level = $next_user->level;
                }
            }
            $license->status             = $next_status;
            $license->approval_level     = $next_approval_level;
            $license->next_action        = $next_user == null ? null : $next_user->action;
            $license->next_user_id       = $next_user == null ? null : $next_user->id;
            $license->next_user_name     = $next_user == null ? null : $next_user->name;
            $license->remarks            = $request->remarks;
            if ($license->status == 'COMPLETED') {
                if ($license->type == 'KIMPER') {
                    $license->expiry_date = date('Y-m-d', strtotime('+ 2 year'));
                    if (!empty($license->driving_license_expiry_date)) {
                        if ($license->driving_license_expiry_date < $license->expiry_date) {
                            $license->expiry_date = $license->driving_license_expiry_date;
                        }
                    }
                } else {
                    $license->expiry_date = date('Y-m-d', strtotime('+ 1 year'));
                }
            }
            $license->save();

            $licenselog                = new LicenseLog;
            $licenselog->license_id    = $license->id;
            $licenselog->user_id       = $user->id;
            $licenselog->user_name     = $user->name;
            $licenselog->status        = $license->status;
            $licenselog->remarks       = $request->remarks;
            $licenselog->event         = $license->last_action;
            $licenselog->delegator_uid = $delegator_uid;
            $licenselog->save();

            $destinationPath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'license';
            for ($i = 1; $i <= 4; $i++) {
                if ($request->hasfile('file_' . $i)) {
                    $file               = $request->file('file_' . $i);
                    $fileName    = 'OHS_LTO_' . $license->id . '_' . $licenselog->id . '_' . $i . '.' . $file->getClientOriginalExtension();
                    $file->move($destinationPath, $fileName);
                    $licenseattachment              = new LicenseAttachment;
                    $licenseattachment->license_id  = $license->id;
                    $licenseattachment->name        = $file->getClientOriginalName();
                    $licenseattachment->file_type   = $file->getClientMimeType();
                    $licenseattachment->file_name   = $file->getClientOriginalName();
                    $licenseattachment->file_path   = $fileName;
                    $licenseattachment->save();
                }
            }

            LicenseNextApprover::where('license_id', $license->id)->delete();
            if (!empty($license->next_action)) {
                $next_user_ids = explode(',', $next_user->value);
                foreach ($next_user_ids as $next_user_id) {
                    $nextuser                           = User::find($next_user_id);
                    $licensenextapprover                = new LicenseNextApprover;
                    $licensenextapprover->license_id    = $license->id;
                    $licensenextapprover->user_id       = $nextuser->id;
                    $licensenextapprover->user_name     = $nextuser->name;
                    $licensenextapprover->save();

                    // send email to next user
                    Mail::send('mail.license_approval', ['data' => $license, 'nextuser' => $nextuser], function ($message) use ($license, $nextuser) {
                        $message->to($nextuser->email);
                        $message->subject('License to Operate (LTO)_' . $license->type . '_Approval Request');
                    });
                }
            }

            // send email to requestor
            $requestor = User::find($license->requestor_id);
            Mail::send('mail.license_status_notification', ['data' => $license, 'requestor' => $requestor], function ($message) use ($license, $requestor) {
                $message->to($requestor->email);
                $message->subject('License to Operate (LTO)_' . $license->type . '_Status changed');
            });

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
        return redirect()->route('transaction-license.index', ['action' => 'APPROVAL'])
            ->with('success', 'Updated successfully');
    }

    public function view_pdf(Request $request, $id)
    {
        $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'license';
        $license = License::find($id);
        $licenselog = LicenseLog::where('license_id', $license->id)
            ->where('status', 'COMPLETED')
            ->orderBy('created_at', 'DESC')
            ->first(['created_at']);
        $license->completed_date = !empty($licenselog) ? date('d M Y', strtotime($licenselog->created_at)) : '';
        if ($license->type == 'KIMPER') {
            $license->item = LicenseItem::selectRaw("GROUP_CONCAT(m.name) as remarks, m.value1, m.value2")
                ->where('license_id', $license->id)
                ->join('ohs_masters AS m', function ($join) use ($license) {
                    $join->on('m.code', '=', 'license_items.code');
                    $join->where('m.type', '=', 'LICENSE_' . $license->type);
                })
                ->groupBy('m.value1')
                ->groupBy('m.value2')
                ->orderBy('m.value2', 'ASC')
                ->orderBy('m.value1', 'ASC')
                ->get();
        } else {
            $license->item = LicenseItem::where('license_id', $license->id)->get(['name']);
        }
        $license->expiry_date = date('d-M-Y', strtotime($license->expiry_date));
        $res = LicenseAttachment::where('license_id', $license->id)->get();
        $photo_path = count($res) > 0 ? $basePath . '/' . $res[0]->file_path : public_path('img/user.png');
        $photo_type = pathinfo($photo_path, PATHINFO_EXTENSION);
        $photo_data = file_get_contents($photo_path);
        $photo_base64 = 'data:image/' . $photo_type . ';base64,' . base64_encode($photo_data);
        $license->photo_base64 = $photo_base64;
        $license->zone = LicenseZone::where('license_id', $license->id)->orderBy('id', 'ASC')->get();
        $license->log = LicenseLog::where('license_id', $license->id)->orderBy('id', 'ASC')->get();
        $pdf = PDF::loadView('transaction.license_operation.pdf_' . strtolower($license->type), compact('license'));
        return $pdf->stream();
    }

    public function detail_qrcode($id)
    {
        $user = Auth::user();
        $license = License::find($id);
        $licenselog = LicenseLog::where('license_id', $license->id)
            ->where('status', 'COMPLETED')
            ->orderBy('created_at', 'DESC')
            ->first(['created_at']);
        $license->completed_date = !empty($licenselog) ? date('d M Y', strtotime($licenselog->created_at)) : '';
        return view('transaction.license_operation.detail_qrcode', [
            'license'               => $license
        ]);
    }
}
