<?php

namespace App\Http\Controllers\Transaction;

use Storage;
use App\Models\User;
use App\Models\Location;
use App\Models\Department;
use App\Models\CheckingItem;
use App\Models\Company;
use App\Models\OhsMaster;
use Illuminate\Http\Request;
use App\Models\CorrectiveAction;
use App\Models\CorrectiveActionLog;
use App\Models\CorrectiveActionNextApprover;
use App\Models\WorkplaceControl;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\WorkplaceControlAction;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkplaceControlItem;
use App\Models\WorkplaceControlAttachment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WorkplaceControlExport;

class WorkPlaceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = WorkplaceControl::query();
        $action = !empty($request->action) ? strtoupper($request->action) : '';

        // logic sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrderby = $request->get('sort_order', 'desc');

        if ($action == 'MONITORING') {
            //
        } else if ($action == 'DEPARTMENT_MONITORING') {
            $res = User::where('department_id', $user->department_id)->get(['id']);
            $list_user_id = [];
            foreach ($res as $v) {
                $list_user_id[] = $v->id;
            }
            $query->whereIn('requestor_id', $list_user_id);
        } else {
            $query->where('requestor_id', $user->id);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        if ($request->filled('no')) {
            $query->where('no', 'like', '%' . $request->no . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('requestor_name')) {
            $query->where('requestor_name', 'like', '%' . $request->requestor_name . '%');
        }
        $query->orderBy($sortBy, $sortOrderby);
        $data = $query->paginate(10)->withQueryString();
        return view('transaction.workplace_control.index', compact('data'));
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        return Excel::download(new WorkplaceControlExport($request, $user), 'workplace_control.xlsx');
    }

    public function create(Request $request)
    {
        $type_sel = [
            'INSPECTION_VEHICLE',
            'INSPECTION_BUILDING',
            'PLANNED_TASK_OBSERVATION',
            'INSPECTION_ROAD',
            'INSPECTION_DRILLING_AREA',
            'INSPECTION_CONSTRUCTION_AREA',
            'INSPECTION_DUMP_POINT_AREA',
            'INSPECTION_LOADING_POINT_AREA'

        ];
        $type = $request->query('type', $type_sel[0]);
        $role = Role::all();
        $dept = Department::all();
        $location = Location::all();
        $users = User::all();
        $checking_items = CheckingItem::where('group', $type)->get();
        $list_activity_company = [];
        $list_observation_reason = [];
        if ($type == 'PLANNED_TASK_OBSERVATION') {
            $res = Company::where('name', '<>', 'Masmindo Dwi Area')->get(['id', 'name']);
            $list_activity_company['Personil MDA'] = 'Personil MDA';
            foreach ($res as $v) {
                $list_activity_company['Kontraktor - ' . $v->name] = 'Kontraktor - ' . $v->name;
            }

            $res = OhsMaster::where('type', 'PTO_REASON')->get(['id', 'code', 'name']);
            foreach ($res as $v) {
                $list_observation_reason[$v->name] = $v->name;
            }
        }
        return view('transaction.workplace_control.create', compact(
            'dept',
            'location',
            'users',
            'checking_items',
            'type_sel',
            'role',
            'type',
            'list_activity_company',
            'list_observation_reason'
        ));
    }

    function getNextNo()
    {
        $prefix = 'WC-' . date('m') . '-' . date('Y') . '-';
        $workplace_control = WorkplaceControl::selectRaw("SUBSTRING(no,12,3) AS no2")
            ->whereRaw("SUBSTRING(no,7,4) = YEAR(CURRENT_DATE)")
            ->orderBy('no2', 'DESC')
            ->first();
        if ($workplace_control == null) {
            return $prefix . '001';
        } else {
            $workplace_controlno = $workplace_control->no2;
            $workplace_controlno = intval($workplace_controlno);
            $workplace_controlno += 1;
            return sprintf($prefix . "%03d", $workplace_controlno);
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $wpc                            = new WorkplaceControl;
            $wpc->type                      = $request->type;
            $wpc->no                        = $this->getNextNo();
            $wpc->requestor_id              = $user->id;
            $wpc->requestor_name            = $user->name;
            $wpc->requestor_department_id   = $user->department_id;
            $department                     = Department::find($user->department_id);
            $wpc->requestor_department_name = $department->name;
            if ($wpc->type == 'PLANNED_TASK_OBSERVATION') {
                $wpc->department_id             = $wpc->requestor_department_id;
                $wpc->department_name           = $wpc->requestor_department_name;
                $wpc->site                      = $request->site;
                $wpc->activity                  = $request->activity;
                $wpc->activity_company          = $request->activity_company;
                $wpc->activity_person           = $request->activity_person;
                $wpc->employee_count            = $request->employee_count;
                $wpc->area_supervisor           = $request->area_supervisor;
                $wpc->procedure                 = $request->procedure;
                $wpc->observation_reason        = !empty($request->observation_reason) ? implode(',', $request->observation_reason) : null;
            } else {
                $wpc->department_id             = $request->department_id;
                $department                     = Department::find($request->department_id);
                $wpc->department_name           = $department->name;
            }
            $wpc->location                  = $request->location;
            $wpc->date                      = $request->date;
            if ($wpc->type == 'INSPECTION_VEHICLE') {
                //$wpc->operator_id       = $request->operator_id;
                $wpc->operator_name     = $request->operator_name;
                $wpc->vehicle_code      = $request->vehicle_code;
                $wpc->vehicle_type      = $request->vehicle_type;
            }
            if ($wpc->type == 'INSPECTION_BUILDING') {
                $wpc->building_type      = $request->building_type;
            }
            $wpc->save();

            if ($request->filled('members')) {
                foreach ($request->members as $member) {
                    if (empty($member['name'])) continue;
                    $wpc->teams()->create([
                        'name'       => $member['name'],
                        'role'       => $member['role'] ?? null,
                        'department' => $member['department'] ?? null,
                    ]);
                }
            }

            if ($request->filled('items')) {
                foreach ($request->items as $row) {
                    if (empty($row['checking_item_id'])) continue;
                    $masterItem = CheckingItem::find($row['checking_item_id']);
                    $wpc->items()->create([
                        'checking_item_id'   => $row['checking_item_id'],
                        'checking_item_name' => $masterItem->name,
                        'result'             => $row['result'] ?? null,
                        'remarks'            => $row['remarks'] ?? null,
                    ]);
                }
            }

            if ($request->filled('findings')) {
                foreach ($request->findings as $row) {
                    if (empty($row['name'])) continue;
                    $wpc->items()->create([
                        'checking_item_id'   => 0,
                        'checking_item_name' => $row['name'],
                        'result'             => $row['status'] ?? null,
                        'remarks'            => 'Item Findings',
                    ]);
                }
            }

            if ($request->filled('actions')) {
                foreach ($request->actions as $row) {
                    if (empty($row['name'])) continue;
                    $inputStatus = $row['status'] ?? 'Open';
                    $assigneeId = !empty($row['assignee_id']) ? $row['assignee_id'] : null;
                    $assigneeUser = $assigneeId ? User::find($assigneeId) : null;

                    $wpca = new WorkplaceControlAction;
                    $wpca->control_id           = $wpc->id;
                    $wpca->name                 = $row['name'];
                    $wpca->category             = $row['category'] ?? null;
                    $wpca->status               = $inputStatus;
                    $wpca->assignee_id          = $assigneeId;
                    $wpca->assignee_name        = $assigneeUser?->name;
                    $wpca->due_date             = $row['due_date'] ?? null;
                    $wpca->save();

                    if ($wpca->status == 'Open') {
                        $oca = new CorrectiveAction;
                        $oca->source                    = 'WC';
                        $oca->source_id                 = $wpc->id;
                        $oca->source_no                 = $wpc->no;
                        $oca->source_action_id          = $wpca->id;
                        $oca->risk_issuer_id            = $wpc->requestor_id;
                        $oca->risk_issuer_name          = $wpc->requestor_name;
                        $oca->risk_issue_date           = $wpc->date;
                        $oca->risk_description          = $wpca->name;
                        $oca->location                  = $wpc->location;
                        $oca->department_id             = $wpc->requestor_department_id;
                        $oca->department_name           = $wpc->requestor_department_name;
                        $oca->responsible_person_id     = $wpca->assignee_id;
                        $oca->responsible_person_name   = $wpca->assignee_name;
                        $oca->corrective_action         = '';
                        $oca->due_date                  = $wpca->due_date;
                        $oca->status                    = 'ACTION_REQUIRED';
                        $oca->last_action               = 'CREATE';
                        $oca->next_action               = 'INPUT_EVIDENCE';
                        $oca->next_user_id              = $oca->responsible_person_id;
                        $oca->next_user_name            = $oca->responsible_person_name;
                        $oca->approval_level            = 1;
                        $oca->save();

                        $log                = new CorrectiveActionLog;
                        $log->action_id     = $oca->id;
                        $log->user_id       = $user->id;
                        $log->user_name     = $user->name;
                        $log->status        = $oca->status;
                        $log->remarks       = 'Generated by Module Worplace Control';
                        $log->event         = $oca->last_action;
                        $log->delegator_uid = null;
                        $log->save();

                        $ocana                = new CorrectiveActionNextApprover;
                        $ocana->action_id     = $oca->id;
                        $ocana->user_id       = $oca->next_user_id;
                        $ocana->user_name     = $oca->next_user_name;
                        $ocana->save();

                        Mail::send('mail.corrective_action_notification', ['data' => $oca, 'assignee' => $assigneeUser], function ($message) use ($oca, $assigneeUser) {
                            $message->to($assigneeUser->email);
                            $message->subject('CAR_' . $oca->source . '_Action required');
                        });
                    }
                }
            }

            if ($request->hasFile('attachments')) {
                $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'workplace_control';
                foreach ($request->file('attachments') as $i => $file) {
                    $filename    = 'OHS_WPC_' . $wpc->id . '_' . ($i + 1) . '.' . $file->getClientOriginalExtension();
                    $file->move($basePath, $filename);
                    $wpc->attachments()->create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientMimeType(),
                        'file_path' => $filename,
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('transaction-workPlace.index')->with('success', "Workplace Control has been created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function show(Request $request, $id)
    {
        $user = Auth::user();
        $data = WorkplaceControl::with(['teams', 'items', 'actions', 'findings', 'attachments'])->findOrFail($id);
        $type = $data->type;
        $type_sel = [
            'INSPECTION_VEHICLE',
            'INSPECTION_BUILDING',
            'PLANNED_TASK_OBSERVATION',
            'INSPECTION_DRILLING_AREA',
            'INSPECTION_ROAD',
            'INSPECTION_CONSTRUCTION_AREA',
            'INSPECTION_DUM_POINT_AREA',
        ];
        $dept = Department::all();
        $location = Location::all();
        $users = User::all();
        $checking_items = CheckingItem::where('group', $type)->get();
        $existingFindings = WorkplaceControlItem::where('checking_item_id', 0)
            ->where('control_id', $data->id)
            ->get();
        $is_able_to_admin_edit = in_array($user->role_id, [1, 2]);
        $is_able_to_admin_delete = in_array($user->role_id, [1, 2]);
        return view('transaction.workplace_control.show', compact(
            'data',
            'type_sel',
            'type',
            'dept',
            'location',
            'users',
            'checking_items',
            'existingFindings',
            'is_able_to_admin_edit',
            'is_able_to_admin_delete',
        ));
    }

    public function admin_edit(Request $request, $id)
    {
        $data = WorkplaceControl::with(['teams', 'items', 'actions', 'findings'])->findOrFail($id);
        $type_sel = [
            'INSPECTION_VEHICLE',
            'INSPECTION_BUILDING',
            'PLANNED_TASK_OBSERVATION',
            'INSPECTION_DRILLING_AREA',
            'INSPECTION_CONSTRUCTION_AREA',
            'INSPECTION_DUM_POINT_AREA',
            'INSPECTION_ROAD',
        ];
        $type = $request->query('type', $data->type);
        $dept = Department::all();
        $location = Location::all();
        $users = User::all();
        $existingFindings = WorkplaceControlItem::where('checking_item_id', 0)->where('control_id', $data->id)->get();
        $checking_items = CheckingItem::where('group', $type)->get();
        return view('transaction.workplace_control.admin_edit', compact(
            'data',
            'type_sel',
            'type',
            'dept',
            'location',
            'users',
            'checking_items',
            'type',
            'existingFindings'
        ));
    }

    public function admin_update(Request $request, $id)
    {
        $control = WorkplaceControl::findOrFail($id);
        $control->update($request->all());
        $control->teams()->delete();
        if ($request->has('members')) {
            foreach ($request->members as $member) {
                if (!empty($member['name'])) {
                    $control->teams()->create($member);
                }
            }
        }
        $control->items()->delete();
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $control->items()->create([
                    'checking_item_id'   => $item['checking_item_id'],
                    'checking_item_name' => $item['checking_item_name'],
                    'result'             => $item['result'],
                    'remarks'             => $item['remark'] ?? null,
                ]);
            }
        }
        if ($request->has('findings')) {
            foreach ($request->findings as $finding) {
                if (!empty($finding['name'])) {
                    $control->items()->create([
                        'checking_item_id'   => 0,
                        'checking_item_name' => $finding['name'],
                        'result'             => $finding['status'],
                        'remark'             => null,
                    ]);
                }
            }
        }
        $control->actions()->delete();
        if ($request->has('actions')) {
            foreach ($request->actions as $action) {
                if (!empty($action['name'])) {
                    $control->actions()->create($action);
                }
            }
        }
        $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'workplace_control';
        if ($request->has('remove_attachments')) {
            foreach ($request->remove_attachments as $attachId) {
                $attachment = WorkplaceControlAttachment::find($attachId);
                if ($attachment) {
                    $filePath = $basePath . '/' . $attachment->file_path;
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                    $attachment->delete();
                }
            }
        }
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $i => $file) {
                $filename    = 'OHS_WPC_' . $control->id . '_' . ($i + 1) . '.' . $file->getClientOriginalExtension();
                $file->move($basePath, $filename);
                $control->attachments()->create([
                    'file_path' => $filename,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                ]);
            }
        }
        return redirect()->route('transaction-workPlace.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $control = WorkplaceControl::with('attachments')->findOrFail($id);
        $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'workplace_control';
        if ($control->attachments->count() > 0) {
            foreach ($control->attachments as $attachment) {
                $filePath = $basePath . '/' . $attachment->file_path;
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
        }
        $control->attachments()->delete();
        $control->teams()->delete();
        $control->items()->delete();
        $control->actions()->delete();
        $control->delete();
        return redirect()->route('transaction-workPlace.index');
    }
}
