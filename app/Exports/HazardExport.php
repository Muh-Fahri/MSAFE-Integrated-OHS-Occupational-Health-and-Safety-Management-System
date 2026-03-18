<?php

namespace App\Exports;

use App\Models\Hazard;
use App\Models\HazardNextApprover;
use App\Models\Delegation;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HazardExport implements FromCollection, WithHeadings {

	protected $request, $user;
	function __construct($request, $user) {
		$this->request = $request;
		$this->user = $user;
	}

    public function collection() {
    	$request = $this->request;
    	$user = $this->user;
    	$query = Hazard::selectRaw("id,no,report_datetime,hazard_source,hazard_type,location,status,reporter_name,remarks,status,(CASE WHEN next_action = 'APPROVAL' THEN CONCAT(next_action,' LEVEL ',approval_level) ELSE next_action END) as next_action,updated_at");
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
        if(isset($request->status)){
            $query->whereIn('status', $request->status);
        }
        return $query->get();
    }

    public function headings(): array {
    	return ['ID','No','Date/Time','Source','Type','Location','Status','Reporter','Remarks','Next Action', 'Last Updated Date'];
    }
}