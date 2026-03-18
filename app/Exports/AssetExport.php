<?php

namespace App\Exports;

use App\Models\Asset;
use App\Models\AssetNextApprover;
use App\Models\Delegation;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetExport implements FromCollection, WithHeadings {

	protected $request, $user;
	function __construct($request, $user) {
		$this->request = $request;
		$this->user = $user;
	}

    public function collection() {
    	$request = $this->request;
    	$user = $this->user;
    	$query = Asset::selectRaw("id,code,name,register_date,type,category,ownership,commissioning_date,department_name,company_name,remarks,status,approval_status,(CASE WHEN next_action = 'APPROVAL' THEN CONCAT(next_action,' LEVEL ',approval_level) ELSE next_action END) as next_action,next_user_name,updated_at");
        $action = !empty($request->action) ? strtoupper($request->action) : '';
        if($action=='MONITORING') {
            //
        } else if($action=='DEPARTMENT_MONITORING') {
            $res = User::where('department_id', $user->department_id)->get(['id']);
            $list_user_id = [];
            foreach($res as $v){
                $list_user_id[] = $v->id;
            }
            $query->whereIn('requestor_id', $list_user_id);
        } else if($action=='APPROVAL') {
            $user_ids = [$user->id];
            $delegation = Delegation::where('type', 'ALL')
                ->where('delegatee', $user->id)
                ->where('begin_date', '<=', date('Y-m-d'))
                ->where('end_date', '>=', date('Y-m-d'))
                ->first();
            if($delegation!=null){
                $user_ids[] = $delegation->delegator;
            }
            $res = AssetNextApprover::whereIn('user_id', $user_ids)->get(['asset_id']);
            $ids = [];
            foreach($res as $v){
                $ids[] = $v->asset_id;
            }
            $query->whereIn('approval_status', ['APPROVAL_REQUIRED'])->whereIn('id', $ids);
        } else if($action=='APPROVAL_HISTORY') {
            $query->whereRaw("(id IN (SELECT DISTINCT asset_id FROM asset_logs WHERE (user_id = ".$user->id." OR delegator_uid = ".$user->id.") AND event NOT IN ('CREATE', 'UPDATE')))");
        } else {
            $query->where('requestor_id', $user->id);
        }
        if($request->filled('register_date')){
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
        return $query->get();
    }

    public function headings(): array {
    	return ['ID','Code','Name','Register Date','Type','Category','Ownership','Commisioning Date','Department','Company','Remarks','Status','Approval Status','Next Action', 'Next User', 'Last Updated Date'];
    }
}