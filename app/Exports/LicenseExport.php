<?php

namespace App\Exports;

use App\Models\License;
use App\Models\LicenseNextApprover;
use App\Models\Delegation;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LicenseExport implements FromCollection, WithHeadings {

	protected $request, $user;
	function __construct($request, $user) {
		$this->request = $request;
		$this->user = $user;
	}

    public function collection() {
    	$request = $this->request;
    	$user = $this->user;
    	$query = License::selectRaw("id,no,date,type,employee_id,name,position,department_name,company_name,remarks,license_status,status,(CASE WHEN next_action = 'APPROVAL' THEN CONCAT(next_action,' LEVEL ',approval_level) ELSE next_action END) as next_action, (SELECT GROUP_CONCAT(user_name) FROM license_next_approvers na WHERE na.license_id = licenses.id) as next_user, updated_at");
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
            $res = LicenseNextApprover::whereIn('user_id', $user_ids)->get(['license_id']);
            $ids = [];
            foreach($res as $v){
                $ids[] = $v->license_id;
            }
            $query->whereIn('status', ['APPROVAL_REQUIRED'])->whereIn('id', $ids);
        } else if($action=='APPROVAL_HISTORY') {
            $query->whereRaw("(id IN (SELECT DISTINCT license_id FROM license_logs WHERE (user_id = ".$user->id." OR delegator_uid = ".$user->id.") AND event NOT IN ('CREATE', 'UPDATE')))");
        } else {
            $query->where('requestor_id', $user->id);
        }
        if(!empty($request->request_date)){
            $request_date   = explode(' - ', $request->request_date);
            $request_date_1 = date('Y-m-d', strtotime($request_date[0]));
            $request_date_2 = date('Y-m-d', strtotime($request_date[1]));
            $query->whereBetween('request_date', [$request_date_1, $request_date_2]);
        }
        if(!empty($request->request_no)){
            $query->whereRaw("request_no LIKE '%".$request->request_no."%'");
        }
        if(!empty($request->requestor_name)){
            $query->whereRaw("requestor_name LIKE '%".$request->requestor_name."%'");
        }
        if(!empty($request->employee_id)){
            $query->whereRaw("employee_id LIKE '%".$request->employee_id."%'");
        }
        if(!empty($request->name)){
            $query->whereRaw("name LIKE '%".$request->name."%'");
        }
        if(isset($request->status)){
            $query->whereIn('status', $request->status);
        }
        return $query->get();
    }

    public function headings(): array {
    	return ['ID','No','Date','Type','Employee ID','Name','Position','Department','Company','Remarks','License Status','Approval Status','Next Action', 'Next User', 'Last Updated Date'];
    }
}