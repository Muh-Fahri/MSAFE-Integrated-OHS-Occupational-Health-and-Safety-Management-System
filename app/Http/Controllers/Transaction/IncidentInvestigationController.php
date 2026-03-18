<?php

namespace App\Http\Controllers\Transaction;

use App\Models\Flow;
use App\Models\User;
use App\Models\Incident;
use App\Models\CorrectiveActionLog;
use App\Models\GeneralTable;
use App\Models\Delegation;
use App\Models\IncidentLog;
use Illuminate\Http\Request;
use App\Models\IncidentAction;
use App\Models\IncidentTeam;
use App\Models\CorrectiveAction;
use App\Helpers\PermissionHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\IncidentNextApprover;
use App\Models\CorrectiveActionNextApprover;
use App\Models\OhsMaster;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IncidentInvestigationExport;

class IncidentInvestigationController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $action = strtoupper($request->query('action', ''));
        $menuId = PermissionHelper::getCurrentMenuId();

            // logic sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');

        $query = Incident::selectRaw("incidents.*, (SELECT GROUP_CONCAT(user_name) FROM incident_next_approvers na WHERE na.incident_id = incidents.id) as next_user");

        if ($action == 'MONITORING') {
            $query->whereIn('status', ['INVESTIGATION_REQUIRED', 'INVESTIGATION_REJECTED', 'INVESTIGATION_APPROVAL_REQUIRED', 'COMPLETED']);
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
            $query->whereIn('status', ['INVESTIGATION_APPROVAL_REQUIRED'])->whereIn('id', $ids);
        } else if ($action == 'APPROVAL_HISTORY') {
            $query->whereRaw("(id IN (SELECT DISTINCT incident_id FROM incident_logs WHERE (user_id = " . $user->id . " OR delegator_uid = " . $user->id . ") AND event NOT IN ('CREATE', 'UPDATE')))");
        } else {
            $query->whereIn('status', ['INVESTIGATION_REQUIRED', 'INVESTIGATION_REJECTED', 'INVESTIGATION_APPROVAL_REQUIRED', 'COMPLETED'])->where('reporter_id', $user->id);
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
        $flow = Flow::all();
        return view('transaction.incident_investigation.index', compact(
            'data',
            'flow',
            'menuId'
        ));
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        return Excel::download(new IncidentInvestigationExport($request, $user), 'incident_investigation.xlsx');
    }

    public function show(string $id)
    {
        $user = Auth::user();
        $incident = Incident::with([
            'logs',
            'teams:id,incident_id,name,role',
            'actions:id,incident_id,name,assignee_id,assignee_name,due_date'
        ])->findOrFail($id);
        $next_user_ids = IncidentNextApprover::where('incident_id', $id)
            ->pluck('user_id')
            ->toArray();
        $delegated = Delegation::where('type', 'ALL')
            ->whereIn('delegator', $next_user_ids)
            ->where('delegatee', $user->id)
            ->whereDate('begin_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->exists();
        return view('transaction.incident_investigation.show', compact('incident', 'user', 'delegated', 'next_user_ids'));
    }

    public function edit(string $id)
    {
        $user = Auth::user();
        $incident = Incident::with([
            'logs',
            'teams:id,incident_id,name,role',
            'actions:id,incident_id,name,assignee_id,assignee_name,due_date'
        ])->findOrFail($id);
        $delegated = Delegation::where('type', 'ALL')
            ->where('delegator', $incident->next_user_id)
            ->where('delegatee', $user->id)
            ->whereDate('begin_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->exists();
        $next_user_ids = IncidentNextApprover::where('incident_id', $id)
            ->pluck('user_id')
            ->toArray();
        $types = [
            'IMPACT_INJURY_CLASSIFICATION',
            'IMPACT_INJURY_BODY',
            'IMPACT_ENVIRONMENTAL_CATEGORY',
            'IMPACT_ENVIRONMENTAL_PRODUCT',
            'IMPACT_PROPERTY_DAMAGE_ASSET',
        ];
        $masters = OhsMaster::whereIn('type', $types)
            ->get(['type', 'name'])
            ->groupBy('type');
        $lists = [];
        foreach ($types as $type) {
            $lists[$type] = ['-' => '-'] + ($masters[$type] ?? collect())->pluck('name', 'name')->toArray();
        }
        $list_user = User::where('status', 'active')->orderBy('name')->get(['id', 'name']);
        return view('transaction.incident_investigation.edit', [
            'incident' => $incident,
            'user' => $user,
            'delegated' => $delegated,
            'next_user_ids' => $next_user_ids,
            'lists' => $lists,
            'list_user' => $list_user,
        ]);
    }


    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $incident = Incident::findOrFail($id);
            $incident->impact_injury_classification             = $request->impact_injury_classification;
            $incident->impact_injury_description                = $request->impact_injury_description;
            $incident->impact_injury_treatment_details          = $request->impact_injury_treatment_details;
            $incident->impact_injury_body_injury                = $request->impact_injury_body_injury;
            $incident->impact_environmental_category            = $request->impact_environmental_category;
            $incident->impact_environmental_product_name        = $request->impact_environmental_product_name;
            $incident->impact_environmental_quantity_uom        = $request->impact_environmental_quantity_uom;
            $incident->impact_property_damage_plant_type        = $request->impact_property_damage_plant_type;
            $incident->impact_property_damage_cost              = $request->impact_property_damage_cost;
            $incident->impact_property_damage_asset_involved    = $request->impact_property_damage_asset_involved;
            $incident->impact_property_damage_info              = $request->impact_property_damage_info;
            $incident->impact_property_damage_asset_number      = $request->impact_property_damage_asset_number;
            $incident->fact_finding_description_people          = $request->fact_finding_description_people;
            $incident->fact_finding_description_environment     = $request->fact_finding_description_environment;
            $incident->fact_finding_description_equipment       = $request->fact_finding_description_equipment;
            $incident->fact_finding_description_procedure       = $request->fact_finding_description_procedure;
            $incident->fact_finding_description_equipment       = $request->fact_finding_description_equipment;
            $incident->fact_finding_causal_factor               = $request->fact_finding_causal_factor;
            $incident->fact_finding_root_cause                  = $request->fact_finding_root_cause;

            $approval_level                                     = !empty($incident->last_approval_level) ? $incident->last_approval_level - 1 : 0;
            $next_user                                          = $this->get_next_approver($incident->reporter_id, $approval_level);
            $incident->status                                   = 'INVESTIGATION_APPROVAL_REQUIRED';
            $incident->approval_level                           = $next_user == null ? null : $next_user->level;
            $incident->next_user_id                             = $next_user == null ? null : $next_user->id;
            $incident->next_user_name                           = $next_user == null ? null : $next_user->name;
            $incident->next_action                              = $next_user == null ? null : $next_user->action;

            $incident->last_action                              = 'SUBMIT_INVESTIGATION';
            $incident->last_user_id                             = $user->id;
            $incident->last_user_name                           = $user->name;
            $incident->last_approval_level                      = 0;

            $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'incident';
            foreach (range(1, 4) as $i) {
                if ($request->hasFile("fact_finding_photo_{$i}_path")) {
                    $file = $request->file("fact_finding_photo_{$i}_path");
                    $fileName = 'OHS_IR_' . $incident->id . '_FACT_FINDING_PHOTO_' . $i . '.' . $file->getClientOriginalExtension();
                    if (!File::isDirectory($basePath))
                        File::makeDirectory($basePath, 0777, true, true);
                    $file->move($basePath, $fileName);
                    $data["fact_finding_photo_{$i}_path"] = $fileName;
                    $data["fact_finding_photo_{$i}_type"] = $file->getClientMimeType();
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

                // EMAIL
                Mail::send('mail.incident_investigation_approval', ['data' => $incident, 'nextuser' => $nextuser], function ($message) use ($incident, $nextuser) {
                    $message->to($nextuser->email);
                    $message->subject('Incident Investigation_' . $incident->severity_level_actual . '_Approval Request');
                });
            }

            if ($request->has('team_name')) {
                IncidentTeam::where('incident_id', $incident->id)->delete();
                $teamNames = $request->input('team_name');
                $teamRoles = $request->input('team_role');
                foreach ($teamNames as $key => $val) {
                    if (!empty($val)) {
                        IncidentTeam::create([
                            'incident_id' => $incident->id,
                            'name' => $val,
                            'role' => $teamRoles[$key] ?? null,
                        ]);
                    }
                }
            }

            if ($request->has('action_name')) {
                IncidentAction::where('incident_id', $incident->id)->delete();
                $actionNames = $request->input('action_name');
                $assigneeIds = $request->input('assignee_id');
                $dueDates    = $request->input('due_date');
                foreach ($actionNames as $key => $val) {
                    if (!empty($val)) {
                        $targetUserId = $assigneeIds[$key] ?? null;
                        $targetUserName = null;

                        if ($targetUserId) {
                            $targetUser = \App\Models\User::find($targetUserId);
                            $targetUserName = $targetUser ? $targetUser->name : null;
                        }
                        IncidentAction::create([
                            'incident_id'   => $incident->id,
                            'name'          => $val,
                            'assignee_id'   => $targetUserId,
                            'assignee_name' => $targetUserName,
                            'due_date'      => $dueDates[$key] ?? null,
                            'status'        => 'OPEN',
                        ]);
                    }
                }
            }
            DB::commit();
            return redirect()->route('transaction-incidentInvestigation.index')->with('success', 'Investigation updated successfully');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $inci = Incident::findOrFail($id);
        IncidentAction::where('incident_id', $inci->id)->delete();
        for ($i = 1; $i <= 4; $i++) {
            $photoField = "fact_finding_photo_{$i}_path";
            if ($inci->$photoField && Storage::disk('public')->exists($inci->$photoField)) {
                Storage::disk('public')->delete($inci->$photoField);
            }
        }
        $inci->delete();
        return redirect()->route('transaction-incidentInvestigation.index');
    }

    function get_next_approver($reporter_id, $level)
    {
        $next_user = null;
        $flow = Flow::where('process', 'INCIDENT_INVESTIGATION')->where('level', '>', $level)->orderBy('level', 'ASC')->first();
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

    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $incident = Incident::find($id);
            $incident->last_action          = $request->action;
            $incident->last_user_id         = $user->id;
            $incident->last_user_name       = $user->name;
            $incident->last_approval_level  = $incident->approval_level;
            $delegator_uid                  = $incident->next_user_id;
            $next_user                      = null;
            $next_status                    = 'INVESTIGATION_REJECTED';
            $next_approval_level            = 0;
            if ($request->action == 'APPROVE') {
                $next_user = $this->get_next_approver($incident->reporter_id, $incident->approval_level);
                if ($next_user == null) {
                    $next_status = 'COMPLETED';
                } else {
                    $next_status = 'INVESTIGATION_APPROVAL_REQUIRED';
                    $next_approval_level = $next_user->level;
                }
            }
            $incident->status             = $next_status;
            $incident->approval_level     = $next_approval_level;
            $incident->next_action        = $next_user == null ? null : $next_user->action;
            $incident->next_user_id       = $next_user == null ? null : $next_user->id;
            $incident->next_user_name     = $next_user == null ? null : $next_user->name;
            $incident->remarks            = $request->remarks;
            $incident->save();

            $log                = new IncidentLog;
            $log->incident_id   = $incident->id;
            $log->user_id       = $user->id;
            $log->user_name     = $user->name;
            $log->status        = $incident->status;
            $log->remarks       = $request->remarks;
            $log->event         = $incident->last_action;
            $log->delegator_uid = $delegator_uid;
            $log->save();

            IncidentNextApprover::where('incident_id', $incident->id)->delete();
            if (!empty($incident->next_action)) {
                $next_user_ids = explode(',', $next_user->value);
                foreach ($next_user_ids as $next_user_id) {
                    $nextuser           = User::find($next_user_id);
                    $ina               = new IncidentNextApprover;
                    $ina->incident_id  = $incident->id;
                    $ina->user_id      = $nextuser->id;
                    $ina->user_name    = $nextuser->name;
                    $ina->save();
                    // EMAIL
                    Mail::send('mail.incident_investigation_approval', ['data' => $incident, 'nextuser' => $nextuser], function ($message) use ($incident, $nextuser) {
                        $message->to($nextuser->email);
                        $message->subject('Incident Investigation_' . $incident->severity_level_actual . '_Approval Request');
                    });
                }
            }

            if ($incident->status == 'COMPLETED') {
                $list_email = GeneralTable::where('type', 'INCIDENT_INVESTIGATION_EMAIL')->get(['code']);
                foreach ($list_email as $v) {
                    Mail::send('mail.incident_investigation_notification', ['data' => $incident], function ($message) use ($incident, $v) {
                        $message->to($v->code);
                        $message->subject('Incident Investigation Report_' . $incident->severity_level_actual . '_' . $incident->event_type);
                    });
                }
                $list_action = IncidentAction::where('incident_id', $incident->id)->get(['name', 'assignee_id', 'assignee_name', 'due_date']);
                foreach ($list_action as $v) {
                    $oca = new CorrectiveAction;
                    $oca->source                    = 'IN';
                    $oca->source_id                 = $incident->id;
                    $oca->source_no                 = $incident->no;
                    $oca->risk_issuer_id            = $incident->reporter_id;
                    $oca->risk_issuer_name          = $incident->reporter_name;
                    $oca->risk_issue_date           = $incident->report_date;
                    $oca->risk_description          = $incident->event_title;
                    $oca->location                  = $incident->location;
                    $oca->department_id             = $incident->department_id;
                    $oca->department_name           = $incident->department_name;
                    $oca->responsible_person_id     = $v->assignee_id;
                    $oca->responsible_person_name   = $v->assignee_name;
                    $oca->corrective_action         = $v->name;
                    $oca->due_date                  = $v->due_date;
                    $oca->status                    = 'ACTION_REQUIRED';
                    $oca->last_action               = 'CREATE';
                    $oca->next_action               = 'INPUT_EVIDENCE';
                    $oca->next_user_id              = $v->assignee_id;
                    $oca->next_user_name            = $v->assignee_name;
                    $oca->approval_level            = 1;
                    $oca->save();

                    $ocal                = new CorrectiveActionLog;
                    $ocal->action_id     = $oca->id;
                    $ocal->user_id       = $user->id;
                    $ocal->user_name     = $user->name;
                    $ocal->status        = $oca->status;
                    $ocal->remarks       = 'Generated by Module Incident';
                    $ocal->event         = $oca->last_action;
                    $ocal->delegator_uid = null;
                    $ocal->save();

                    $ocana                = new CorrectiveActionNextApprover;
                    $ocana->action_id     = $oca->id;
                    $ocana->user_id       = $oca->next_user_id;
                    $ocana->user_name     = $oca->next_user_name;
                    $ocana->save();

                    $assignee   = User::find($oca->next_user_id);

                    Mail::send('mail.corrective_action_notification', ['data' => $oca, 'assignee' => $assignee], function ($message) use ($oca, $assignee) {
                        $message->to($assignee->email);
                        $message->subject('CAR_' . $oca->source . '_Action required');
                    });
                }
            }

            // send email to requestor
            $reporter = User::find($incident->reporter_id);
            Mail::send('mail.incident_investigation_status_notification', ['data' => $incident, 'reporter' => $reporter], function ($message) use ($incident, $reporter) {
                $message->to($reporter->email);
                $message->subject('Incident Investigation_' . $incident->severity_level_actual . '_Status changed');
            });

            DB::commit();
            return redirect()->route('transaction-incidentInvestigation.index', ['action' => 'APPROVAL'])->with('success', 'Updated status successfully');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
