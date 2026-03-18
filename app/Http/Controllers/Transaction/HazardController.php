<?php

namespace App\Http\Controllers\Transaction;

use App\Models\User;
use App\Models\Hazard;
use App\Models\Location;
use App\Models\HazardLog;
use App\Models\OhsMaster;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\CorrectiveAction;
use App\Helpers\PermissionHelper;
use App\Models\CorrectiveActionLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\CorrectiveActionNextApprover;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HazardExport;

class HazardController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        $menuId = PermissionHelper::getCurrentMenuId();
        $query = Hazard::query();
        $action = !empty($request->action) ? strtoupper($request->action) : '';
        
        // logic shorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if($action=='MONITORING') {
            //
        } else if($action=='DEPARTMENT_MONITORING') {
            $res = User::where('department_id', $user->department_id)->get(['id']);
            $list_user_id = [];
            foreach($res as $v){
                $list_user_id[] = $v->id;
            }
            $query->whereIn('requestor_id', $list_user_id);
        } else {
            $query->where('requestor_id', $user->id);
        }

        if($request->filled('report_date')){
            $report_date   = explode(' to ', $request->report_date);
            $report_date_1 = date('Y-m-d', strtotime($report_date[0]));
            $report_date_2 = date('Y-m-d', strtotime($report_date[1]));
            $query->whereBetween('report_datetime', [$report_date_1.' 00:00:00', $report_date_2.' 23:59:59']);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('no')) {
            $query->where('no', 'LIKE', '%' . $request->no . '%');
        }
        if ($request->filled('reporter_name')) {
            $query->where('reporter_name', 'LIKE', '%' . $request->reporter_name . '%');
        }
        if ($request->filled('reporter_department_name')) {
            $query->where('reporter_department_name', 'LIKE', '%' . $request->reporter_department_name . '%');
        }
        $hazards = $query->orderBy($sortBy, $sortOrder)->paginate(10)->appends($request->query());

        return view('transaction.hazards.index', compact('hazards', 'menuId'));
    }

    public function export(Request $request){
        $user = Auth::user();
        return Excel::download(new HazardExport($request, $user), 'hazard.xlsx');
    }

    public function create()
    {
        $list_status = [
            'ACTION_REQUIRED' => 'ACTION REQUIRED',
            'COMPLETED' => 'COMPLETED',
        ];
        $data = new Hazard();
        $list_user = User::where('status', 'active')->get(['id', 'name']);
        $res = OHSMaster::get(['id', 'type', 'code', 'name']);
        $list_hazard_source = $res->where('type', 'HAZARD_SOURCE');
        $list_hazard_type   = $res->where('type', 'HAZARD_TYPE');
        $list_department = Department::get(['id', 'name']);
        $list_location   = Location::get(['id', 'name']);
        return view('transaction.hazards.create', compact(
            'list_hazard_source',
            'list_hazard_type',
            'list_location',
            'list_department',
            'list_user',
            'list_status',
            'data'
        ));
    }

    function getNextNo(){
        $prefix = 'HZ-'.date('m').'-'.date('Y').'-';
        $hazard = Hazard::selectRaw("SUBSTRING(no,12,4) AS no2")
            ->whereRaw("SUBSTRING(no,7,4) = YEAR(CURRENT_DATE)")
            ->orderBy('no2', 'DESC')
            ->first();
        if($hazard==null){
            return $prefix.'0001';
        } else {
            $hazardno = $hazard->no2;
            $hazardno = intval($hazardno);
            $hazardno += 1;
            return sprintf($prefix."%04d", $hazardno);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_1_path' => 'nullable|file|mimes:jpg,png,pdf,docx,jpeg|max:2048',
            'file_2_path' => 'nullable|file|mimes:jpg,png,pdf,docx,jpeg|max:2048',
            'file_3_path' => 'nullable|file|mimes:jpg,png,pdf,docx,jpeg|max:2048',
            'file_4_path' => 'nullable|file|mimes:jpg,png,pdf,docx,jpeg|max:2048',
        ]);
        $user = Auth::user();
        $request->validate([
            'reporter_id'        => 'required',
            'assignee_id'        => 'required',
            'hazard_source'      => 'required',
            'hazard_type'        => 'required',
            'hazard_description' => 'required',
            'report_datetime'    => 'required',
            'status'             => 'required',
            'location'           => 'required',
        ]);
        DB::beginTransaction();
        try {
            $hazard = new Hazard;
            $hazard->no                         = $this->getNextNo();
            $hazard->requestor_id               = $user->id;
            $hazard->requestor_name             = $user->name;
            $hazard->requestor_department_id    = $user->department_id;
            $department                         = Department::find($user->department_id);
            $hazard->requestor_department_name  = $department->name;

            $hazard->reporter_id                = $request->reporter_id;
            $reporter                           = User::find($request->reporter_id);
            $hazard->reporter_name              = $reporter->name;
            $hazard->reporter_department_id     = $request->reporter_department_id;
            $department                         = Department::find($request->reporter_department_id);
            $hazard->reporter_department_name   = $department->name;


            $hazard->recipient_id               = $request->assignee_id;
            $recipient                          = User::find($request->assignee_id);
            $hazard->recipient_name             = $recipient->name;
            $hazard->recipient_department_id    = $request->assignee_department_id;
            $department                         = Department::find($request->assignee_department_id);
            $hazard->recipient_department_name  = $department->name;

            $hazard->assignee_id                = $request->assignee_id;
            $assignee                           = User::find($request->assignee_id);
            $hazard->assignee_name              = $assignee->name;
            $hazard->assignee_department_id     = $request->assignee_department_id;
            $department                         = Department::find($request->assignee_department_id);
            $hazard->assignee_department_name   = $department->name;

            $hazard->report_datetime        = $request->report_datetime;
            $hazard->location               = $request->location;
            $hazard->hazard_source          = $request->hazard_source;
            $hazard->hazard_type            = $request->hazard_type;
            $hazard->hazard_description     = $request->hazard_description;
            $hazard->immediate_actions      = $request->immediate_actions;
            $hazard->corrective_action      = $request->status == 'COMPLETED' ? null : $request->corrective_action;
            $hazard->action_taken           = $request->status == 'COMPLETED' ? $request->action_taken : null;
            $hazard->remarks                = $request->remarks;
            $hazard->due_date               = $request->due_date;
            $hazard->completed_date         = $request->status == 'COMPLETED' ? $request->completed_date : null;

            $hazard->status                 = $request->status; // COMPLETED or NEED_FURTHER_ACTION
            $hazard->last_action            = 'SUBMIT';
            $hazard->last_user_id           = $user->id;
            $hazard->last_user_name         = $user->name;
            $hazard->next_action            = $request->status == 'ACTION_REQUIRED' ? $request->status : '';
            $hazard->approval_level         = 0;
            $hazard->save();

            $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'hazard';
            $fileFields = ['file_1_path', 'file_2_path', 'file_3_path', 'file_4_path'];
            foreach ($fileFields as $index => $field) {
                if ($request->hasFile($field)) {
                    if (!empty($hazard->$field)) {
                        $oldFilePath = $basePath . '/' . $hazard->$field;
                        if (File::exists($oldFilePath)) {
                            File::delete($oldFilePath);
                        }
                    }
                    $file = $request->file($field);
                    $slot = $index + 1;
                    $filename    = 'OHS_HZ_'.$hazard->id.'_FILE_'.$slot.'.'.$file->getClientOriginalExtension();
                    $file->move($basePath, $filename);
                    $hazard->{"file_{$slot}_path"} = $filename;
                    $hazard->{"file_{$slot}_type"} = $file->getClientMimeType();
                }
            }
            $hazard->save();

            $hazardlog = new HazardLog;
            $hazardlog->hazard_id    = $hazard->id;
            $hazardlog->user_id       = $user->id;
            $hazardlog->status        = $hazard->status;
            $hazardlog->remarks       = '';
            $hazardlog->event         = $hazard->last_action;
            $hazardlog->save();

            if($hazard->status=='ACTION_REQUIRED') {
                $oca = new CorrectiveAction;
                $oca->source                = 'HZ';
                $oca->source_id             = $hazard->id;
                $oca->source_no             = $hazard->no;
                $oca->risk_issuer_id        = $hazard->requestor_id;
                $oca->risk_issuer_name      = $hazard->requestor_name;
                $oca->risk_issue_date       = date('Y-m-d', strtotime($hazard->report_datetime));
                $oca->risk_description      = $hazard->hazard_description;
                $oca->location              = $hazard->location;
                $oca->department_id         = $hazard->requestor_department_id;
                $oca->department_name       = $hazard->requestor_department_name;
                $oca->responsible_person_id = $hazard->assignee_id;
                $oca->responsible_person_name   = $hazard->assignee_name;
                $oca->corrective_action     = $hazard->corrective_action;
                $oca->due_date              = $hazard->due_date;
                $oca->status                = 'ACTION_REQUIRED';
                $oca->last_action           = 'CREATE';
                $oca->next_action           = 'INPUT_EVIDENCE';
                $oca->next_user_id          = $oca->responsible_person_id;
                $oca->next_user_name        = $oca->responsible_person_name;
                $oca->approval_level        = 1;
                $oca->save();

                $log                = new CorrectiveActionLog;
                $log->action_id     = $oca->id;
                $log->user_id       = $user->id;
                $log->user_name     = $user->name;
                $log->status        = $oca->status;
                $log->remarks       = 'Generated by Module Hazard';
                $log->event         = $oca->last_action;
                $log->delegator_uid = null;
                $log->save();

                $ocana                = new CorrectiveActionNextApprover;
                $ocana->action_id     = $oca->id;
                $ocana->user_id       = $oca->next_user_id;
                $ocana->user_name     = $oca->next_user_name;
                $ocana->save();

                // Send mail to assignee
                Mail::send('mail.corrective_action_notification', ['data' => $oca, 'assignee' => $assignee], function($message) use($oca, $assignee){
                    $message->to($assignee->email);
                    $message->subject('CAR_'.$oca->source.'_Action required');
                });
            }
            DB::commit();
            return redirect()->route('transaction-hazards.index')->with('success', 'Hazard saved successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function show(string $id)
    {
        $user = Auth::user();
        $data = Hazard::findOrFail($id);
        $ohs = OhsMaster::all();
        $hazard_source = $ohs->where('type', 'HAZARD_SOURCE');
        $list_hazard_type   = $ohs->where('type', 'HAZARD_TYPE');
        $list_user = User::where('status', 'active')->get();
        $list_location = Location::all();
        $list_department = Department::all();
        $list_status = [
            'ACTION_REQUIRED' => 'ACTION_REQUIRED',
            'COMPLETED' => 'COMPLETED',
        ];
        $is_able_to_admin_edit = in_array($user->role_id, [1, 2]);
        $is_able_to_admin_delete = in_array($user->role_id, [1, 2]);
        return view('transaction.hazards.show', compact(
            'data',
            'hazard_source',
            'list_hazard_type',
            'list_user',
            'list_status',
            'list_location',
            'list_department',
            'is_able_to_admin_edit',
            'is_able_to_admin_delete',
        ));
    }

    public function admin_edit(string $id)
    {
        $data = Hazard::findOrFail($id);
        $ohs = OhsMaster::all();
        $hazard_source = $ohs->where('type', 'HAZARD_SOURCE');
        $list_hazard_type   = $ohs->where('type', 'HAZARD_TYPE');
        $list_user = User::where('status', 'active')->get();
        $list_location = Location::all();
        $list_department = Department::all();
        $list_status = [
            'ACTION_REQUIRED' => 'ACTION REQUIRED',
            'COMPLETED' => 'COMPLETED',
        ];
        return view('transaction.hazards.admin_edit', compact(
            'data',
            'hazard_source',
            'list_hazard_type',
            'list_user',
            'list_status',
            'list_location',
            'list_department'
        ));
    }

    public function admin_update(Request $request, string $id)
    {
        $request->validate([
            'file_1_path' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
            'file_2_path' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
            'file_3_path' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
            'file_4_path' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $hazard                             = Hazard::findOrFail($request->id);
            $hazard->reporter_id                = $request->reporter_id;
            $reporter                           = User::findOrFail($request->reporter_id);
            $hazard->reporter_name              = $reporter->name;
            $hazard->reporter_department_id     = $request->reporter_department_id;
            $department                         = Department::findOrFail($request->reporter_department_id);
            $hazard->reporter_department_name   = $department->name;
            $hazard->recipient_id               = $request->assignee_id;
            $recipient                          = User::findOrFail($request->assignee_id);
            $hazard->recipient_name             = $recipient->name;
            $hazard->recipient_department_id    = $request->assignee_department_id;
            $department                         = Department::findOrFail($request->assignee_department_id);
            $hazard->recipient_department_name  = $department->name;
            $hazard->assignee_id                = $request->assignee_id;
            $assignee                           = User::findOrFail($request->assignee_id);
            $hazard->assignee_name              = $assignee->name;
            $hazard->assignee_department_id     = $request->assignee_department_id;
            $department                         = Department::findOrFail($request->assignee_department_id);
            $hazard->assignee_department_name   = $department->name;
            $hazard->report_datetime            = $request->report_datetime;
            $hazard->location                   = $request->location;
            $hazard->hazard_source              = $request->hazard_source;
            $hazard->hazard_type                = $request->hazard_type;
            $hazard->hazard_description         = $request->hazard_description;
            $hazard->immediate_actions          = $request->immediate_actions;
            $hazard->corrective_action          = $request->status == 'COMPLETED' ? null : $request->corrective_action;
            $hazard->action_taken               = $request->status == 'COMPLETED' ? $request->action_taken : null;
            $hazard->remarks                    = $request->remarks;
            $hazard->due_date                   = $request->due_date;
            $hazard->completed_date             = $request->status == 'COMPLETED' ? $request->completed_date : null;
            $hazard->status                     = $request->status; // COMPLETED or NEED_FURTHER_ACTION
            $hazard->last_action                = 'ADMIN_UPDATE';
            $hazard->last_user_id               = $user->id;
            $hazard->last_user_name             = $user->name;
            $hazard->next_action                = $request->status == 'ACTION_REQUIRED' ? $request->status : '';
            $hazard->approval_level             = 0;
            $hazard->save();

            $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'hazard';
            $fileFields = ['file_1_path', 'file_2_path', 'file_3_path', 'file_4_path'];
            foreach ($fileFields as $index => $field) {
                if ($request->hasFile($field)) {
                    if (!empty($hazard->$field)) {
                        $oldFilePath = $basePath . '/' . $hazard->$field;
                        if (File::exists($oldFilePath)) {
                            File::delete($oldFilePath);
                        }
                    }
                    $file = $request->file($field);
                    $slot = $index + 1;
                    $filename    = 'OHS_HZ_'.$hazard->id.'_FILE_'.$slot.'.'.$file->getClientOriginalExtension();
                    $file->move($basePath, $filename);
                    $hazard->{"file_{$slot}_path"} = $filename;
                    $hazard->{"file_{$slot}_type"} = $file->getClientMimeType();
                }
            }

            $hazardlog              = new HazardLog;
            $hazardlog->hazard_id   = $hazard->id;
            $hazardlog->user_id     = $user->id;
            $hazardlog->status      = $hazard->status;
            $hazardlog->remarks     = '';
            $hazardlog->event       = $hazard->last_action;
            $hazardlog->save();

            if($hazard->status=='ACTION_REQUIRED') {
                $oca                        = CorrectiveAction::where('source', 'HZ')->where('source_id', $hazard->id)->first();
                if($oca==null) {
                    $oca = new CorrectiveAction;
                }
                $oca->source                    = 'HZ';
                $oca->source_id                 = $hazard->id;
                $oca->source_no                 = $hazard->no;
                $oca->risk_issuer_id            = $hazard->requestor_id;
                $oca->risk_issuer_name          = $hazard->requestor_name;
                $oca->risk_issue_date           = date('Y-m-d', strtotime($hazard->report_datetime));
                $oca->risk_description          = $hazard->hazard_description;
                $oca->location                  = $hazard->location;
                $oca->department_id             = $hazard->requestor_department_id;
                $oca->department_name           = $hazard->requestor_department_name;
                $oca->responsible_person_id     = $hazard->assignee_id;
                $oca->responsible_person_name   = $hazard->assignee_name;
                $oca->corrective_action         = $hazard->corrective_action;
                $oca->due_date                  = $hazard->due_date;
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
                $log->remarks       = 'Generated by Module Hazard [ADMIN_UPDATE]';
                $log->event         = $oca->last_action;
                $log->delegator_uid = null;
                $log->save();

                CorrectiveActionNextApprover::where('action_id', $oca->id)->delete();
                $ocana                = new CorrectiveActionNextApprover;
                $ocana->action_id     = $oca->id;
                $ocana->user_id       = $oca->next_user_id;
                $ocana->user_name     = $oca->next_user_name;
                $ocana->save();

                Mail::send('mail.corrective_action_notification', ['data' => $oca, 'assignee' => $assignee], function($message) use($oca, $assignee){
                    $message->to($assignee->email);
                    $message->subject('CAR_'.$oca->source.'_Action required');
                });
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
        return redirect()->route('transaction-hazards.index')->with('success', 'Data has been updated.');
    }

    public function destroy(string $id) {
        $hazard = Hazard::findOrFail($id);
        $basePath = rtrim(env('FILE_PATH', '/data/msafe/'), '/') . '/hazard';
        foreach (range(1,4) as $i) {
            $column = "file_{$i}_path";

            if ($hazard->$column && File::exists($basePath.'/'.$hazard->$column)) {
                File::delete($basePath.'/'.$hazard->$column);
            }
        }
        HazardLog::where('hazard_id', $id)->delete();
        $hazard->delete();
        return redirect()->route('transaction-hazards.index')->with('success', 'Deleted successfully');
    }
}
