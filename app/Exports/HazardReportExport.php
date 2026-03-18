<?php

namespace App\Exports;

use App\Models\Hazard;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HazardReportExport implements FromCollection, WithHeadings {

	protected $request;
	function __construct($request) {
		$this->request = $request;
	}

    public function collection() {
    	$request = $this->request;
    	$query = Hazard::selectRaw("id, no, report_datetime, reporter_name, reporter_department_name, location, hazard_source, hazard_type, hazard_description, immediate_actions, action_taken, due_date, completed_date, assignee_name, assignee_department_name, status");
        if(!empty($request->report_date)){
            $report_date = explode(' - ', $request->report_date);
            $report_date_1 = date('Y-m-d 00:00:00', strtotime($report_date[0]));
            $report_date_2 = date('Y-m-d 23:59:59', strtotime($report_date[1]));
            $query->whereBetween('report_datetime', [$report_date_1, $report_date_2]);
        }
        if(!empty($request->location)){
            $query->whereRaw("location LIKE '%".$request->location."%'");
        }
        if(!empty($request->reporter_name)){
            $query->whereRaw("reporter_name LIKE '%".$request->reporter_name."%'");
        }
        if(!empty($request->reporter_department_name)){
            $query->whereRaw("reporter_department_name LIKE '%".$request->reporter_department_name."%'");
        }
        if(isset($request->status)){
            $query->whereIn('status', $request->status);
        }
        return $query->get();
    }

    public function headings(): array {
    	return ['ID', 'No', 'Date/Time', 'Reporter', 'ReporterDepartment', 'Location', 'Source','Type','Description','ImmediateActions','ActionTaken','DueDate','CompletedDate', 'Assignee', 'AssigneeDepartment', 'Status'];
    }
}