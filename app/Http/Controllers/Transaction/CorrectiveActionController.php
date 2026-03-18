<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Models\CorrectiveAction;
use App\Models\CorrectiveActionEvidence;
use App\Models\CorrectiveActionLog;
use App\Models\CorrectiveActionNextApprover;
use App\Models\Delegation;
use App\Models\Department;
use App\Models\Flow;
use App\Models\Hazard;
use App\Models\HazardLog;
use App\Models\WorkplaceControlAction;
use App\Models\Location;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Exports\CorrectiveActionExport;
use Maatwebsite\Excel\Facades\Excel;

use function Symfony\Component\Clock\now;

class CorrectiveActionController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $menuId = PermissionHelper::getCurrentMenuId();
        $query = CorrectiveAction::selectRaw("corrective_actions.*, (CASE `source` WHEN 'HZ' THEN 'HAZARD' WHEN 'IN' THEN 'INCIDENT' WHEN 'WC' THEN CONCAT('WORKPLACE CONTROL - ',REPLACE((SELECT wpc.type FROM workplace_controls wpc WHERE wpc.id = corrective_actions.source_id), '_', ' ')) WHEN 'AUD' THEN 'AUDIT' WHEN 'MTG' THEN 'MEETING' WHEN 'OTH' THEN 'OTHER' ELSE `source` END) as source_desc, (SELECT GROUP_CONCAT(user_name) FROM corrective_action_next_approvers na WHERE na.action_id = corrective_actions.id) as next_user");
        $action = !empty($request->action) ? strtoupper($request->action) : '';

        // logic shorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($action == 'MONITORING') {
            //
        } else if ($action == 'DEPARTMENT_MONITORING') {
            $res = User::where('department_id', $user->department_id)->get(['id']);
            $list_user_id = [];
            foreach ($res as $v) {
                $list_user_id[] = $v->id;
            }
            $query->whereIn('risk_issuer_id', $list_user_id);
        } else if ($action == 'TO_DO') {
            $user_ids = [$user->id];
            $delegation = Delegation::where('type', 'ALL')
                ->where('delegatee', $user->id)
                ->where('begin_date', '<=', date('Y-m-d'))
                ->where('end_date', '>=', date('Y-m-d'))
                ->first();
            if ($delegation != null) {
                $user_ids[] = $delegation->delegator;
            }
            $res = User::whereRaw("CONCAT(department_id,'-',company_id) IN (SELECT CONCAT(department_id,'-',company_id) FROM users WHERE id IN (" . implode(',', $user_ids) . "))")
                ->get(['id']);
            foreach ($res as $v) {
                $user_ids[] = $v->id;
            }
            $res = CorrectiveActionNextApprover::whereIn('user_id', $user_ids)->get(['action_id']);
            $ids = [];
            foreach ($res as $v) {
                $ids[] = $v->action_id;
            }
            $query->whereIn('status', ['ACTION_REQUIRED', 'REJECTED'])->whereIn('id', $ids);
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
            $res = CorrectiveActionNextApprover::whereIn('user_id', $user_ids)->get(['action_id']);
            $ids = [];
            foreach ($res as $v) {
                $ids[] = $v->action_id;
            }
            $query->whereIn('status', ['APPROVAL_REQUIRED'])->whereIn('id', $ids);
        } else if ($action == 'APPROVAL_HISTORY') {
            $query->whereRaw("(id IN (SELECT DISTINCT action_id FROM corrective_action_logs WHERE (user_id = " . $user->id . " OR delegator_uid = " . $user->id . ") AND event NOT IN ('CREATE', 'UPDATE')))");
        } else {
            $query->where('risk_issuer_id', $user->id);
        }
        if ($request->filled('risk_issue_date')) {
            $risk_issue_date   = explode(' to ', $request->risk_issue_date);
            $risk_issue_date_1 = date('Y-m-d', strtotime($risk_issue_date[0]));
            $risk_issue_date_2 = date('Y-m-d', strtotime($risk_issue_date[1]));
            $query->whereBetween('risk_issue_date', [$risk_issue_date_1, $risk_issue_date_2]);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('risk_issuer_name')) {
            $query->where('risk_issuer_name', 'LIKE', '%' . $request->risk_issuer_name . '%');
        }
        if ($request->filled('source_no')) {
            $query->where('source_no', 'LIKE', '%' . $request->source_no . '%');
        }
        if ($request->filled('department_name')) {
            $query->where('department_name', 'LIKE', '%' . $request->department_name . '%');
        }
        $corrAct = $query->orderBy($sortBy, $sortOrder)->paginate(10)->withQueryString();
        return view('transaction.corective_action.index', compact('corrAct', 'menuId'));
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        return Excel::download(new CorrectiveActionExport($request, $user), 'corrective_action.xlsx');
    }

    public function inputEvidence(Request $request, $id)
    {
        $request->validate([
            'evidence_files.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'remark' => 'nullable|string'
        ]);
        if ($request->hasFile('evidence_files')) {
            $files = $request->file('evidence_files');
            $subFolder = 'incident';
            foreach ($files as $file) {
                $path = $file->store($subFolder, 'local');
                CorrectiveActionEvidence::create([
                    'action_id' => $id,
                    'remark'    => $request->remark,
                    'file_type' => $file->getClientMimeType(),
                    'file_path' => $path,
                ]);
            }
        }
        return redirect()->back()->with('success', 'Evidence uploaded successfully.');
    }

    public function create()
    {
        $loc = Location::all();
        $depart = Department::all();
        $respon = User::all();
        return view('transaction.corective_action.create', compact('loc', 'depart', 'respon'));
    }

    function get_next_approver($assignee_id, $level)
    {
        $next_user = null;
        $flow = Flow::where('process', 'CORRECTIVE_ACTION')
            ->where('level', '>', $level)
            ->orderBy('level', 'ASC')->first();
        if ($flow != null) {
            if ($flow->type == 'ASSIGNEE') {
                $next_user          = User::find($assignee_id);
                $next_user->value   = $assignee_id;
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

    function getNextNo($source)
    {
        $prefix = $source . '-' . date('m') . '-' . date('Y') . '-';
        $ca = CorrectiveAction::selectRaw("SUBSTRING(source_no,10+LENGTH(`source`),3) AS source_no2")
            ->whereRaw("SUBSTRING(source_no,5+LENGTH(`source`),4) = YEAR(CURRENT_DATE)")
            ->where('source', $source)
            ->orderBy('source_no2', 'DESC')
            ->first();
        if ($ca == null) {
            return $prefix . '001';
        } else {
            $no = $ca->source_no2;
            $no = str_replace($prefix, '', $no);
            $no = intval($no);
            $no += 1;
            return sprintf($prefix . "%03d", $no);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'risk_issue_date'       => 'required|date',
            'department_id'         => 'required|exists:departments,id',
            'risk_description'      => 'required|string',
            'location'              => 'required|string',
            'corrective_action'     => 'required|string',
            'due_date'              => 'required|date|after_or_equal:risk_issue_date',
            'responsible_person_id' => 'required|exists:users,id',
        ], [
            'department_id.required'         => 'Departemen harus dipilih.',
            'risk_description.required'      => 'Deskripsi risiko tidak boleh kosong.',
            'due_date.after_or_equal'        => 'Batas waktu tidak boleh sebelum tanggal temuan.',
            'responsible_person_id.required' => 'PIC (Penanggung Jawab) harus dipilih.',
        ]);
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $oca = new CorrectiveAction;
            $oca->source                 = $request->source;
            $oca->source_id             = null;
            $oca->source_no               = $this->getNextNo($oca->source);
            $oca->risk_issue_date         = $request->risk_issue_date;
            $oca->risk_issuer_id         = $user->id;
            $oca->risk_issuer_name         = $user->name;
            $oca->risk_issue_date       = $request->risk_issue_date;
            $oca->risk_description         = $request->risk_description;
            $oca->location               = $request->location;
            $oca->department_id         = $request->department_id;
            $department                 = Department::find($request->department_id);
            $oca->department_name       = $department->name;
            $oca->responsible_person_id = $request->responsible_person_id;
            $user                         = User::find($request->responsible_person_id);
            $oca->responsible_person_name     = $user->name;
            $oca->corrective_action     = $request->corrective_action;
            $oca->due_date                 = $request->due_date;
            $oca->status                 = $request->status;
            $oca->last_action             = 'CREATE';
            $oca->last_user_id          = $user->id;
            $oca->last_user_name        = $user->name;
            $next_user                  = null;
            if ($oca->status == 'ACTION_REQUIRED') {
                $next_user              = $this->get_next_approver($oca->responsible_person_id, 0);
                $oca->next_action       = 'INPUT_EVIDENCE';
                $oca->next_user_id      = $next_user->id;
                $oca->next_user_name    = $next_user->name;
                $oca->approval_level    = 1;
            } else {
                $oca->next_action       = null;
                $oca->next_user_id      = null;
                $oca->next_user_name    = null;
                $oca->approval_level    = 0;
            }
            $oca->save();

            $log                = new CorrectiveActionLog;
            $log->action_id    = $oca->id;
            $log->user_id       = $user->id;
            $log->user_name     = $user->name;
            $log->status        = $oca->status;
            $log->remarks       = $request->remarks;
            $log->event         = $oca->last_action;
            $log->delegator_uid = null;
            $log->save();

            CorrectiveActionNextApprover::where('action_id', $oca->id)->delete();
            if ($next_user != null) {
                $next_user_ids = explode(',', $next_user->value);
                foreach ($next_user_ids as $next_user_id) {
                    $nextuser                       = User::find($next_user_id);
                    $ocanextapprover                = new CorrectiveActionNextApprover;
                    $ocanextapprover->action_id     = $oca->id;
                    $ocanextapprover->user_id       = $nextuser->id;
                    $ocanextapprover->user_name     = $nextuser->name;
                    $ocanextapprover->save();

                    // send email to next user
                    Mail::send('mail.corrective_action_notification', ['data' => $oca, 'nextuser' => $nextuser], function ($message) use ($oca, $nextuser) {
                        $message->to($nextuser->email);
                        $message->subject('CAR_' . $oca->source . '_Action required');
                    });
                }
            }
            DB::commit();
            return redirect()->route('transaction-correctiveAction.index')->with('success', 'Corrective Action has been saved.');
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function show(string $id)
    {
        $user = Auth::user();
        $menuId = PermissionHelper::getCurrentMenuId();
        $loc = Location::all();
        $depart = Department::all();
        $respon = User::all();
        $corr = CorrectiveAction::with('evidences')->findOrFail($id);
        $delegated = Delegation::where('type', 'ALL')
            ->where('delegator', $corr->next_user_id)
            ->where('delegatee', Auth::user()->id)
            ->whereDate('begin_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->exists();
        $res = CorrectiveActionNextApprover::where('action_id', $id)
            ->get(['user_id', 'user_name']);
        $next_user_ids = $res->pluck('user_id')->toArray();
        $next_user_names = $res->pluck('user_name')->toArray();
        if ($corr->next_action === 'INPUT_EVIDENCE' && !empty($next_user_ids)) {
            $res = User::whereRaw("CONCAT(department_id,'-',company_id) IN (SELECT CONCAT(department_id,'-',company_id) FROM users WHERE id IN (" . implode(',', $next_user_ids) . "))")
                ->get(['id', 'name']);
            $next_user_ids = $res->pluck('id')->toArray();
            $next_user_names = $res->pluck('name')->toArray();
        }
        $corr->next_user_name = implode(', ', $next_user_names);
        if ($corr->next_action === 'APPROVAL') {
            $list_department = Department::pluck('name', 'id')->toArray();
            $list_user = User::where('status', 'active')
                ->pluck('name', 'id')
                ->toArray();
        } else {
            $list_department = [];
            $list_user = [];
        }
        return view('transaction.corective_action.show', compact(
            'corr',
            'loc',
            'depart',
            'respon',
            'menuId',
            'delegated',
            'next_user_ids',
            'next_user_names',
            'user',
            'list_department',
            'list_user'
        ));
    }


    public function edit(string $id)
    {
        $corr = CorrectiveAction::findOrFail($id);
        $loc = Location::all();
        $depart = Department::all();
        $respon = User::all();
        return view('transaction.corective_action.edit', compact('corr', 'loc', 'depart', 'respon'));
    }

    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $ca = CorrectiveAction::findOrFail($id);

        // 1. SKENARIO: INPUT EVIDENCE (Triggered by hidden input 'type')
        if ($request->type === 'input_evidence') {
            // Logika Approval Level (bisa disesuaikan jika ada flow berikutnya)
            // Jika hanya upload tanpa pindah level, gunakan level yang ada
            // Cari flow berikutnya setelah level saat ini
            // Ini penting agar setelah upload, tugas pindah ke orang yang harus approve
            $nextFlow = Flow::where('level', '>', $ca->approval_level)
                ->orderBy('level', 'asc')
                ->first();

            $nextUserId = $ca->next_user_id; // Default: tetap di user sekarang
            $nextUserName = $ca->next_user_name;
            $nextAction = 'INPUT_EVIDENCE'; // Default

            if ($nextFlow) {
                $userIds = explode(',', $nextFlow->value);
                $targetId = trim($userIds[0]);
                $nextUser = User::find($targetId);

                if ($nextUser) {
                    $nextUserId = $nextUser->id;
                    $nextUserName = $nextUser->name;
                    $nextAction = $nextFlow->action; // Contoh: 'APPROVAL' atau 'VERIFICATION'
                }
            }

            $ca->update([
                'status'         => 'ACTION_REQUIRED',
                'last_user_id'   => $user->id,
                'last_user_name' => $user->name,
                'last_action'    => 'INPUT_EVIDENCE',
                'updated_by'     => $user->name,
                'next_user_id'   => $nextUserId,
                'next_user_name' => $nextUserName,
                'next_action'    => $nextAction,
                // Kita biarkan next_user tetap orang yang sama atau tentukan approver berikutnya di sini
            ]);

            if ($request->has('remark')) {
                foreach ($request->remark as $key => $remarkValue) {
                    $fileName = null;
                    $filePath = null;
                    $fileExtension = null;
                    if ($request->hasFile("file_path.$key")) {
                        $file = $request->file("file_path.$key");
                        $fileExtension = $file->getClientOriginalExtension();
                        $fileName = time() . '_' . uniqid() . '.' . $fileExtension;
                        $filePath = $file->storeAs('evidences', $fileName, 'public');
                    }
                    CorrectiveActionEvidence::create([
                        'action_id'   => $ca->id,
                        'remark'      => $remarkValue,
                        'file_type'   => $fileExtension,
                        'file_path'   => $filePath,
                        'uploaded_by' => $user->name
                    ]);
                }
            }

            // Catat Log untuk Evidence
            CorrectiveActionLog::create([
                'action_id' => $ca->id,
                'user_id'   => $user->id,
                'user_name' => $user->name,
                'status'    => 'ACTION_REQUIRED',
                'remarks'   => 'Evidence Uploaded',
                'event'     => 'INPUT_EVIDENCE',
            ]);

            return redirect()->route('transaction-correctiveAction.index')
                ->with('success', 'Evidence uploaded successfully.');
        }

        // 2. SKENARIO: UPDATE DATA BIASA
        $respon = User::find($request->responsible_person_id);

        $ca->update([
            'source'                  => $request->source,
            'risk_issue_date'         => $request->risk_issuer_date,
            'risk_description'        => $request->risk_description,
            'location'                => $request->location,
            'department_id'           => $request->department_id,
            'responsible_person_id'   => $request->responsible_person_id,
            'responsible_person_name' => $respon->name ?? $ca->responsible_person_name,
            'corrective_action'       => $request->corrective_action,
            'due_date'                => $request->due_date,
            'status'                  => $request->status,
            'next_user_id'            => $request->responsible_person_id,
            'next_user_name'          => $respon->name ?? $ca->responsible_person_name,
            'last_user_id'            => $user->id,
            'last_user_name'          => $user->name,
            'last_action'             => 'UPDATED',
            'remarks'                 => $request->remarks ?? '-',
            'updated_by'              => $user->name,
        ]);

        CorrectiveActionLog::create([
            'action_id' => $ca->id,
            'user_id'   => $user->id,
            'user_name' => $user->name,
            'status'    => $ca->status,
            'remarks'   => $request->remarks ?? 'Record updated',
            'event'     => 'UPDATED',
        ]);

        return redirect()->route('transaction-correctiveAction.index')
            ->with('success', 'Corrective Action updated successfully!');
    }


    public function destroy(string $id)
    {
        $ca = CorrectiveAction::findOrFail($id);
        $evidences = CorrectiveActionEvidence::where('action_id', $id)->get();
        foreach ($evidences as $evidence) {
            if ($evidence->file_path && Storage::disk('public')->exists($evidence->file_path)) {
                Storage::disk('public')->delete($evidence->file_path);
            }
        }

        CorrectiveActionLog::where('action_id', $id)->delete();
        CorrectiveActionEvidence::where('action_id', $id)->delete();
        $ca->delete();
        return redirect()->route('transaction-correctiveAction.index')
            ->with('success', 'Data berhasil dihapus!');
    }

    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $oca = CorrectiveAction::find($request->id);
            $oca->last_action       = $request->action;
            $oca->last_user_id      = $user->id;
            $oca->last_user_name    = $user->name;
            $delegator_uid          = $oca->next_user_id;
            $next_user              = null;
            $next_status            = 'REJECTED';
            $next_approval_level    = 0;
            if ($request->action == 'CHANGE_ASSIGNEE') {
                $oca->department_id             = $request->department_id;
                $department                     = Department::find($request->department_id);
                $oca->department_name           = $department->name;
                $oca->responsible_person_id     = $request->responsible_person_id;
                $responsible_person             = User::find($request->responsible_person_id);
                $oca->responsible_person_name   = $responsible_person->name;
                $next_user                      = User::find($oca->responsible_person_id);
                $next_user->level               = 1;
                $next_user->type                = 'ASSIGNEE';
                $next_user->action              = 'INPUT_EVIDENCE';
                $next_user->value               = $oca->responsible_person_id;
                $next_status                    = 'ACTION_REQUIRED';
                $next_approval_level            = 1;

                if ($oca->source == 'HZ') {
                    $hazard                             = Hazard::find($oca->source_id);
                    $hazard->recipient_id               = $oca->responsible_person_id;
                    $hazard->recipient_name             = $oca->responsible_person_name;
                    $hazard->recipient_department_id    = $oca->department_id;
                    $hazard->recipient_department_name  = $oca->department_name;
                    $hazard->assignee_id                = $oca->responsible_person_id;
                    $hazard->assignee_name              = $oca->responsible_person_name;
                    $hazard->assignee_department_id     = $oca->department_id;
                    $hazard->assignee_department_name   = $oca->department_name;
                    $hazard->last_action                = 'CHANGE_ASSIGNEE';
                    $hazard->last_user_id               = $user->id;
                    $hazard->last_user_name             = $user->name;
                    $hazard->save();

                    $hazardlog                = new HazardLog;
                    $hazardlog->hazard_id     = $hazard->id;
                    $hazardlog->user_id       = $user->id;
                    $hazardlog->user_name     = $user->name;
                    $hazardlog->status        = $hazard->status;
                    $hazardlog->remarks       = 'Changed Assignee by Module Corrective Action';
                    $hazardlog->event         = $hazard->last_action;
                    $hazardlog->delegator_uid = null;
                    $hazardlog->save();
                }

                if ($oca->source == 'WC') {
                    $wpc                = WorkplaceControlAction::find($oca->source_action_id);
                    $wpc->assignee_id   = $oca->responsible_person_id;
                    $wpc->assignee_name = $oca->responsible_person_name;
                    $wpc->save();
                }
            } else if ($request->action == 'INPUT_EVIDENCE' || $request->action == 'APPROVE') {
                if ($request->action == 'INPUT_EVIDENCE') {
                    $oca->action_taken = $request->remarks;
                }
                $next_user = $this->get_next_approver($oca->responsible_person_id, $oca->approval_level);
                if ($next_user == null) {
                    $next_status            = 'COMPLETED';
                    if ($oca->source == 'HZ') {
                        $hazard                         = Hazard::find($oca->source_id);
                        $hazard->completed_date         = date('Y-m-d');
                        $hazard->status                 = 'COMPLETED';
                        $hazard->last_action            = 'UPDATED';
                        $hazard->last_user_id           = $user->id;
                        $hazard->last_user_name         = $user->name;
                        $hazard->next_action            = null;
                        $hazard->save();

                        $hazardlog                = new HazardLog;
                        $hazardlog->hazard_id     = $hazard->id;
                        $hazardlog->user_id       = $user->id;
                        $hazardlog->user_name     = $user->name;
                        $hazardlog->status        = $hazard->status;
                        $hazardlog->remarks       = 'Updated by Module Corrective Action';
                        $hazardlog->event         = $hazard->last_action;
                        $hazardlog->delegator_uid = null;
                        $hazardlog->save();
                    }

                    if ($oca->source == 'WC') {
                        $wpc            = WorkplaceControlAction::find($oca->source_action_id);
                        $wpc->status    = 'Closed';
                        $wpc->save();
                    }
                } else {
                    $next_status            = 'APPROVAL_REQUIRED';
                    $next_approval_level    = $next_user->level;
                }
            } else if ($request->action == 'REJECT') {
                $next_user              = User::find($oca->responsible_person_id);
                $next_user->level       = 1;
                $next_user->type        = 'ASSIGNEE';
                $next_user->action      = 'INPUT_EVIDENCE';
                $next_user->value       = $oca->responsible_person_id;
                $next_approval_level    = 1;
            }
            $oca->status             = $next_status;
            $oca->approval_level     = $next_approval_level;
            $oca->next_action        = $next_user == null ? null : $next_user->action;
            $oca->next_user_id       = $next_user == null ? null : $next_user->id;
            $oca->next_user_name     = $next_user == null ? null : $next_user->name;
            $oca->remarks            = $request->remarks;
            $oca->save();

            $log                = new CorrectiveActionLog;
            $log->action_id     = $oca->id;
            $log->user_id       = $user->id;
            $log->user_name     = $user->name;
            $log->status        = $oca->status;
            $log->remarks       = $request->remarks;
            $log->event         = $oca->last_action;
            $log->delegator_uid = $delegator_uid;
            $log->save();

            $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'corrective_action';
            foreach (range(1, 4) as $i) {
                if ($request->hasFile("file_{$i}")) {
                    $file = $request->file("file_{$i}");
                    $fileName    = 'OHS_CA_' . $oca->id . '_' . $log->id . '_EVIDENCE_' . $i . '.' . $file->getClientOriginalExtension();
                    if (!File::isDirectory($basePath))
                        File::makeDirectory($basePath, 0777, true, true);
                    $file->move($basePath, $fileName);

                    $ocae               = new CorrectiveActionEvidence;
                    $ocae->action_id    = $oca->id;
                    $ocae->file_type    = $file->getClientMimeType();
                    $ocae->file_path    = $fileName;
                    $ocae->remarks      = 'Evidence ' . $i;
                    $ocae->save();
                }
            }

            CorrectiveActionNextApprover::where('action_id', $oca->id)->delete();
            if (!empty($oca->next_action)) {
                $next_user_ids = explode(',', $next_user->value);
                foreach ($next_user_ids as $next_user_id) {
                    $nextuser           = User::find($next_user_id);
                    $ocana              = new CorrectiveActionNextApprover;
                    $ocana->action_id   = $oca->id;
                    $ocana->user_id     = $nextuser->id;
                    $ocana->user_name   = $nextuser->name;
                    $ocana->save();

                    // send email to next user
                    Mail::send('mail.corrective_action_approval', ['data' => $oca, 'nextuser' => $nextuser], function ($message) use ($oca, $nextuser) {
                        $message->to($nextuser->email);
                        $message->subject('CAR_' . $oca->source . '_Approval Request');
                    });
                }
            }

            // send email to requestor
            $risk_issuer = User::find($oca->risk_issuer_id);
            Mail::send('mail.corrective_action_status_notification', ['data' => $oca, 'risk_issuer' => $risk_issuer], function ($message) use ($oca, $risk_issuer) {
                $message->to($risk_issuer->email);
                $message->subject('CAR_' . $oca->source . '_Status changed');
            });

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
        return redirect()
            ->route('transaction-correctiveAction.index', ['action' => 'APPROVAL'])
            ->with('success', 'Updated successfully!');
    }
}
