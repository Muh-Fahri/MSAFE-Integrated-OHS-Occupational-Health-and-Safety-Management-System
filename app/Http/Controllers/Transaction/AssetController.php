<?php

namespace App\Http\Controllers\Transaction;

use App\Models\User;
use App\Models\Asset;
use App\Models\Company;
use App\Models\AssetLog;
use App\Models\OhsMaster;
use App\Models\Flow;
use App\Models\Delegation;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\AssetAttachment;
use App\Models\AssetNextApprover;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Exports\AssetExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Asset::query();

        // logic shorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
       

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
            $res = AssetNextApprover::whereIn('user_id', $user_ids)->get(['asset_id']);
            $ids = [];
            foreach ($res as $v) {
                $ids[] = $v->asset_id;
            }
            $query->whereIn('approval_status', ['APPROVAL_REQUIRED'])->whereIn('id', $ids);
        } else if ($action == 'APPROVAL_HISTORY') {
            $query->whereRaw("(id IN (SELECT DISTINCT asset_id FROM asset_logs WHERE (user_id = " . $user->id . " OR delegator_uid = " . $user->id . ") AND event NOT IN ('CREATE', 'UPDATE')))");
        } else {
            $query->where('requestor_id', $user->id);
        }
        if ($request->filled('register_date')) {
            $register_date   = explode(' to ', $request->register_date);
            $register_date_1 = date('Y-m-d', strtotime($register_date[0]));
            $register_date_2 = date('Y-m-d', strtotime($register_date[1]));
            $query->whereBetween('register_date', [$register_date_1, $register_date_2]);
        }
        if ($request->filled('code')) {
            $query->where('code', 'LIKE', '%' . $request->code . '%');
        }
        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }
        if ($request->filled('type')) {
            $query->whereIn('type', $request->type);
        }
        if ($request->filled('category')) {
            $query->whereIn('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->whereIn('status', $request->status);
        }
        if ($request->filled('department_id')) {
            $query->whereIn('department_id', $request->department_id);
        }
        if ($request->filled('company_id')) {
            $query->whereIn('company_id', $request->company_id);
        }
        if ($request->filled('requestor_name')) {
            $query->where('requestor_name', 'LIKE', '%' . $request->requestor_name . '%');
        }
        if ($request->filled('approval_status')) {
            $query->whereIn('approval_status', $request->approval_status);
        }
        $data = $query->orderBy($sortBy, $sortOrder)->paginate(10)->withQueryString();

        $list_department = Department::pluck('name', 'id')->toArray();
        $list_company = Company::pluck('name', 'id')->toArray();
        $list_type = OhsMaster::where('type', 'ASSET_TYPE')
            ->pluck('name', 'name')
            ->toArray();
        $list_category = OhsMaster::where('type', 'ASSET_CATEGORY')
            ->pluck('name', 'name')
            ->toArray();
        $list_ownership = OhsMaster::where('type', 'ASSET_OWNERSHIP')
            ->pluck('name', 'name')
            ->toArray();
        $list_status = ['ACTIVE', 'INACTIVE'];
        $list_approval_status = ['APPROVAL_REQUIRED', 'COMPLETED', 'REJECTED'];
        return view('transaction.asset_management.index', compact('data', 'list_department', 'list_company', 'list_type', 'list_category', 'list_ownership', 'list_status', 'list_approval_status'));
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        return Excel::download(new AssetExport($request, $user), 'asset.xlsx');
    }

    public function create()
    {
        $user = Auth::user();
        $list_department = Department::pluck('name', 'id')->toArray();
        $list_company = Company::pluck('name', 'id')->toArray();
        $list_type = OhsMaster::where('type', 'ASSET_TYPE')
            ->pluck('name', 'name')
            ->toArray();
        $list_category = OhsMaster::where('type', 'ASSET_CATEGORY')
            ->pluck('name', 'name')
            ->toArray();
        $list_ownership = OhsMaster::where('type', 'ASSET_OWNERSHIP')
            ->pluck('name', 'name')
            ->toArray();
        return view('transaction.asset_management.create', compact(
            'list_department',
            'list_company',
            'list_type',
            'list_category',
            'list_ownership'
        ));
    }

    function get_next_approver($asset, $level)
    {
        $next_user = null;
        $flow = Flow::where('process', 'ASSET')->where('level', '>', $level)->orderBy('level', 'ASC')->first();
        if ($flow != null) {
            if ($flow->type == 'HEAD_OF_DEPARTMENT') {
                $requestor          = User::find($asset->requestor_id);
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
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $asset                                = new Asset;
            $asset->code                        = $request->code;
            $asset->name                          = $request->name;
            $asset->type                          = $request->type;
            $asset->category                      = $request->category;
            $asset->register_date                 = date('Y-m-d');
            $asset->department_id                 = $request->department_id;
            $department                         = Department::find($request->department_id);
            $asset->department_name               = $department->name;
            $asset->company_id                    = $request->company_id;
            $company                            = Company::find($request->company_id);
            $asset->company_name                  = $company->name;
            $asset->specification                  = $request->specification;
            $asset->commissioning_date          = $request->commissioning_date;
            $asset->assembly_year                 = $request->assembly_year;
            $asset->maintenance_period             = $request->maintenance_period;
            $asset->ownership                     = $request->ownership;
            $asset->status                       = 'INACTIVE';

            $asset->requestor_id                  = $user->id;
            $asset->requestor_name                = $user->name;
            $asset->requestor_department_id       = $user->department_id;
            $department                         = Department::find($user->department_id);
            $asset->requestor_department_name     = $department->name;
            $asset->save();

            $asset->last_action       = 'CREATE';
            $asset->last_user_id      = $user->id;
            $asset->last_user_name    = $user->name;
            $asset->last_approval_level = 0;
            $asset->next_action       = 'APPROVAL';
            $next_user                  = $this->get_next_approver($asset, 0);
            $asset->next_user_id      = $next_user->id;
            $asset->next_user_name    = $next_user->name;
            $asset->approval_status   = 'APPROVAL_REQUIRED';
            $asset->approval_level    = 1;
            $asset->save();

            if ($request->hasFile('attachments')) {
                $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'asset';
                foreach ($request->file('attachments') as $i => $file) {
                    $path = $file->store('', 'local');
                    $filename    = 'OHS_ASSET_' . $asset->id . '_' . ($i + 1) . '.' . $file->getClientOriginalExtension();
                    $file->move($basePath, $filename);
                    AssetAttachment::create([
                        'asset_id'  => $asset->id,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientMimeType(),
                        'file_path' => $filename,
                    ]);
                }
            }

            $log                = new AssetLog;
            $log->asset_id        = $asset->id;
            $log->user_id       = $user->id;
            $log->user_name     = $user->name;
            $log->status        = $asset->approval_status;
            $log->remarks       = $request->remarks;
            $log->event         = $asset->last_action;
            $log->delegator_uid = null;
            $log->save();

            AssetNextApprover::where('asset_id', $asset->id)->delete();
            if ($next_user != null) {
                $next_user_ids = explode(',', $next_user->value);
                foreach ($next_user_ids as $next_user_id) {
                    $nextuser                           = User::find($next_user_id);
                    $assetnextapprover                    = new AssetNextApprover;
                    $assetnextapprover->asset_id        = $asset->id;
                    $assetnextapprover->user_id           = $nextuser->id;
                    $assetnextapprover->user_name         = $nextuser->name;
                    $assetnextapprover->save();

                    // send email to next user
                    Mail::send('mail.asset_approval', ['data' => $asset, 'nextuser' => $nextuser], function ($message) use ($asset, $nextuser) {
                        $message->to($nextuser->email);
                        $message->subject('Asset Management (AM)_' . $asset->code . '_Approval Request');
                    });
                }
            }
            DB::commit();
            return redirect()->route('transaction-asset.index')
                ->with('success', 'Asset ' . $asset->code . ' has been submitted for approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error occurs: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $asset = Asset::find($id);
        $asset->logs = AssetLog::where('asset_id', $id)->orderBy('id', 'ASC')->get();
        $asset->attachments = AssetAttachment::where('asset_id', $id)->get();
        $delegated = Delegation::where('type', 'ALL')
            ->where('delegator', $asset->next_user_id)
            ->where('delegatee', $user->id)
            ->whereDate('begin_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->exists();
        $next_user_ids = AssetNextApprover::where('asset_id', $id)
            ->pluck('user_id')
            ->toArray();
        $is_able_to_admin_edit = in_array($user->role_id, [1, 2]);
        return view('transaction.asset_management.show', compact(
            'asset',
            'delegated',
            'next_user_ids',
            'user',
            'is_able_to_admin_edit',
        ));
    }


    public function edit(string $id)
    {
        $data = Asset::with('attachments')->findOrFail($id);
        $data->attachments = AssetAttachment::where('asset_id', $id)->get();
        $list_department = Department::pluck('name', 'id')->toArray();
        $list_company = Company::pluck('name', 'id')->toArray();
        $list_type = OhsMaster::where('type', 'ASSET_TYPE')
            ->pluck('name', 'name')
            ->toArray();
        $list_category = OhsMaster::where('type', 'ASSET_CATEGORY')
            ->pluck('name', 'name')
            ->toArray();
        $list_ownership = OhsMaster::where('type', 'ASSET_OWNERSHIP')
            ->pluck('name', 'name')
            ->toArray();
        return view('transaction.asset_management.edit', compact(
            'list_department',
            'list_company',
            'list_type',
            'list_category',
            'list_ownership',
            'data'
        ));
    }


    public function update(Request $request, $id)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $asset                              = Asset::find($request->id);
            $asset->code                        = $request->code;
            $asset->name                        = $request->name;
            $asset->type                        = $request->type;
            $asset->category                    = $request->category;
            $asset->register_date               = date('Y-m-d');
            $asset->department_id               = $request->department_id;
            $department                         = Department::find($request->department_id);
            $asset->department_name             = $department->name;
            $asset->company_id                  = $request->company_id;
            $company                            = Company::find($request->company_id);
            $asset->company_name                = $company->name;
            $asset->specification               = $request->specification;
            $asset->commissioning_date          = $request->commissioning_date;
            $asset->assembly_year               = $request->assembly_year;
            $asset->maintenance_period          = $request->maintenance_period;
            $asset->ownership                   = $request->ownership;
            $asset->status                      = 'INACTIVE';
            $asset->requestor_id                = $user->id;
            $asset->requestor_name              = $user->name;
            $asset->requestor_department_id     = $user->department_id;
            $department                         = Department::find($user->department_id);
            $asset->requestor_department_name   = $department->name;

            $approval_level                     = !empty($asset->last_approval_level) ? $asset->last_approval_level - 1 : 0;
            $next_user                          = $this->get_next_approver($asset, $approval_level);
            $asset->next_user_id                = $next_user->id;
            $asset->next_user_name              = $next_user->name;
            $asset->next_action                 = $next_user->action;
            $asset->approval_status             = 'APPROVAL_REQUIRED';
            $asset->approval_level              = $next_user->level;

            $asset->last_action                 = 'UPDATE';
            $asset->last_user_id                = $user->id;
            $asset->last_user_name              = $user->name;
            $asset->last_approval_level         = 0;
            $asset->save();


            if ($request->hasFile('attachments')) {
                $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'asset';
                foreach ($request->file('attachments') as $i => $file) {
                    $filename    = 'OHS_ASSET_' . $asset->id . '_' . ($i + 1) . '.' . $file->getClientOriginalExtension();
                    $file->move($basePath, $filename);
                    AssetAttachment::create([
                        'asset_id'  => $asset->id,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientMimeType(),
                        'file_path' => $filename,
                    ]);
                }
            }

            $log                = new AssetLog;
            $log->asset_id      = $asset->id;
            $log->user_id       = $user->id;
            $log->user_name     = $user->name;
            $log->status        = $asset->approval_status;
            $log->remarks       = $request->remarks;
            $log->event         = $asset->last_action;
            $log->delegator_uid = null;
            $log->save();

            AssetNextApprover::where('asset_id', $asset->id)->delete();
            if ($next_user != null) {
                $next_user_ids = explode(',', $next_user->value);
                foreach ($next_user_ids as $next_user_id) {
                    $nextuser                           = User::find($next_user_id);
                    $assetnextapprover                  = new AssetNextApprover;
                    $assetnextapprover->asset_id        = $asset->id;
                    $assetnextapprover->user_id         = $nextuser->id;
                    $assetnextapprover->user_name       = $nextuser->name;
                    $assetnextapprover->save();

                    // send email to next user
                    Mail::send('mail.asset_approval', ['data' => $asset, 'nextuser' => $nextuser], function ($message) use ($asset, $nextuser) {
                        $message->to($nextuser->email);
                        $message->subject('Asset Management (AM)_' . $asset->code . '_Approval Request');
                    });
                }
            }

            DB::commit();
            return redirect()->route('transaction-asset.index')->with('success', 'Asset ' . $asset->code . ' has been saved.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error occurs: ' . $e->getMessage());
        }
    }

    public function admin_edit(string $id)
    {
        $data = Asset::with('attachments')->findOrFail($id);
        $data->attachments = AssetAttachment::where('asset_id', $id)->get();
        $list_department = Department::pluck('name', 'id')->toArray();
        $list_company = Company::pluck('name', 'id')->toArray();
        $list_type = OhsMaster::where('type', 'ASSET_TYPE')
            ->pluck('name', 'name')
            ->toArray();
        $list_category = OhsMaster::where('type', 'ASSET_CATEGORY')
            ->pluck('name', 'name')
            ->toArray();
        $list_ownership = OhsMaster::where('type', 'ASSET_OWNERSHIP')
            ->pluck('name', 'name')
            ->toArray();
        return view('transaction.asset_management.admin_edit', compact(
            'list_department',
            'list_company',
            'list_type',
            'list_category',
            'list_ownership',
            'data'
        ));
    }

    public function admin_update(Request $request, $id)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $asset                              = Asset::find($request->id);
            $asset->code                        = $request->code;
            $asset->name                        = $request->name;
            $asset->type                        = $request->type;
            $asset->category                    = $request->category;
            $asset->company_id                  = $request->company_id;
            $company                            = Company::find($request->company_id);
            $asset->company_name                = $company->name;
            $asset->department_id               = $request->department_id;
            $department                         = Department::find($request->department_id);
            $asset->department_name             = $department->name;
            $asset->register_date               = $request->register_date;
            $asset->specification               = $request->specification;
            $asset->commissioning_date          = $request->commissioning_date;
            $asset->assembly_year               = $request->assembly_year;
            $asset->maintenance_period          = $request->maintenance_period;
            $asset->ownership                   = $request->ownership;
            $asset->last_action                 = 'ADMIN_UPDATE';
            $asset->last_user_id                = $user->id;
            $asset->last_user_name              = $user->name;
            $asset->save();


            if ($request->hasFile('attachments')) {
                $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'asset';
                foreach ($request->file('attachments') as $i => $file) {
                    $filename    = 'OHS_ASSET_' . $asset->id . '_' . ($i + 1) . '.' . $file->getClientOriginalExtension();
                    $file->move($basePath, $filename);
                    AssetAttachment::create([
                        'asset_id'  => $asset->id,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientMimeType(),
                        'file_path' => $filename,
                    ]);
                }
            }

            $log                = new AssetLog;
            $log->asset_id      = $asset->id;
            $log->user_id       = $user->id;
            $log->user_name     = $user->name;
            $log->status        = $asset->approval_status;
            $log->remarks       = $request->remarks;
            $log->event         = $asset->last_action;
            $log->delegator_uid = null;
            $log->save();

            DB::commit();
            return redirect()->route('transaction-asset.index')->with('success', 'Asset ' . $asset->code . ' has been saved.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error occurs: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $data = Asset::with('attachments')->findOrFail($id);
        $basePath = rtrim(env('FILE_PATH', '/data/msafe/')) . 'asset';
        foreach ($data->attachments as $file) {
            $path = $basePath . '/' . $file->file_path;
            if (File::exists($path)) {
                File::delete($path);
            }
        }
        $data->delete();
        return redirect()->route('transaction-asset.index')->with('success', 'Asset has been deleted.');
    }

    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $asset = Asset::findOrFail($id);
            $asset->last_action       = $request->action;
            $asset->last_user_id      = $user->id;
            $asset->last_user_name    = $user->name;
            $asset->last_approval_level = $asset->approval_level;
            $delegator_uid              = $asset->next_user_id;
            $next_user                  = null;
            $next_approval_status          = 'REJECTED';
            $next_approval_level        = 0;
            if ($request->action == 'APPROVE') {
                $next_user = $this->get_next_approver($asset, $asset->approval_level);
                if ($next_user == null) {
                    $next_approval_status     = 'COMPLETED';
                    $asset->status             = 'ACTIVE';
                } else {
                    $next_approval_status     = 'APPROVAL_REQUIRED';
                    $next_approval_level     = $next_user->level;
                }
            }
            $asset->approval_status      = $next_approval_status;
            $asset->approval_level         = $next_approval_level;
            $asset->next_action            = $next_user == null ? null : $next_user->action;
            $asset->next_user_id           = $next_user == null ? null : $next_user->id;
            $asset->next_user_name         = $next_user == null ? null : $next_user->name;
            $asset->remarks                = $request->remarks;
            $asset->save();

            $assetlog                    = new AssetLog;
            $assetlog->asset_id            = $asset->id;
            $assetlog->user_id           = $user->id;
            $assetlog->user_name         = $user->name;
            $assetlog->status            = $asset->approval_status;
            $assetlog->remarks           = $request->remarks;
            $assetlog->event             = $asset->last_action;
            $assetlog->delegator_uid     = $delegator_uid;
            $assetlog->save();

            AssetNextApprover::where('asset_id', $asset->id)->delete();
            if (!empty($asset->next_action)) {
                $next_user_ids = explode(',', $next_user->value);
                foreach ($next_user_ids as $next_user_id) {
                    $nextuser                           = User::find($next_user_id);
                    $assetnextapprover                    = new AssetNextApprover;
                    $assetnextapprover->asset_id        = $asset->id;
                    $assetnextapprover->user_id           = $nextuser->id;
                    $assetnextapprover->user_name         = $nextuser->name;
                    $assetnextapprover->save();

                    // send email to next user
                    Mail::send('mail.asset_approval', ['data' => $asset, 'nextuser' => $nextuser], function ($message) use ($asset, $nextuser) {
                        $message->to($nextuser->email);
                        $message->subject('Asset Management (AM)_' . $asset->code . '_Approval Request');
                    });
                }
            }

            // send email to requestor
            $requestor = User::find($asset->requestor_id);
            Mail::send('mail.asset_status_notification', ['data' => $asset, 'requestor' => $requestor], function ($message) use ($asset, $requestor) {
                $message->to($requestor->email);
                $message->subject('Asset Management (AM)_' . $asset->code . '_Status changed');
            });

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
        return redirect()->route('transaction-asset.index', ['action' => 'APPROVAL'])->with('success', "Asset {$asset->code} has been approved/rejected.");
    }

    public function update_asset_status(Request $request, $id)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $asset = Asset::findOrFail($id);
            $asset->last_action         = $request->action;
            $asset->last_user_id        = $user->id;
            $asset->last_user_name      = $user->name;
            $asset->status              = $request->action == 'ACTIVATE' ? 'ACTIVE' : 'INACTIVE';
            $asset->save();

            $assetlog               = new AssetLog;
            $assetlog->asset_id     = $asset->id;
            $assetlog->user_id      = $user->id;
            $assetlog->user_name    = $user->name;
            $assetlog->status       = $asset->approval_status;
            $assetlog->remarks      = $request->remarks;
            $assetlog->event        = $asset->last_action;
            $assetlog->save();

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
        return redirect()->route('transaction-asset.index', ['action' => 'REQUEST'])->with('success', "Asset {$asset->code} status has been updated.");
    }
}
