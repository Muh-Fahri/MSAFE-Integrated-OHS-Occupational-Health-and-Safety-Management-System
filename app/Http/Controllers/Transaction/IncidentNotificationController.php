<?php

namespace App\Http\Controllers\Transaction;

use App\Models\Flow;
use App\Models\User;
use App\Models\Company;
use App\Models\Incident;
use App\Models\Location;
use App\Models\Delegation;
use App\Models\Department;
use App\Models\IncidentLog;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Models\IncidentNextApprover;
use App\Models\OhsMaster;
use App\Models\GeneralTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IncidentNotificationExport;

class IncidentNotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $action = strtoupper($request->query('action', ''));
        $menuId = PermissionHelper::getCurrentMenuId();

        $query = Incident::selectRaw("incidents.*, (SELECT GROUP_CONCAT(user_name) FROM incident_next_approvers na WHERE na.incident_id = incidents.id) as next_user");

        // logic sorting
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
            $query->whereIn('reporter_id', $list_user_id);
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
            $res = IncidentNextApprover::whereIn('user_id', $user_ids)->get(['incident_id']);
            $ids = [];
            foreach ($res as $v) {
                $ids[] = $v->incident_id;
            }
            $query->whereIn('status', ['APPROVAL_REQUIRED'])->whereIn('id', $ids);
        } else if ($action == 'APPROVAL_HISTORY') {
            $query->whereRaw("(id IN (SELECT DISTINCT incident_id FROM incident_logs WHERE (user_id = " . $user->id . " OR delegator_uid = " . $user->id . ") AND event NOT IN ('CREATE', 'UPDATE')))");
        } else {
            $query->where('reporter_id', $user->id);
        }

        if ($request->filled('report_date')) {
            $report_date   = explode(' to ', $request->report_date);
            $report_date_1 = date('Y-m-d', strtotime($report_date[0]));
            $report_date_2 = date('Y-m-d', strtotime($report_date[1]));
            $query->whereBetween('report_date', [$report_date_1, $report_date_2]);
        }

        if ($request->filled('no')) {
            $query->whereRaw("no LIKE '%" . $request->no . "%'");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('reporter_name')) {
            $query->where('reporter_name', 'like', '%' . $request->reporter_name . '%');
        }

        if ($request->filled('department_name')) {
            $query->where('department_name', 'like', '%' . $request->department_name . '%');
        }

        if ($request->filled('company_name')) {
            $query->where('company_name', 'like', '%' . $request->company_name . '%');
        }

        $data = $query->orderBy($sortBy, $sortOrder)->paginate(10)->withQueryString();
        return view('transaction.incident_notification.index', compact('data', 'menuId'));
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        return Excel::download(new IncidentNotificationExport($request, $user), 'incident_notification.xlsx');
    }

    public function create()
    {
        $act_sev = ['1A', '2A', '3A', '4A', '5A'];
        $pot_sev = ['1P', '2P', '3P', '4P', '5P'];
        $loc_type = ['CoW', 'Off Site'];
        $ev_type  = OhsMaster::where('type', 'INCIDENT_EVENT_TYPE')->pluck('name')->toArray();
        $company = Company::all();
        $department = Department::all();
        $location = Location::all();
        return view('transaction.incident_notification.create', compact([
            'act_sev',
            'pot_sev',
            'company',
            'department',
            'loc_type',
            'location',
            'ev_type'
        ]));
    }

    function getNextNo()
    {
        $prefix = 'IN-' . date('m') . '-' . date('Y') . '-';
        $incident = Incident::selectRaw("SUBSTRING(no,12,3) AS no2")
            ->whereRaw("SUBSTRING(no,7,4) = YEAR(CURRENT_DATE)")
            ->orderBy('no2', 'DESC')
            ->first();
        if ($incident == null) {
            return $prefix . '001';
        } else {
            $no = $incident->no2;
            $no = intval($no);
            $no += 1;
            return sprintf($prefix . "%03d", $no);
        }
    }

    function get_next_approver($reporter_id, $level)
    {
        $next_user = null;
        $flow = Flow::where('process', 'INCIDENT_NOTIFICATION')
            ->where('level', '>', $level)
            ->orderBy('level', 'ASC')->first();
        if ($flow != null) {
            if ($flow->type == 'HEAD_OF_DEPARTMENT') {
                $requestor          = User::find($reporter_id);
                $next_user          = User::find($requestor->hod);
                $next_user->value   = $requestor->hod;
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
        $request->validate([
            'event_title'    => 'required|max:255',
            'photo_1_path'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'photo_2_path'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'photo_3_path'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'photo_4_path'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'max'   => 'Ukuran file :attribute tidak boleh lebih dari 2MB.',
            'mimes' => 'Format file :attribute harus berupa jpg, jpeg, png, atau pdf.'
        ]);
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $incident = new Incident;
            $incident->no                               = $this->getNextNo();
            $incident->event_title                      = $request->event_title;
            $incident->event_datetime                   = $request->event_datetime;
            $incident->company_id                       = $request->company_id;
            $company                                    = Company::find($incident->company_id);
            $incident->company_name                     = $company->name;
            $incident->department_id                    = $request->department_id;
            $department                                 = Department::find($incident->department_id);
            $incident->department_name                  = $department->name;
            $incident->event_type                       = $request->event_type;
            $incident->report_date                      = $request->report_date;
            $incident->location_type                    = $request->location_type;
            $incident->location                         = $request->location_type == 'CoW' ? $request->location : $request->location2;
            $incident->severity_level_actual            = $request->severity_level_actual;
            $incident->severity_level_actual_remarks    = $request->severity_level_actual_remarks;
            $incident->severity_level_potential         = $request->severity_level_potential;
            $incident->severity_level_potential_remarks = $request->severity_level_potential_remarks;
            $incident->work_related                     = $request->work_related;
            $incident->incident_description             = $request->incident_description;
            $incident->immediate_actions                = $request->immediate_actions;
            $incident->reporter_id                      = $user->id;
            $incident->reporter_name                    = $user->name;
            $incident->remarks                          = $request->remarks;
            $incident->last_action                      = $request->action;
            $incident->last_user_id                     = $user->id;
            $incident->last_user_name                   = $user->name;
            $incident->next_action                      = 'APPROVAL';
            $incident->last_approval_level              = 0;
            $next_user                                  = $this->get_next_approver($incident->reporter_id, 0);
            $incident->status                           = 'APPROVAL_REQUIRED';
            $incident->approval_level                   = 1;
            $incident->next_user_id                     = $next_user == null ? null : $next_user->id;
            $incident->next_user_name                   = $next_user == null ? null : $next_user->name;
            $incident->save();
            $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'incident';
            foreach (range(1, 4) as $i) {
                if ($request->hasFile("photo_{$i}_path")) {
                    $file = $request->file("photo_{$i}_path");
                    $filename = 'OHS_IR_' . $incident->id . '_PHOTO_' . $i . '.' . $file->getClientOriginalExtension();
                    if (!File::isDirectory($basePath))
                        File::makeDirectory($basePath, 0777, true, true);
                    $file->move($basePath, $filename);
                    $incident->{"photo_{$i}_path"} = $filename;
                    $incident->{"photo_{$i}_type"} = $file->getClientMimeType();
                }
            }
            $incident->save();

            $incidentlog = new IncidentLog;
            $incidentlog->incident_id   = $incident->id;
            $incidentlog->user_id       = $user->id;
            $incidentlog->status        = $incident->status;
            $incidentlog->remarks       = '';
            $incidentlog->event         = $incident->last_action;
            $incidentlog->save();

            IncidentNextApprover::where('incident_id', $incident->id)->delete();
            $next_user_ids = explode(',', $next_user->value);
            foreach ($next_user_ids as $next_user_id) {
                $nextuser                           = User::find($next_user_id);
                $incidentnextapprover               = new IncidentNextApprover;
                $incidentnextapprover->incident_id  = $incident->id;
                $incidentnextapprover->user_id      = $nextuser->id;
                $incidentnextapprover->user_name    = $nextuser->name;
                $incidentnextapprover->save();

                // send email to next user
                Mail::send('mail.incident_approval', ['data' => $incident, 'nextuser' => $nextuser], function ($message) use ($incident, $nextuser) {
                    $message->to($nextuser->email);
                    $message->subject('Incident Notification_' . $incident->severity_level_actual . '_Approval Request');
                });
            }
            DB::commit();
            return redirect()->route('transaction-incidentNotification.index')->with('success', 'Incident report submitted successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function show(string $id)
    {
        $item = Incident::findOrFail($id);
        $company = Company::all();
        $department = Department::all();
        $location = Location::all();
        $loc_type = ['CoW', 'Off Site'];
        $act_sev = ['1A', '2A', '3A', '4A', '5A'];
        $pot_sev = ['1P', '2P', '3P', '4P', '5P'];
        $ev_type  = OhsMaster::where('type', 'INCIDENT_EVENT_TYPE')->pluck('name')->toArray();
        return view('transaction.incident_notification.show', compact(
            'item',
            'company',
            'department',
            'location',
            'loc_type',
            'ev_type',
            'act_sev',
            'pot_sev'
        ));
    }

    public function edit(string $id)
    {
        $item = Incident::findOrFail($id);
        $company = Company::all();
        $department = Department::all();
        $location = Location::all();
        $loc_type = ['CoW', 'Off Site'];
        $act_sev = ['1A', '2A', '3A', '4A', '5A'];
        $pot_sev = ['1P', '2P', '3P', '4P', '5P'];
        $ev_type  = OhsMaster::where('type', 'INCIDENT_EVENT_TYPE')->pluck('name')->toArray();
        return view('transaction.incident_notification.edit', compact(
            'item',
            'company',
            'department',
            'location',
            'loc_type',
            'ev_type',
            'act_sev',
            'pot_sev'
        ));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'event_title'    => 'required|max:255',
            'photo_1_path'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'photo_2_path'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'photo_3_path'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'photo_4_path'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'max'   => 'Ukuran file :attribute tidak boleh lebih dari 2MB.',
            'mimes' => 'Format file :attribute harus berupa jpg, jpeg, png, atau pdf.'
        ]);
        $incident = Incident::findOrFail($id);
        $user = Auth::user();
        $company = Company::find($request->company_id);
        $department = Department::find($request->department_id);
        $updateData = [
            'event_title'                       => $request->event_title,
            'event_datetime'                    => $request->event_datetime,
            'report_date'                       => $request->report_date,
            'company_id'                        => $request->company_id,
            'company_name'                      => $company ? $company->name : null,
            'department_id'                     => $request->department_id,
            'department_name'                   => $department ? $department->name : null,
            'location_type'                     => $request->location_type,
            'location'                          => $request->location,
            'event_type'                        => $request->event_type,
            'severity_level_actual'             => $request->severity_level_actual,
            'severity_level_actual_remarks'     => $request->severity_level_actual_remarks,
            'severity_level_potential'          => $request->severity_level_potential,
            'severity_level_potential_remarks'  => $request->severity_level_potential_remarks,
            'work_related'                      => $request->work_related,
            'incident_description'              => $request->incident_description,
            'immediate_actions'                 => $request->immediate_actions,
            'remarks'                           => $request->remarks,
        ];
        $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'incident';
        foreach (range(1, 4) as $i) {
            if ($request->hasFile("photo_{$i}_path")) {
                $file = $request->file("photo_{$i}_path");
                $filename = 'OHS_IR_' . $incident->id . '_PHOTO_' . $i . '.' . $file->getClientOriginalExtension();
                if (!File::isDirectory($basePath))
                    File::makeDirectory($basePath, 0777, true, true);
                $file->move($basePath, $filename);
                $incident->{"photo_{$i}_path"} = $filename;
                $incident->{"photo_{$i}_type"} = $file->getClientMimeType();
            }
        }
        $incident->update($updateData);
        IncidentLog::create([
            'incident_id' => $incident->id,
            'user_id'     => $user->id,
            'user_name'   => $user->name,
            'status'      => 'UPDATED',
            'event'       => 'ADMIN_UPDATED',
            'remarks'     => 'Data updated by ' . $user->name,
        ]);

        IncidentNextApprover::create([
            'incident_id'   => $incident->id,
            'user_id'       => $incident->next_user_id,
            'user_name'     => $incident->next_user_name,
        ]);
        return redirect()->route('transaction-incidentNotification.index')->with('success', 'Incident report updated successfully!');
    }

    public function destroy($id)
    {
        $incident = Incident::findOrFail($id);
        for ($i = 1; $i <= 4; $i++) {
            $pathField = "photo_{$i}_path";
            if ($incident->$pathField) {
                Storage::disk('public')->delete($incident->$pathField);
            }
        }
        IncidentLog::where('incident_id', $id)->delete();
        IncidentNextApprover::where('incident_id', $id)->delete();
        $incident->delete();

        return redirect()->route('transaction-incidentNotification.index');
    }

    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $oi = Incident::findOrFail($id);
            $oi->last_action            = $request->action;
            $oi->last_user_id           = $user->id;
            $oi->last_user_name         = $user->name;
            $oi->last_approval_level    = $oi->approval_level;
            $delegator_uid              = $oi->next_user_id;
            $next_user                  = null;
            $next_status                = 'REJECTED';
            $next_approval_level        = 0;
            if ($request->action == 'APPROVE') {
                $next_user = $this->get_next_approver($oi->reporter_id, $oi->approval_level);
                if ($next_user == null) {
                    if ($oi->work_related == 'Yes') {
                        $next_status = 'INVESTIGATION_REQUIRED';
                        $next_user = User::find($oi->reporter_id);
                        $next_user->action = 'INVESTIGATION';
                        $next_user->value = $oi->reporter_id;
                    } else {
                        $next_status = 'COMPLETED';
                    }
                } else {
                    if ($next_user->action == 'PRINTING') {
                        $next_status = 'WAITING_TO_PRINT';
                    } else {
                        $next_status = 'APPROVAL_REQUIRED';
                    }
                    $next_approval_level = $next_user->level;
                }
            }
            $oi->status             = $next_status;
            $oi->approval_level     = $next_approval_level;
            $oi->next_action        = $next_user == null ? null : $next_user->action;
            $oi->next_user_id       = $next_user == null ? null : $next_user->id;
            $oi->next_user_name     = $next_user == null ? null : $next_user->name;
            $oi->remarks            = $request->remarks;
            $oi->save();

            $log                = new IncidentLog;
            $log->incident_id   = $oi->id;
            $log->user_id       = $user->id;
            $log->user_name     = $user->name;
            $log->status        = $oi->status;
            $log->remarks       = $request->remarks;
            $log->event         = $oi->last_action;
            $log->delegator_uid = $delegator_uid;
            $log->save();

            IncidentNextApprover::where('incident_id', $oi->id)->delete();
            if (!empty($oi->next_action)) {
                $next_user_ids = explode(',', $next_user->value);
                foreach ($next_user_ids as $next_user_id) {
                    $nextuser           = User::find($next_user_id);
                    $frna               = new IncidentNextApprover;
                    $frna->incident_id  = $oi->id;
                    $frna->user_id      = $nextuser->id;
                    $frna->user_name    = $nextuser->name;
                    $frna->save();

                    // send email to next user
                    Mail::send('mail.incident_approval', ['data' => $oi, 'nextuser' => $nextuser], function ($message) use ($oi, $nextuser) {
                        $message->to($nextuser->email);
                        $message->subject('Incident Notification_' . $oi->severity_level_actual . '_Approval Request');
                    });
                }
            }

            if ($oi->status == 'INVESTIGATION_REQUIRED' || $oi->status == 'INVESTIGATION_REQUIRED') {
                $list_email = GeneralTable::where('type', 'INCIDENT_NOTIFICATION_EMAIL')->get(['code']);
                foreach ($list_email as $v) {
                    Mail::send('mail.incident_notification', ['data' => $oi], function ($message) use ($oi, $v) {
                        $message->to('notification@ptmasmindo.co.id');
                        $message->bcc($v->code);
                        $message->subject('Incident Notification_' . $oi->severity_level_actual . '_' . $oi->event_type);
                    });
                }

                // send email to requestor
                $reporter = User::find($oi->reporter_id);
                Mail::send('mail.incident_investigation_required', ['data' => $oi, 'reporter' => $reporter], function ($message) use ($oi, $reporter) {
                    $message->to($reporter->email);
                    $message->subject('Incident Notification_' . $oi->severity_level_actual . '_' . $oi->event_type);
                });
            } else {
                // send email to requestor
                $reporter = User::find($oi->reporter_id);
                Mail::send('mail.incident_status_notification', ['data' => $oi, 'reporter' => $reporter], function ($message) use ($oi, $reporter) {
                    $message->to($reporter->email);
                    $message->subject('Incident Notification_' . $oi->severity_level_actual . '_Status changed');
                });
            }
            DB::commit();
            return redirect()->route('transaction-incidentNotification.index', ['action' => 'APPROVAL'])->with('success', 'Approved successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
