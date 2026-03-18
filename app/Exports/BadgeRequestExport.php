<?php

namespace App\Exports;

use App\Models\BadgeRequest;
use App\Models\BadgeRequestNextApprover;
use App\Models\Delegation;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BadgeRequestExport implements FromCollection, WithHeadings {

	protected $request, $user;
	function __construct($request, $user) {
		$this->request = $request;
		$this->user = $user;
	}

    public function collection() {
    	$request = $this->request;
    	$user = $this->user;
    	$query = BadgeRequest::selectRaw("badge_requests.id,badge_requests.request_no,badge_requests.request_date,badge_requests.company_name,badge_requests.location,badge_requests.status,badge_requests.requestor_name, brl.employee_id, brl.citizen_id, brl.name, brl.title, brl.status as emp_status, brl.contract_start_date, brl.contract_end_date, brl.active_period")
    	->join('badge_request_lines as brl', 'brl.request_id', '=', 'badge_requests.id');
        $action = !empty($request->action) ? strtoupper($request->action) : '';
        if($action=='MONITORING') {
            //
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
            $res = BadgeRequestNextApprover::whereIn('user_id', $user_ids)->get(['request_id']);
            $ids = [];
            foreach($res as $v){
                $ids[] = $v->request_id;
            }
            $query->whereIn('badge_requests.status', ['APPROVAL_REQUIRED'])->whereIn('badge_requests.id', $ids);
        } else if($action=='PRINTING') {
            $user_ids = [$user->id];
            $delegation = Delegation::where('type', 'ALL')
                ->where('delegatee', $user->id)
                ->where('begin_date', '<=', date('Y-m-d'))
                ->where('end_date', '>=', date('Y-m-d'))
                ->first();
            if($delegation!=null){
                $user_ids[] = $delegation->delegator;
            }
            $res = BadgeRequestNextApprover::whereIn('user_id', $user_ids)->get(['request_id']);
            $ids = [];
            foreach($res as $v){
                $ids[] = $v->request_id;
            }
            $query->whereIn('badge_requests.status', ['WAITING_TO_PRINT'])->whereIn('badge_requests.id', $ids);
        } else if($action=='APPROVAL_HISTORY') {
            $query->whereRaw("(badge_requests.id IN (SELECT DISTINCT request_id FROM badge_request_logs WHERE (user_id = ".$user->id." OR delegator_uid = ".$user->id.") AND event NOT IN ('CREATE', 'UPDATE')))");
        } else {
            $query->where('badge_requests.requestor_id', $user->id);
        }
        if(!empty($request->request_date)){
            $request_date   = explode(' - ', $request->request_date);
            $request_date_1 = date('Y-m-d', strtotime($request_date[0]));
            $request_date_2 = date('Y-m-d', strtotime($request_date[1]));
            $query->whereBetween('badge_requests.request_date', [$request_date_1, $request_date_2]);
        }
        if(!empty($request->request_no)){
            $query->whereRaw("badge_requests.request_no LIKE '%".$request->request_no."%'");
        }
        if(!empty($request->requestor_name)){
            $query->whereRaw("badge_requests.requestor_name LIKE '%".$request->requestor_name."%'");
        }
        if(!empty($request->company_name)){
            $query->whereRaw("badge_requests.company_name LIKE '%".$request->company_name."%'");
        }
        if(isset($request->status)){
            $query->whereIn('badge_requests.status', $request->status);
        }
        return $query->get();
    }

    public function headings(): array {
    	return ['ID', 'Request No', 'Request Date', 'Company', 'Location', 'Status', 'Requestor', 'Employee ID', 'Citizen ID', 'Name', 'Title', 'Status', 'Contract Start Date', 'Conctract End Date', 'Active Period'];
    }
}