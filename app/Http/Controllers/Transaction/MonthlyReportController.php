<?php

namespace App\Http\Controllers\Transaction;

use App\Exports\MonthlyContractorExport;
use Storage;
use Exception;
use App\Models\Flow;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\MonthlyReport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MonthlyReportLog;
use App\Helpers\PermissionHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MonthlyReportActivity;
use App\Models\MonthlyReportContractor;
use App\Models\MonthlyReportContractorLog;
use Yajra\DataTables\Facades\DataTables;
use function Symfony\Component\Clock\now;
use App\Models\MonthlyReportDocumentation;
use Maatwebsite\Excel\Facades\Excel;

class MonthlyReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = MonthlyReportContractor::query();

         // logic sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrderby = $request->get('sort_order', 'desc');


        $type = strtoupper($request->query('type', ''));
        if ($type === 'APPROVAL') {
            $query->where('next_user_id', $user->id);
        } elseif ($type === 'APPROVAL_HISTORY') {
            $query->where('status', '!=', 'APPROVAL_REQUIRED');
        } elseif ($type === 'REQUEST'){
            $query->where('requestor_id' === $user->id);
        }
        $reports = $query->orderBy($sortBy, $sortOrderby)->paginate(5)->withQueryString();

        return view('transaction.monthly-reports.index', compact('user', 'reports'));
    }

    public function create()
    {
        $user = Auth::user();
        $comp = Company::find($user->company_id);

        return view('transaction.monthly-reports.create', compact('user', 'comp'));
    }


    public function edit(string $id)
    {
        $comp = Company::all();
        $report = MonthlyReportContractor::with(['activities', 'materials', 'incidents', 'indicators', 'safetyMetrics', 'documentations'])->findOrFail($id);

        return view('transaction.monthly-reports.edit', compact('comp', 'report'));
    }


    public function view_pdf(string $id)
    {
        $report = MonthlyReportContractor::find($id);

        if (!$report) {
            abort(404, "REPORT NOT FOUND !!!");
        }
        $report->period_desc = strtoupper(date('F Y', strtotime($report->report_date)));
        $pdf = Pdf::loadView('transaction.monthly-reports.view_pdf', compact('report'))
            ->setPaper('a4', 'portrait');
        return $pdf->stream("Monthly_Report_" . $report->id . ".pdf");
    }


    public function view_pdf_contractor($id)
    {
        $report = MonthlyReportContractor::with([
            'activities',
            'materials',
            'incidents',
            'indicators',
            'safetyMetrics',
            'documentations'
        ])->find($id);

        if (!$report) {
            return abort(404, "REPORT NOT FOUND !!!");
        }

        $report->period_desc = strtoupper(date('F Y', strtotime($report->report_date)));

        $pdf = Pdf::loadView('transaction.monthly-reports.view_pdf_contractor', compact('report'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("Monthly_Report_" . $report->report_no . ".pdf");
    }


    public function store(Request $request)
    {
        $user = Auth::user();
        $comp = Company::find($user->company_id);

        // 1. Logika Penentuan Status & Flow
        if ($request->action === 'draft') {
            // JIKA KLIK SAVE AS DRAFT
            $status = 'DRAFT';
            $nextUserId = null;
            $nextUserName = null;
            $lastAction = 'DRAFTED';
            $actionLabel = 'DRAFT';
            $approvalLevel = 0; // Draft biasanya di level 0
        } else {
            // JIKA KLIK SUBMIT (DEFAULT)
            $status = 'APPROVAL_REQUIRED';
            $lastAction = 'SUBMITTED';
            $approvalLevel = 1;

            // Cari Flow Approval Level 1
            $flow = Flow::where('level', 1)
                ->where('process', 'MONTHLY_REPORT_CONTRACTOR')
                ->first();

            $actionLabel = $flow->action ?? 'APPROVE';

            if ($flow && $flow->type == 'CONTRACTOR_HSE_MANAGER') {
                $hseManagerId = $comp->hse_manager_id;
                if ($hseManagerId) {
                    $nextUser = User::find($hseManagerId);
                    if ($nextUser) {
                        $nextUserId = $nextUser->id;
                        $nextUserName = $nextUser->name;
                    }
                }
            }
        }
        try {
            $month =  MonthlyReportContractor::create([
                'company_id' => $user->company_id,
                'company_name' => $comp->name,
                'operational_person_name' => $request->operational_person_name,
                'report_date' => now(),
                'report_no' => $request->report_no, #saran biarkan sistem yang isi
                'business_field' => $request->business_field,
                'issue_date' => $request->issue_date,
                'expiry_date' => $request->expiry_date,
                'operational_employee_total' => $request->operational_employee_total,
                'operational_hours'           => $request->operational_hours,
                'administration_hours'        => $request->administration_hours,
                'administration_operational_total' => $request->administration_operational_total,
                'supervision_employee_total'        => $request->supervision_employee_total,
                'supervision_hours'                 => $request->supervision_hours,
                'subcon_operational_employee_total' => $request->subcon_operational_employee_total,
                'subcon_admin_total'                => $request->subcon_admin,
                'subcon_admin_hours'                => $request->subcon_admin_hours,
                'subcon_supervision_total'          => $request->subcon_supervision_total,
                'subcon_supervision_hours'          => $request->subcon_supervision_hours,
                'machine_working_hours'             => $request->machine_working_hours,
                'local_employee_total'              => $request->local_employee_total,
                'national_employee_total'           => $request->national_employee_total,
                'foreign_employee_total'        => $request->foreign_employee_total,
                'approval_level'                => 1,
                'last_user_id'                  => $user->id,
                'last_user_name'                => $user->name,
                'next_user_id'                  => $nextUserId ?? null,
                'next_user_name'                => $nextUserName ?? null,
                'last_action'                   => $lastAction,
                'status'                        => $status,
                'action'                        => $actionLabel,
                'requestor_id'                  => $user->id,
                'requestor_name'                => $user->name,
            ]);
            if ($request->has('prev_activity')) {
                foreach ($request->prev_activity as $act) {
                    if ($act) {
                        $month->activities()->create([
                            'activity' => $act,
                            'type'     => 'PREV'
                        ]);
                    }
                }
            }
            if ($request->has('next_activity')) {
                foreach ($request->next_activity as $nAct) {
                    if ($nAct) {
                        $month->activities()->create([
                            'activity' => $nAct,
                            'type'     => 'NEXT'
                        ]);
                    }
                }
            }

            if ($request->has('name')) {
                foreach ($request->name as $key => $val) {
                    if (!empty($val)) {
                        $month->materials()->create([
                            'name'          => $val,
                            'materials_qty' => $request->materials_qty[$key] ?? 0,
                            'received_qty'  => $request->received_qty[$key] ?? 0,
                            'used_qty'      => $request->used_qty[$key] ?? 0,
                            'remaining_qty' => ($request->materials_qty[$key] ?? 0) + ($request->received_qty[$key] ?? 0) - ($request->used_qty[$key] ?? 0),
                            'uom'           => $request->uom[$key] ?? 'KG',
                        ]);
                    }
                }
            }

            if ($request->has('incident')) {
                foreach ($request->incident as $key => $val) {
                    if (!empty($val)) {
                        $month->incidents()->create([
                            'incident' => $val,
                            'status'   => $request->status[$key] ?? 'Open',
                        ]);
                    }
                }
            }

            if ($request->has('activity')) {
                foreach ($request->activity as $index => $actName) {
                    $month->indicators()->create([
                        'activity'    => $actName,
                        'jumlah_pelaksana' => $request->jumlah_pelaksana[$index] ?? 0,
                        'remarks'          => $request->remarks[$index] ?? '',
                    ]);
                }
            }

            if ($request->has('category')) {
                foreach ($request->category as $index => $catName) {
                    $month->safetyMetrics()->create([
                        'category'  => $catName,
                        'target'    => $request->target[$index] ?? 0,
                        'actual'    => $request->actual[$index] ?? 0,
                        'fr'        => $request->fr[$index] ?? 0,
                        'lost_days' => $request->lost_days[$index] ?? 0,
                        'sr'        => $request->sr[$index] ?? 0,
                    ]);
                }
            }
            if ($request->has('remarks')) {
                foreach ($request->remarks as $index => $current_remark) {
                    if ($request->hasFile("image.$index")) {
                        $file = $request->file("image.$index");
                        if ($file->isValid()) {
                            $folder = 'monthly_contractor';
                            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                            $file->storeAs($folder, $filename, 'local');
                            $month->documentations()->create([
                                'image'   => $folder . '/' . $filename,
                                'remarks' => $current_remark,
                            ]);
                        }
                    }
                }
            }

            MonthlyReportContractorLog::create([
                'report_id' => $month->id,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'event' => ($status === 'DRAFT') ? "SAVED AS DRAFT by {$user->name}" : "SUBMITTED by {$user->name}",
                'status' => $month->status,
            ]);
        } catch (\Exception $e) {
            dd($e);
        }

        return redirect()->route('transaction-monthly-reports.index');
    }


    private function saveDocumentation($month, $request, $index, $remark)
    {
        $docId = $request->doc_ids[$index] ?? null;
        $data  = ['remarks' => $remark];
        if ($request->hasFile("doc_images.$index")) {
            $file = $request->file("doc_images.$index");
            $path = $file->storeAs('monthly_contractor', time() . '_' . $file->getClientOriginalName(), 'local');
            $data['image'] = $path;
            if ($docId && ($oldDoc = $month->documentations()->find($docId))) {
                // Tambahkan pengecekan !empty($oldDoc->image)
                if (!empty($oldDoc->image)) {
                    \Storage::disk('local')->delete($oldDoc->image);
                }
            }
        }
        $month->documentations()->updateOrCreate(['id' => $docId], $data);
    }

    public function update(Request $request, $id)
    {
        // 1. Ambil input action dan paksa jadi huruf kecil
        // Jika null (tombol tidak punya name), default ke 'submit' karena di edit cuma ada tombol itu
        $inputAction = strtolower($request->action ?? 'submit');

        $user = Auth::user();
        $month = MonthlyReportContractor::findOrFail($id);
        $comp = Company::find($user->company_id);

        // Inisialisasi variabel berdasarkan data lama
        $status = $month->status;
        $nextUserId = $month->next_user_id;
        $nextUserName = $month->next_user_name;
        $lastAction = 'UPDATED';
        $actionLabel = $month->action;
        $approvalLevel = $month->approval_level;

        // 2. Logika Status (Gunakan $inputAction yang sudah di-lowercase)
        if ($inputAction === 'draft') {
            $status = 'DRAFT';
            $lastAction = 'DRAFTED';
            $actionLabel = 'DRAFT';
        } elseif ($inputAction === 'submit') {
            $status = 'APPROVAL_REQUIRED';
            $lastAction = 'SUBMITTED';
            $approvalLevel = 1;

            $flow = Flow::where('level', 1)
                ->where('process', 'MONTHLY_REPORT_CONTRACTOR')
                ->first();

            $actionLabel = $flow->action ?? 'APPROVE';

            if ($flow && $flow->type == 'CONTRACTOR_HSE_MANAGER') {
                $hseManagerId = $comp->hse_manager_id ?? null;
                if ($hseManagerId) {
                    $nextUser = User::find($hseManagerId);
                    if ($nextUser) {
                        $nextUserId = $nextUser->id;
                        $nextUserName = $nextUser->name;
                    }
                }
            }
        }

        try {
            $month->update([
                'company_id'                => $user->company_id,
                'company_name'              => $comp->name,
                'operational_person_name'   => $request->operational_person_name,
                'report_date'               => $request->report_date ? $request->report_date . '-01' : $month->report_date,
                'report_no'                 => $request->report_no,
                'business_field'            => $request->business_field,
                'issue_date'                => $request->issue_date,
                'expiry_date'               => $request->expiry_date,
                'operational_employee_total' => $request->operational_employee_total,
                'operational_hours'         => $request->operational_hours,
                'administration_hours'      => $request->administration_hours,
                'administration_operational_total' => $request->administration_operational_total,
                'supervision_employee_total' => $request->supervision_employee_total,
                'supervision_hours'         => $request->supervision_hours,
                'subcon_operational_employee_total' => $request->subcon_operational_employee_total,
                'subcon_admin_total'        => $request->subcon_admin_total, // Pastikan name di blade benar
                'subcon_admin_hours'        => $request->subcon_admin_hours,
                'subcon_supervision_total'  => $request->subcon_supervision_total,
                'subcon_supervision_hours'  => $request->subcon_supervision_hours,
                'machine_working_hours'     => $request->machine_working_hours,
                'local_employee_total'      => $request->local_employee_total,
                'national_employee_total'   => $request->national_employee_total,
                'foreign_employee_total'    => $request->foreign_employee_total,
                // Update Status & Flow
                'status'                    => $status,
                'last_action'               => $lastAction,
                'action'                    => $actionLabel,
                'next_user_id'              => $nextUserId,
                'next_user_name'            => $nextUserName,
                'approval_level'            => $approvalLevel,
                'last_user_id'              => $user->id,
                'last_user_name'            => $user->name,
            ]);
            if ($request->has('prev_activity')) {
                $month->activities()->where('type', 'PREV')->delete();
                foreach ($request->prev_activity as $act) {
                    if ($act) {
                        $month->activities()->create(['activity' => $act, 'type' => 'PREV']);
                    }
                }
            }
            if ($request->has('next_activity')) {
                $month->activities()->where('type', 'NEXT')->delete();
                foreach ($request->next_activity as $nAct) {
                    if ($nAct) {
                        $month->activities()->create(['activity' => $nAct, 'type' => 'NEXT']);
                    }
                }
            }
            if ($request->has('name')) {
                $month->materials()->delete();
                foreach ($request->name as $key => $val) {
                    if (!empty($val)) {
                        $month->materials()->create([
                            'name'          => $val,
                            'materials_qty' => $request->materials_qty[$key] ?? 0,
                            'received_qty'  => $request->received_qty[$key] ?? 0,
                            'used_qty'      => $request->used_qty[$key] ?? 0,
                            'remaining_qty' => $request->remaining_qty[$key] ?? 0,
                            'uom'           => $request->uom[$key] ?? '',
                        ]);
                    }
                }
            }
            if ($request->has('activity')) {
                foreach ($request->activity as $index => $actName) {
                    $month->indicators()->updateOrCreate(
                        ['activity' => $actName],
                        [
                            'jumlah_pelaksana' => $request->jumlah_pelaksana[$index] ?? 0,
                            'remarks'          => $request->remarks[$index] ?? '',
                        ]
                    );
                }
            }
            if ($request->has('category')) {
                foreach ($request->category as $index => $catName) {
                    $month->safetyMetrics()->updateOrCreate(
                        ['category' => $catName],
                        [
                            'target'    => $request->target[$index] ?? 0,
                            'actual'    => $request->actual[$index] ?? 0,
                            'fr'        => $request->fr[$index] ?? 0,
                            'lost_days' => $request->lost_days[$index] ?? 0,
                            'sr'        => $request->sr[$index] ?? 0,
                        ]
                    );
                }
            }
            if ($request->has('doc_remarks')) {
                foreach ($request->doc_remarks as $index => $remark) {
                    $this->saveDocumentation($month, $request, $index, $remark);
                }
            }
            MonthlyReportContractorLog::create([
                'report_id' => $month->id,
                'user_id'   => $user->id,
                'user_name' => $user->name,
                'event'     => "{$lastAction} by user {$user->name}",
                'status'    => $status,
            ]);
            return redirect()->route('transaction-monthly-reports.index');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function show($id)
    {
        $comp = Company::all();
        $log = MonthlyReportContractorLog::where('report_id', $id)->get();
        $report = MonthlyReportContractor::with(['activities', 'materials', 'incidents', 'indicators', 'safetyMetrics', 'documentations'])->findOrFail($id);
        return view('transaction.monthly-reports.show', compact('comp', 'report', 'log'));
    }

    public function destroy($id)
    {
        $report = MonthlyReportContractor::findOrFail($id);
        try {
            DB::transaction(function () use ($report) {
                foreach ($report->documentations as $doc) {
                    if ($doc->image && Storage::disk('public')->exists($doc->image)) {
                        Storage::disk('public')->delete($doc->image);
                    }
                }
                $report->activities()->delete();
                $report->materials()->delete();
                $report->incidents()->delete();
                $report->indicators()->delete();
                $report->safetyMetrics()->delete();
                $report->documentations()->delete();
                $report->delete();
            });

            return redirect()->route('transaction-monthly-reports.index')
                ->with('success', 'Laporan bulanan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }

    public function approve(Request $request, $id)
    {
        $data = MonthlyReportContractor::findOrFail($id);
        $company = Company::findOrFail($data->company_id);
        $user = Auth::user();
        if ($request->aksi === 'reject') {
            $data->update([
                'status'         => 'REJECT',
                'approval_level' => $data->approval_level,
                'last_user_id'   => $user->id,
                'last_user_name' => $user->name,
                'next_user_id'   => null,
                'next_user_name' => null,
                'action'         => 'RESUBMIT',
                'last_action'    => 'REJECTED',
                'remarks'        => $request->remarks,
            ]);
            return redirect()->route('transaction-monthly-reports.index');
        }
        $flow = Flow::where('process', 'MONTHLY_REPORT_CONTRACTOR')
            ->where('level', '>', $data->approval_level)
            ->orderBy('level', 'asc')
            ->first();

        $nextUserId = null;
        $nextUserName = null;
        $status = 'APPROVAL_REQUIRED';
        if ($flow) {
            if ($flow->type === 'CONTRACTOR_HSE_MANAGER') {
                $nextUser = User::find($company->hse_manager_id);
            } elseif ($flow->type === 'CONTRACTOR_PJO') {
                $nextUser = User::find($company->pjo_id);
            } else {
                $userIds = explode(',', $flow->value);
                $nextUser = User::find(trim($userIds[0]));
            }

            if ($nextUser) {
                $nextUserId = $nextUser->id;
                $nextUserName = $nextUser->name;
            }
            $newLevel = $flow->level;
            $nextAction = $flow->action;
        } else {
            $status = 'COMPLETED';
            $newLevel = $data->approval_level;
            $nextUserId = null;
            $nextUserName = null;
            $nextAction = 'APPROVAL';
        }
        $data->update([
            'status'         => $status,
            'approval_level' => $newLevel,
            'last_user_id'   => $user->id,
            'last_user_name' => $user->name,
            'next_user_id'   => $nextUserId,
            'next_user_name' => $nextUserName,
            'action'         => $nextAction,
            'last_action'    => 'APPROVAL',
            'remarks'        => $request->remarks,
        ]);

        return redirect()->route('transaction-monthly-reports.index')
            ->with('success', 'Laporan berhasil disetujui.');
    }

    public function excel()
    {
        $reports = MonthlyReportContractor::all();
        $filename = 'Monthly_report_contractor_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new MonthlyContractorExport($reports), $filename);
    }
}
