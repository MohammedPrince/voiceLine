<?php

 
namespace App\Http\Controllers;
use App\Models\Category;

use Illuminate\Http\Request;
use App\Models\VoiceCall;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    
    public function getReportData(Request $request)
    {
        //  $query = DB::table('voice_calls')
        //     ->join('category', 'voice_calls.category', '=', 'category.id') // الربط مع جدول category
        //     ->select(
        //         'category.name as category', 
        //         DB::raw('COUNT(voice_calls.call_id) as total')
        //     );
$query = DB::table('voice_calls')
        ->select('category', DB::raw('COUNT(call_id) as total'));
        // فلترة حسب period
        if ($request->filled('period')) {
            $period = $request->period;
            switch ($period) {
                case 'today':
                    $query->whereDate('voice_calls.created_at', Carbon::today());
                    break;
                case 'last7':
                    $query->whereBetween('voice_calls.created_at', [Carbon::today()->subDays(6), Carbon::today()]);
                    break;
                case 'last30':
                    $query->whereBetween('voice_calls.created_at', [Carbon::today()->subDays(29), Carbon::today()]);
                    break;
                case 'thisMonth':
                    $query->whereMonth('voice_calls.created_at', Carbon::now()->month)
                          ->whereYear('voice_calls.created_at', Carbon::now()->year);
                    break;
                case 'lastMonth':
                    $lastMonth = Carbon::now()->subMonth();
                    $query->whereMonth('voice_calls.created_at', $lastMonth->month)
                          ->whereYear('voice_calls.created_at', $lastMonth->year);
                    break;
            }
        }

        // فلترة حسب تاريخ مخصص
        if ($request->filled('startDate') && $request->filled('endDate')) {
            $query->whereBetween('voice_calls.created_at', [$request->startDate, $request->endDate]);
        }

        // تجميع حسب category
        // $data = $query->groupBy('category.id', 'category.name')->get();
$results = $query->groupBy('category')->get();
// Map the IDs to Names
    $categoryMap = $this->getCategoriesMap();

    $data = $results->map(function ($item) use ($categoryMap) {
        return [
            // If the ID exists in th map, use the name. Otherwise, show the ID.
            'category' => $categoryMap[$item->category] ?? 'Other/Unknown (' . $item->category . ')',
            'total' => $item->total
        ];
    });
        return response()->json($data);
    }
     
    
    // Private helper to keep the list organized
private function getCategoriesMap()
{
   return [
    "1"  => "[Certificates] Graduates Lists",
    "2"  => "[Certificates] Delayed Issuance",
    "3"  => "[Certificates] Payment Issues",
    "4"  => "[Certificates] General",

    "5"  => "[Finance] Delayed Approval",

    "6"  => "[Academic] Delayed Approval",
    "7"  => "[Academic] Delayed Result",
    "8"  => "[Academic] Registration",
    "9"  => "[Academic] Verification",
    "10" => "[Academic] F/z result",
    "11" => "[Academic] Postgraduate",
    "12" => "[Academic] remarking",

    "13" => "[E-Learning] Account Activation",
    "14" => "[E-Learning] F/Z Course Enrolment",
    "15" => "[E-Learning] Wrong Courses",

    "16" => "[HelpDesk] Password Reset",

    "17" => "[General Inquiry] New Admission",
    "18" => "[General Inquiry] General",
    "19" => "[General inquiry] others",
    "20" => "[General inquiry] Complaint",

    "21" => "[Addmission] Internal/external transfer/تجسير",

    "22" => "[CTS] SMOWL issues",
    "23" => "[CTS] Exam Access",
    "24" => "[CTS] Close ticket /OPEN",

    "25" => "[CESD] Index issuing",
];
}
 


    public function index()
    {
        return view('dashboard');
    }

    public function getDashboardData(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // as khld request >> report User Performance 
        $userPerformance = VoiceCall::select(
                'handled_by_user_id',
                DB::raw('COUNT(*) as total_calls'),
                DB::raw('SUM(CASE WHEN Final_Status = "Completed" THEN 1 ELSE 0 END) as resolved_calls'),
                DB::raw('SUM(CASE WHEN Final_Status = "In Progress" THEN 1 ELSE 0 END) as in_progress_calls'),
                DB::raw('SUM(CASE WHEN Final_Status = "Pending" THEN 1 ELSE 0 END) as pending_calls'),
                DB::raw('SUM(CASE WHEN Final_Status = "Waiting Approval" THEN 1 ELSE 0 END) as waiting_approval_calls')
            )
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereNotNull('handled_by_user_id')
            ->groupBy('handled_by_user_id')
            ->with('handler')
            ->get();

        $statusDistribution = VoiceCall::select(
                'Final_Status',
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereNotNull('Final_Status')
            ->groupBy('Final_Status')
            ->get();

         $categoryDistribution = VoiceCall::join('category', 'voice_calls.category', '=', 'category.id')
            ->select(
                'category.name as category_name',
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('voice_calls.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('category.id', 'category.name')
            ->get();

         $dailyCalls = VoiceCall::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as call_count')
            )
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'userPerformance' => $userPerformance,
            'statusDistribution' => $statusDistribution,
            'categoryDistribution' => $categoryDistribution,
            'dailyCalls' => $dailyCalls,
            'dateRange' => [
                'start' => $startDate,
                'end' => $endDate
            ]
        ]);
    } 
   
    public function callsPerUser()
{
    $report = DB::table('voice_calls as v')
            ->join('users as u', 'u.id', '=', 'v.handled_by_user_id')
            ->select(
                'u.name',
                DB::raw('COUNT(*) AS Received_Calls'),
                DB::raw("SUM(CASE WHEN v.Final_Status = '" . self::STATUS_RESOLVED . "' THEN 1 ELSE 0 END) AS Resolved"),
                DB::raw("SUM(CASE WHEN v.Final_Status = '" . self::STATUS_ESCALATED . "' THEN 1 ELSE 0 END) AS Escalated"),
                DB::raw("SUM(CASE WHEN v.Final_Status = '" . self::STATUS_SUBMITTED . "' THEN 1 ELSE 0 END) AS Submitted"),
              /*  DB::raw("SUM(CASE WHEN v.Final_Status = 'In Progress' THEN 1 ELSE 0 END) AS In_Progress"),
                DB::raw("SUM(CASE WHEN v.Final_Status = 'Waiting Approval' THEN 1 ELSE 0 END) AS Waiting_Approval"),
                DB::raw("SUM(CASE WHEN v.Final_Status = 'Under Review' THEN 1 ELSE 0 END) AS Under_Review"),
                */
                DB::raw("SUM(CASE WHEN v.Final_Status IS NULL THEN 1 ELSE 0 END) AS No_data"),
                DB::raw("SUM(CASE WHEN v.priority IS NOT NULL AND v.created_at <> v.updated_at THEN 1 ELSE 0 END
                ) AS Priority_Changed")
           
            )
            ->groupBy('v.handled_by_user_id', 'u.name')
            ->get();
 //dd($report);
        // تمرير المتغير للـ view
        return view('reports.dashboard', compact('report'));
    }
// the numbers stored by the dropdown
const STATUS_RESOLVED = '1';
const STATUS_SUBMITTED = '2';
const STATUS_ESCALATED = '3';

public function dashboardData(Request $request)
{
    
    $query = DB::table('voice_calls as v')
        ->join('users as u', 'u.id', '=', 'v.handled_by_user_id')
        ->select(
            'u.name',
            DB::raw('COUNT(*) AS Received_Calls'),
             DB::raw("SUM(CASE WHEN v.Final_Status = '" . self::STATUS_RESOLVED . "' THEN 1 ELSE 0 END) AS Resolved"),
                DB::raw("SUM(CASE WHEN v.Final_Status = '" . self::STATUS_SUBMITTED . "' THEN 1 ELSE 0 END) AS Submitted"),
                DB::raw("SUM(CASE WHEN v.Final_Status = '" . self::STATUS_ESCALATED . "' THEN 1 ELSE 0 END) AS Escalated"),
              /*  DB::raw("SUM(CASE WHEN v.Final_Status = 'In Progress' THEN 1 ELSE 0 END) AS In_Progress"),
                DB::raw("SUM(CASE WHEN v.Final_Status = 'Waiting Approval' THEN 1 ELSE 0 END) AS Waiting_Approval"),
                DB::raw("SUM(CASE WHEN v.Final_Status = 'Under Review' THEN 1 ELSE 0 END) AS Under_Review"),
                */ DB::raw("SUM(CASE WHEN v.Final_Status IS NULL THEN 1 ELSE 0 END) AS No_data"),
                DB::raw("SUM(CASE WHEN v.priority IS NOT NULL AND v.created_at <> v.updated_at THEN 1 ELSE 0 END
                ) AS Priority_Changed"));

    if ($request->period == 'week') {
        $query->where('v.created_at', '>=', now()->startOfWeek());
    } elseif ($request->period == 'month') {
        $query->where('v.created_at', '>=', now()->startOfMonth());
    } elseif ($request->period == 'last_month') {
        $query->whereBetween('v.created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth()
        ]);
    } elseif ($request->period == 'custom' && $request->from && $request->to) {
        $query->whereBetween('v.created_at', [$request->from, $request->to]);
    }

     $report = $query->groupBy('v.handled_by_user_id', 'u.name')->get();

   // Rush hour data
    $rushHourData = DB::table('voice_calls')
        ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
        ->when($request->period, function ($q, $period) use ($request) {
            if ($period == 'week') {
                $q->where('created_at', '>=', now()->startOfWeek());
            } elseif ($period == 'month') {
                $q->where('created_at', '>=', now()->startOfMonth());
            } elseif ($period == 'last_month') {
                $q->whereBetween('created_at', [
                    now()->subMonth()->startOfMonth(),
                    now()->subMonth()->endOfMonth()
                ]);
            } elseif ($period == 'custom' && $request->from && $request->to) {
                $q->whereBetween('created_at', [$request->from, $request->to]);
            }
        })
        ->groupBy('hour')
        ->orderBy('hour')
        ->get();

    return response()->json([
        'users' => $report,
        'rushHour' => $rushHourData,
    ]);
}
// public function search(Request $request)
//     {
//         $query = VoiceCall::query();

//         // البحث بالفترة الزمنية
//         if ($request->filled('start_date') && $request->filled('end_date')) {
//             $query->whereBetween('created_at', [
//                 $request->start_date . ' 00:00:00',
//                 $request->end_date . ' 23:59:59'
//             ]);
//         }

//         // البحث الديناميكي على كل الحقول
//         $searchableFields = [
//             'call_id', 'ticket_number', 'customer_type', 'stud_id', 'staff_ID',
//             'category', 'issue', 'Solution_Note', 'Found_Status', 'Final_Status',
//             'priority', 'parent_id', 'parent_name', 'parent_phone', 'handled_by_user_id'
//         ];

//         foreach ($searchableFields as $field) {
//             if ($request->filled($field)) {
//                 $query->where($field, 'like', '%' . $request->$field . '%');
//             }
//         }

//         $results = $query->get();

//         return response()->json($results);
//     }
public function search(Request $request)
{
    $query = VoiceCall::query();

    // Filter by date range
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date . ' 23:59:59'
        ]);
    }

    // Searchable fields for dynamic filtering
    $searchableFields = [
        'call_id', 'ticket_number', 'customer_type', 'stud_id', 'staff_ID',
        'category', 'issue', 'Solution_Note', 'Found_Status', 'Final_Status',
        'priority', 'parent_id', 'parent_name', 'parent_phone', 'handled_by_user_id'
    ];

    foreach ($searchableFields as $field) {
        if ($request->filled($field)) {
            $query->where($field, 'like', '%' . $request->$field . '%');
        }
    }

    $results = $query->get();

    // Your existing category map
    $categoryMap = $this->getCategoriesMap();

    // Define your Final_Status map (example, adjust keys and labels as needed)
    $finalStatusMap = [
        '1' => 'Resolved',
        '2' => 'Submitted',
        '3' => 'Escalated',
    ];

    // Map category and Final_Status IDs to their textual names
    $mappedResults = $results->map(function ($item) use ($categoryMap, $finalStatusMap) {
        return [
            'call_id' => $item->call_id,
            'ticket_number' => $item->ticket_number,
            'customer_type' => $item->customer_type,
            'parent_name' => $item->parent_name,
            // Map category id to text or fallback to raw value
            'category' => $categoryMap[$item->category] ?? 'Other/Unknown (' . $item->category . ')',
            'issue' => $item->issue,
            // Map Final_Status to text or fallback raw value
            'Final_Status' => $finalStatusMap[$item->Final_Status] ?? ($item->Final_Status ?? 'Unknown'),
             'created_at' => $item->created_at ? Carbon::parse($item->created_at)->format('Y-m-d H:i:s') : null,
             'stud_id' => $item->stud_id, 
        ];
    });

    return response()->json($mappedResults);
}

    public function voiceCallsReport()
{
    return view('reports.report');
}
}
