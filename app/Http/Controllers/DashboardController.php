<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CorrectiveAction;
use App\Models\Hazard;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $date1 = date('Y-m-d', strtotime('-1 year +1 day'));
        $date2 = date('Y-m-d');

        if (!empty($request->date_range)) {
            $date_range = explode(' to ', $request->date_range);
            if (count($date_range) === 2) {
                $date1 = date('Y-m-d', strtotime(trim($date_range[0])));
                $date2 = date('Y-m-d', strtotime(trim($date_range[1])));
            }
        }
        
        $incident_notif_count = Incident::whereBetween('report_date', [$date1, $date2])
            ->count('id');
        $incident_inv_count = Incident::whereBetween('report_date', [$date1, $date2])
            ->where('work_related', 'Yes')
            ->whereIn('status', ['INVESTIGATION_REQUIRED', 'INVESTIGATION_APPROVAL_REQUIRED', 'COMPLETED'])
            ->count('id');
        $hazard_count = Hazard::whereBetween('report_datetime', [$date1 . ' 00:00:00', $date2 . ' 23:59:59'])
            ->count('id');
        $corrective_action_count = CorrectiveAction::whereBetween('risk_issue_date', [$date1, $date2])
            ->count('id');
        $res = DB::select("SELECT DATE_FORMAT(report_datetime,'%Y-%m') as p0, DATE_FORMAT(report_datetime,'%M %Y') as p1, count(1) as c FROM `hazards` WHERE report_datetime BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59' GROUP BY p0,p1 ORDER BY p0 ASC");
        $list_hazard_grouping = ['label' => [], 'data' => []];
        foreach ($res as $k => $v) {
            $list_hazard_grouping['label'][] = $v->p1;
            $list_hazard_grouping['data'][] = $v->c;
        }
        $res = DB::select("SELECT DATE_FORMAT(report_date,'%Y-%m') as p0, DATE_FORMAT(report_date,'%M %Y') as p1, count(1) as c FROM `incidents` WHERE report_date BETWEEN '" . $date1 . "' AND '" . $date2 . "' GROUP BY p0, p1 ORDER BY p0 ASC");
        $list_incident_grouping = ['label' => [], 'data' => []];
        foreach ($res as $k => $v) {
            $list_incident_grouping['label'][] = $v->p1;
            $list_incident_grouping['data'][] = $v->c;
        }
        $list_incident = Incident::whereBetween('report_date', [$date1, $date2])
            ->orderBy('report_date', 'DESC')
            ->limit(10)
            ->get(['report_date', 'no', 'event_title', 'department_name']);
        $list_hazard = Hazard::whereBetween('report_datetime', [$date1 . ' 00:00:00', $date2 . ' 23:59:59'])
            ->orderBy('report_datetime', 'DESC')
            ->limit(10)
            ->get(['report_datetime', 'no', 'hazard_description', 'reporter_department_name']);

        $date_range = date('Y-m-d', strtotime($date1)) . ' to ' . date('Y-m-d', strtotime($date2));
        $full_date = \Carbon\Carbon::now()->translatedFormat('d F Y');

        $list_summary_CAR = DB::select("SELECT source, CASE WHEN source = 'IN' THEN 'INCIDENT' WHEN source = 'HZ' THEN 'HAZARD' WHEN source = 'WC' THEN 'WORKPLACE CONTROL' WHEN source = 'AUD' THEN 'AUDIT' WHEN source = 'MTG' THEN 'MEETING' WHEN source = 'OTH' THEN 'OTHER' ELSE '' END as source_name, SUM(CASE WHEN status != 'COMPLETED' THEN 1 ELSE 0 END) AS open, SUM(CASE WHEN status = 'COMPLETED' THEN 1 ELSE 0 END) AS complete from `corrective_actions` WHERE risk_issue_date BETWEEN ? AND ? group by `source`, `source_name`", [$date1, $date2]);

        return view('dashboard', [
            'incident_notif_count'      => $incident_notif_count,
            'incident_inv_count'        => $incident_inv_count,
            'hazard_count'              => $hazard_count,
            'corrective_action_count'   => $corrective_action_count,
            'date1'                     => $date1,
            'date2'                     => $date2,
            'date_range'                => $date_range,
            'full_date'                 => $full_date,
            'list_incident'             => $list_incident,
            'list_hazard'               => $list_hazard,
            'list_incident_grouping'    => json_encode($list_incident_grouping, JSON_UNESCAPED_SLASHES),
            'list_hazard_grouping'        => json_encode($list_hazard_grouping, JSON_UNESCAPED_SLASHES),
            'list_summary_CAR'          => $list_summary_CAR
        ]);
    }
}
