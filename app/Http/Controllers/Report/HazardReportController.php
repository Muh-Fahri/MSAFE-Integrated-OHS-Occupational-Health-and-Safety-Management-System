<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Hazard;
use Illuminate\Http\Request;

class HazardReportController extends Controller
{
    public function index(Request $request)
    {
        $date1 = date('Y-m-d', strtotime('-1 year +1 day'));
        $date2 = date('Y-m-d');
        $query = Hazard::query();
        $date_range = $date1 . ' to ' . $date2;
        if ($request->filled('date_range')) {
            $date_range = $request->date_range;
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('report_datetime', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            }
        }


        if ($request->filled('no')) {
            $query->whereRaw("no LIKE '%" . $request->no . "%'");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('reporter_name')) {
            $query->where('reporter_name', 'like', '%' . $request->reporter_name . '%');
        }

        if ($request->filled('company_name')) {
            $query->where('company_name', 'like', '%' . $request->company_name . '%');
        }
        $hazards = $query->latest()->paginate(10)->withQueryString();
        return view('report.hazard_report', compact('hazards', 'date_range'));
    }
}
