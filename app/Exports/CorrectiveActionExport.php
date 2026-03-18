<?php

namespace App\Exports;

use App\Models\CorrectiveAction;
use App\Models\CorrectiveActionNextApprover;
use App\Models\Delegation;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CorrectiveActionExport implements FromCollection, WithHeadings {

	protected $request, $user;
	function __construct($request, $user) {
		$this->request = $request;
		$this->user = $user;
	}

    public function collection() {
    	$request = $this->request;
    	$user = $this->user;
    	$query = CorrectiveAction::selectRaw("id,(CASE `source` WHEN 'HZ' THEN 'HAZARD' WHEN 'IN' THEN 'INCIDENT' WHEN 'WC' THEN CONCAT('WORKPLACE CONTROL - ',REPLACE((SELECT wpc.type FROM workplace_controls wpc WHERE wpc.id = corrective_actions.source_id), '_', ' ')) WHEN 'AUD' THEN 'AUDIT' WHEN 'MTG' THEN 'MEETING' WHEN 'OTH' THEN 'OTHER' ELSE `source` END) as source,source_no,risk_issuer_name,risk_issue_date,risk_description,location,department_name,responsible_person_name,corrective_action,action_taken,due_date,status");
        $action = !empty($request->action) ? strtoupper($request->action) : '';
        if($action=='MONITORING') {
            //
        } else if($action=='DEPARTMENT_MONITORING') {
            $res = User::where('department_id', $user->department_id)->get(['id']);
            $list_user_id = [];
            foreach($res as $v){
                $list_user_id[] = $v->id;
            }
            $query->whereIn('risk_issuer_id', $list_user_id);
        } else if($action=='TO_DO') {
            $user_ids = [$user->id];
            $delegation = Delegation::where('type', 'ALL')
                ->where('delegatee', $user->id)
                ->where('begin_date', '<=', date('Y-m-d'))
                ->where('end_date', '>=', date('Y-m-d'))
                ->first();
            if($delegation!=null){
                $user_ids[] = $delegation->delegator;
            }
            $res = User::whereRaw("CONCAT(department_id,'-',company_id) IN (SELECT CONCAT(department_id,'-',company_id) FROM users WHERE id IN (".implode(',',$user_ids)."))")
                ->get(['id']);
            foreach($res as $v){
                $user_ids[] = $v->id;
            }
            $res = CorrectiveActionNextApprover::whereIn('user_id', $user_ids)->get(['action_id']);
            $ids = [];
            foreach($res as $v){
                $ids[] = $v->action_id;
            }
            $query->whereIn('status', ['ACTION_REQUIRED'])->whereIn('id', $ids);
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
            $res = CorrectiveActionNextApprover::whereIn('user_id', $user_ids)->get(['action_id']);
            $ids = [];
            foreach($res as $v){
                $ids[] = $v->action_id;
            }
            $query->whereIn('status', ['APPROVAL_REQUIRED'])->whereIn('id', $ids);
        } else if($action=='APPROVAL_HISTORY') {
            $query->whereRaw("(id IN (SELECT DISTINCT action_id FROM corrective_action_logs WHERE (user_id = ".$user->id." OR delegator_uid = ".$user->id.") AND event NOT IN ('CREATE', 'UPDATE')))");
        } else {
            $query->where('risk_issuer_id', $user->id);
        }
        if(!empty($request->request_date)){
            $request_date   = explode(' - ', $request->request_date);
            $request_date_1 = date('Y-m-d', strtotime($request_date[0]));
            $request_date_2 = date('Y-m-d', strtotime($request_date[1]));
            $query->whereBetween('event_date', [$request_date_1, $request_date_2]);
        }
        if(!empty($request->request_no)){
            $query->whereRaw("no LIKE '%".$request->request_no."%'");
        }
        if(!empty($request->requestor_name)){
            $query->whereRaw("reporter_name LIKE '%".$request->requestor_name."%'");
        }
        if(isset($request->status)){
            $query->whereIn('status', $request->status);
        }
        return $query->get();
    }

    public function headings(): array {
    	return ['ID','Source','Source No','Risk Issuer','Risk Issue Date','Risk Description','Location','Department','Responsible Person','Corrective Action','Action Taken','Due Date','Status'];
    }
}