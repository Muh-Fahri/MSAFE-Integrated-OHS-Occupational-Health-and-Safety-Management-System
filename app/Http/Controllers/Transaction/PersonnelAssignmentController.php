<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Employee;
use App\Models\OhsMaster;
use App\Models\PersonnelAssignment;
use App\Models\PersonnelAssignmentDetail;
use App\Models\PersonnelAssignmentFlow;
use App\Models\PersonnelAssignmentLog;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PersonnelAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $menuId = PermissionHelper::getCurrentMenuId();
        $type = strtoupper($request->query('type', ''));

        // logic sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrderby = $request->get('sort_order', 'desc');

        $query = PersonnelAssignment::query();
        $query->when($request->request_no, function ($q) use ($request) {
            return $q->where('request_no', 'like', '%' . $request->request_no . '%');
        });
        $query->when($request->request_date, function ($q) use ($request) {
            return $q->whereDate('request_date', $request->request_date);
        });
        $query->when($request->status, function ($q) use ($request) {
            return $q->where('status', $request->status);
        });
        $query->when($request->requestor_name, function ($q) use ($request) {
            return $q->where('requestor_name', 'like', '%' . $request->requestor_name . '%');
        });
        if ($type === 'REQUEST') {
            $query->where('requestor_id', $user->id);
        } elseif ($type === 'APPROVAL') {
            $query->where('next_user_id', $user->id);
        } elseif ($type === 'APPROVAL_HISTORY') {
            $query->where('last_action', $user->id)->whereNot('status', 'DRAFT');
        }
        $personnelAssignments = $query->orderBy($sortBy, $sortOrderby)->paginate(5)->withQueryString();
        return view('transaction.personnel-assignments.index', compact('personnelAssignments', 'menuId', 'type'));
    }
    public function get_employee_detail(Request $request)
    {
        $employee = Employee::where('employee_id', $request->employee_id)->first(['employee_id', 'full_name', 'job_position', 'organization', 'company']);
        echo json_encode($employee);
        exit;
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $id = $request->query('id');
        if ($id) {
            $data = PersonnelAssignment::with('personel_assign')->find($id);
        } else {
            $data = new PersonnelAssignment();
            $data->requestor_id = $user->id;
            $data->requestor_name = $user->name;
            $data->request_date = date('Y-m-d');
        }
        $list_employee = Employee::whereIn('employee_status', ['Permanent', 'Probation', 'Contract'])->get(['employee_id', 'full_name']);
        $res = OhsMaster::get(['id', 'type', 'code', 'name']);
        $list_assignment_type = $res->where('type', 'PERSONNEL_ASSIGNMENT_TYPE');
        $list_assignment_field = $res->where('type', 'PERSONNEL_ASSIGNMENT_FIELD');
        $list_company = Company::get(['id', 'name']);

        return view('transaction.personnel-assignments.create', compact(
            'list_assignment_type',
            'list_assignment_field',
            'list_employee',
            'list_company',
            'data'
        ));
    }

    function get_next_request_no()
    {
        $lastRecord = PersonnelAssignment::orderByRaw('CAST(request_no AS UNSIGNED) DESC')->first();
        if (!$lastRecord) {
            return '000001';
        }
        $nextNumber = intval($lastRecord->request_no) + 1;
        return sprintf("%06d", $nextNumber);
    }

    public function store_detail(Request $request)
    {
        $user = Auth::user();
        try {
            $request->validate([
                'details' => 'required|array',
                'details.*.employee_id'     => 'required|exists:employees,employee_id',
                'details.*.assignment_type' => 'required',
            ]);
            $flow = PersonnelAssignmentFlow::where('level', '>', 0)->orderBy('level', 'asc')->first();
            if (!$flow) {
                return back()->with('error', 'Workflow approval belum diatur.');
            }
            $next_userId_val = null;
            $next_userName_val = null;
            $find_user = ($flow->type === 'HEAD_OF_DEPARTMENT')
                ? ($user->hod ?? $user->hod2)
                : trim(explode(',', $flow->value)[0]);
            if ($find_user) {
                $nextUserObj = User::find($find_user);
                if ($nextUserObj) {
                    $next_userId_val = $nextUserObj->id;
                    $next_userName_val = $nextUserObj->name;
                }
            }
            $pa = PersonnelAssignment::firstOrCreate(
                ['requestor_id' => $user->id, 'status' => 'DRAFT'],
                [
                    'request_no'     => $this->get_next_request_no(),
                    'requestor_name' => $user->name ?? 'NaN',
                    'approval_level' => 1,
                    'next_action'    => $flow->action,
                    'status'         => 'DRAFT',
                    'last_action'    => 'CREATED',
                    'next_user_id'   => $next_userId_val,
                    'next_user_name' => $next_userName_val,
                    'created_by'     => $user->name,
                    'updated_by'     => $user->name,
                ]
            );
            $pa->update([
                'company_id'     => $request->company_id ?? 6,
                'company_name'   => Company::find($request->company_id ?? 6)->name ?? 'Unknown',
                'request_date'   => $request->request_date ?? now()->format('Y-m-d'),
                'last_action'    => 'CREATED',
                'last_user_id'   => $user->id,
                'last_user_name' => $user->name,
                'next_user_id'   => $next_userId_val,
                'next_user_name' => $next_userName_val,
                'updated_by'     => $user->name,
            ]);
            $externalPath = rtrim(env('FILE_PATH'), '/');
            foreach ($request->details as $index => $item) {
                $employee = Employee::where('employee_id', $item['employee_id'])->first();
                $detail = PersonnelAssignmentDetail::updateOrCreate(
                    ['assignment_id' => $pa->id, 'employee_id' => $item['employee_id']],
                    [
                        'employee_name'       => $employee->full_name,
                        'employee_title'      => $item['title'] ?? $employee->job_position,
                        'employee_department' => $item['department'] ?? $employee->organization,
                        'assignment_type'     => $item['assignment_type'],
                        'assignment_field'    => $item['assignment_field'] ?? '-',
                    ]
                );
                for ($i = 1; $i <= 5; $i++) {
                    $fileKey = "file_{$i}_path";
                    $typeKey = "file_{$i}_type";
                    if ($request->hasFile("details.{$index}.{$fileKey}")) {
                        $file = $request->file("details.{$index}.{$fileKey}");
                        $subFolder = 'personnel-assignment';
                        $uploadPath = rtrim($externalPath, '/') . '/' . $subFolder;
                        $fileName = time() . '_' . uniqid() . "_{$index}_{$i}" . $file->getClienOriginalName();
                        $file->move($uploadPath, $fileName);
                        $detail->update([
                            $fileKey => $fileName,
                            $typeKey => $file->getClientMimeType(),
                        ]);
                    }
                }
                PersonnelAssignmentLog::create([
                    'assignment_id' => $pa->id,
                    'user_id'       => $user->id,
                    'user_name'     => $user->name,
                    'status'        => 'DRAFT',
                    'event'         => 'ADD_DETAIL',
                    'remarks'       => 'Added employee: ' . $employee->full_name,
                ]);
            }
            return redirect()->route('transaction-personnel-assignments.index')
                ->with('success', 'Data and attachments saved successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy_detail($id)
    {
        try {
            $pad = PersonnelAssignmentDetail::find($id);
            $assignment_id = $pad->assignment_id;
            $pad->delete();
            return redirect()->route('transaction-personnel-assignments.edit', ['id' => $assignment_id])->with('success', 'Detail deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function show(string $id)
    {
        $assign = PersonnelAssignment::find($id);
        $logs = PersonnelAssignmentLog::where('assignment_id', $assign->id)->get();
        $detail = PersonnelAssignmentDetail::where('assignment_id', $assign->id)->get();
        return view('transaction.personnel-assignments.show', compact([
            'assign',
            'detail',
            'logs'
        ]));
    }

    public function edit(string $id)
    {
        $user = Auth::user();
        $data = PersonnelAssignment::find($id);
        if ($data == null) {
            return "DATA NOT FOUND";
        }
        $list_employee = Employee::whereIn('employee_status', ['Permanent', 'Probation', 'Contract'])->get(['employee_id', 'full_name']);
        $res = OhsMaster::get(['id', 'type', 'code', 'name']);
        $list_assignment_type = $res->where('type', 'PERSONNEL_ASSIGNMENT_TYPE');
        $list_assignment_field   = $res->where('type', 'PERSONNEL_ASSIGNMENT_FIELD');
        $list_company = Company::get(['id', 'name']);
        $list_detail = PersonnelAssignmentDetail::where('assignment_id', $id)->get();
        return view('transaction.personnel-assignments.edit', compact(
            'list_assignment_type',
            'list_assignment_field',
            'list_employee',
            'list_company',
            'data',
            'list_detail'
        ));
    }


    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $pa = PersonnelAssignment::findOrFail($id);
        $company = Company::findOrFail($request->company_id);
        $externalPath = rtrim(env('FILE_PATH'), '/data/msafe/', '/');
        $subFolder  = 'personnel-assignment';
        $fullPath = $externalPath . '/' . $subFolder;
        for ($i = 1; $i <= 5; $i++) {
            $fileKey = "file_{$i}_path";
            $typeKey = "file_{$i}_type";

            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                if ($pa->$fileKey) {
                    $oldFilePath = $fullPath . '/' . $pa->$fileKey;
                    if (File::exists($oldFilePath)) {
                        File::delete($oldFilePath);
                    }
                }
                $filename = time() . "_" . uniqid() . "_{$i}_" . $file->getClientOriginalName();
                $file->move($fullPath, $filename);
                $pa->$fileKey = $filename;
                $pa->$typeKey = $file->getClientMimeType();
            }
        }
        $pa->update([
            'company_id'   => $request->company_id,
            'company_name' => $company->name,
            'updated_by'   => $user->name,
        ]);
        return redirect()->route('transaction-personnel-assignments.index')
            ->with('success', 'Assignment updated successfully');
    }

    public function destroy(string $id)
    {
        $data = PersonnelAssignment::with('personel_assign')->findOrFail($id);
        $data->delete();
        return redirect()->route('transaction-personnel-assignments.index');
    }

    public function view_pdf(string $id)
    {
        $report = null;
        $pdf = Pdf::loadView('transaction.personnel-assignments.view_pdf', compact('report'));
        return $pdf->stream("Form_Assignment_.pdf");
    }

    public function approve(Request $request, $id)
    {
        $assignment = PersonnelAssignment::findOrFail($id);
        $user = Auth::user();
        if ($request->action === 'reject') {
            $assignment->update([
                'next_user_id' => null,
                'next_user_name' => null,
                'last_user_id' => $user->id,
                'last_user_name' => $user->name,
                'next_action' => 'NONE',
                'last_action' => 'REJECTED',
                'status' => 'REJECTED',
                'remarks' => $request->remarks
            ]);
            return redirect()->back()->with('success', 'Assignment Rejected.');
        }
        $nextFlow = PersonnelAssignmentFlow::where('level', '>', $assignment->approval_level)
            ->orderBy('level', 'asc')
            ->first();
        $currentFlow = PersonnelAssignmentFlow::where('level', $assignment->approval_level)->first();
        if ($nextFlow) {
            $next_user_id = null;
            if ($nextFlow->type === 'HEAD_OF_DEPARTMENT') {
                $next_user_id = $user->hod ?? $user->hod2;
            } else {
                $ids = explode(',', $nextFlow->value);
                $next_user_id = $ids[0];
            }
            $nextUser = User::find($next_user_id);
            $assignment->update([
                'next_user_id'   => $nextUser->id,
                'next_user_ name' => $nextUser->name,
                'last_user_id'   => $user->id,
                'last_user_name' => $user->name,
                'approval_level' => $nextFlow->level,
                'next_action'    => $nextFlow->action,
                'last_action'    => $currentFlow->action ?? 'APPROVE',
                'status'         => 'DRAFT'
            ]);
        } else {
            $assignment->update([
                'next_user_id'   => null,
                'next_user_name' => null,
                'last_user_id'   => $user->id,
                'last_user_name' => $user->name,
                'next_action'    => 'NONE',
                'last_action'    => $currentFlow->action ?? 'APPROVE',
                'status'         => 'COMPLETED',
            ]);
        }
        $log = new PersonnelAssignmentLog;
        $log->assignment_id     = $assignment->id;
        $log->user_id           = $user->id;
        $log->user_name         = $user->name;
        $log->status            = $assignment->status;
        $log->remarks = "Transaction processed by " . $user->name . " with status " . $assignment->status;
        $log->event             = $assignment->next_action;
        return redirect()->back()->with('success', 'Assignment Processed successfully.');
    }
}
