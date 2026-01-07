<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VoiceCall;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
class UserProfileController extends Controller
{
    // Constants for Final_Status values
    const STATUS_RESOLVED = '1';
    const STATUS_SUBMITTED = '2';
    const STATUS_ESCALATED = '3';

    /**
     * Show the dashboard page with initial data.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // All calls handled by this user, latest first
        $calls = VoiceCall::where('handled_by_user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        $totalCalls = $calls->count();

        $todayCalls = VoiceCall::where('handled_by_user_id', $user->id)
                        ->whereDate('created_at', now()->toDateString())
                        ->count();

        // Calls per hour for today (array with 24 elements)
        $callsPerHourRaw = VoiceCall::select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
                        ->where('handled_by_user_id', $user->id)
                        ->whereDate('created_at', now()->toDateString())
                        ->groupBy('hour')
                        ->pluck('count', 'hour');

        $callsPerHour = [];
        for ($i = 0; $i < 24; $i++) {
            $callsPerHour[$i] = $callsPerHourRaw->get($i, 0);
        }

        // Status counts for doughnut chart
        $statusCountsRaw = VoiceCall::select(
            DB::raw("SUM(CASE WHEN Final_Status = '" . self::STATUS_RESOLVED . "' THEN 1 ELSE 0 END) as Resolved"),
            DB::raw("SUM(CASE WHEN Final_Status = '" . self::STATUS_SUBMITTED . "' THEN 1 ELSE 0 END) as Submitted"),
            DB::raw("SUM(CASE WHEN Final_Status = '" . self::STATUS_ESCALATED . "' THEN 1 ELSE 0 END) as Escalated")
        )->where('handled_by_user_id', $user->id)->first();

        $statusCounts = [
            'Resolved' => $statusCountsRaw->Resolved ?? 0,
            'Submitted' => $statusCountsRaw->Submitted ?? 0,
            'Escalated' => $statusCountsRaw->Escalated ?? 0,
        ];

        // Return your dashboard view (adjust view path if needed)
        return view('user_profile.dashboard', [
            'user' => $user,
            'calls' => $calls,
            'totalCalls' => $totalCalls,
            'todayCalls' => $todayCalls,
            'callsPerHour' => array_values($callsPerHour),
            'statusCounts' => $statusCounts,
        ]);
    }

    /**
     * Return JSON data for the user profile including calls per hour for the chart.
     */
    public function profileData($userId)
    {
        // Security check
        if (auth()->id() != $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = auth()->user();

        // All calls handled by user, newest first
        $calls = VoiceCall::where('handled_by_user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalCalls = $calls->count();

        // Calls today count
        $todayCalls = VoiceCall::where('handled_by_user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // Calls per hour for today (0-23)
        $callsPerHourRaw = VoiceCall::select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->where('handled_by_user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->groupBy('hour')
            ->pluck('count', 'hour');

        $callsPerHour = [];
        for ($i = 0; $i < 24; $i++) {
            $callsPerHour[$i] = $callsPerHourRaw->get($i, 0);
        }

        // Status counts for doughnut chart
        $statusCountsRaw = VoiceCall::select(
                DB::raw("SUM(CASE WHEN Final_Status = '" . self::STATUS_RESOLVED . "' THEN 1 ELSE 0 END) as Resolved"),
                DB::raw("SUM(CASE WHEN Final_Status = '" . self::STATUS_SUBMITTED . "' THEN 1 ELSE 0 END) as Submitted"),
                DB::raw("SUM(CASE WHEN Final_Status = '" . self::STATUS_ESCALATED . "' THEN 1 ELSE 0 END) as Escalated")
            )
            ->where('handled_by_user_id', $userId)
            ->first();

        $statusCounts = [
            'Resolved' => $statusCountsRaw->Resolved ?? 0,
            'Submitted' => $statusCountsRaw->Submitted ?? 0,
            'Escalated' => $statusCountsRaw->Escalated ?? 0,
        ];

        return response()->json([
            'user' => $user,
            'calls' => $calls,
            'totalCalls' => $totalCalls,
            'todayCalls' => $todayCalls,
            'callsPerHour' => array_values($callsPerHour),
            'statusCounts' => $statusCounts,
        ]);
    }
public function search(Request $request)
    {
        $userId = auth()->id();

        $query = VoiceCall::where('handled_by_user_id', $userId);

        // Filter by date range (created_at)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Filter by individual fields (if provided)
        $filterFields = [
            'ticket_number', 'customer_type', 'parent_name',
            'stud_id', 'staff_ID', 'category'
        ];

        foreach ($filterFields as $field) {
            if ($request->filled($field)) {
                // Use LIKE for text-based filtering (case insensitive)
                $query->where($field, 'like', '%' . $request->input($field) . '%');
            }
        }

        // Optional: You can also filter by Final_Status or other fields here if needed

        $results = $query->orderBy('created_at', 'desc')->get();

        // Map categories - adjust as per your categories data
        $categoryMap = $this->getCategoriesMap();

        // Map final status values - adjust keys if different
        $finalStatusMap = [
            '1' => 'Resolved',
            '2' => 'Submitted',
            '3' => 'Escalated',
        ];

        $mappedResults = $results->map(function ($call) use ($categoryMap, $finalStatusMap) {
            return [
                'call_id' => $call->call_id,
                'ticket_number' => $call->ticket_number,
                'customer_type' => $call->customer_type,
                'parent_name' => $call->parent_name,
                'stud_id' => $call->stud_id,
                'staff_ID' => $call->staff_ID,
                'category' => $categoryMap[$call->category] ?? "Unknown ({$call->category})",
                'issue' => $call->issue,
                'Final_Status' => $finalStatusMap[$call->Final_Status] ?? ($call->Final_Status ?? 'Unknown'),
                'created_at' => $call->created_at ? Carbon::parse($call->created_at)->format('Y-m-d H:i:s') : null,
            ];
        });

        return response()->json($mappedResults);
    }

    /**
     * Return your category map here.
     */
    private function getCategoriesMap()
    {
        return [
            1 => '[Certificates] Graduates Lists',
            2 => '[Certificates] Delayed Issuance',
            3 => '[Certificates] Payment Issues',
            4 => '[Certificates] General',
            5 => '[Finance] Delayed Approval',
            6 => '[Academic] Delayed Approval',
            7 => '[Academic] Delayed Result',
            8 => '[Academic] Registration',
            9 => '[Academic] Verification',
            10 => '[Academic] F/z result',
            11 => '[Academic] Postgraduate',
            12 => '[Academic] remarking',
            13 => '[E-Learning] Account Activation',
            14 => '[E-Learning] F/Z Course Enrolment',
            15 => '[E-Learning] Wrong Courses',
            16 => '[HelpDesk] Password Reset',
            17 => '[General Inquiry] New Admission',
            18 => '[General Inquiry] General',
            19 => '[General inquiry] others',
            20 => '[General inquiry] Complaint',
            21 => '[Addmission] Internal/external transfer/تجسير',
            22 => '[CTS] SMOWL issues',
            23 => '[CTS] Exam Access',
            24 => '[CTS] Close ticket /OPEN',
            25 => '[CESD] Index issuing',
        ];
    }
public function callArchive()
{
    return view('dashboard.call_archive');
}
   public function updateStatus(Request $request)
{
    try {
        $request->validate([
            // Use call_id for the search as it is the absolute unique primary key
            'call_id' => 'required|exists:voice_calls,call_id', 
            'final_status' => 'required|string|in:Resolved,Submitted,Escalated',
            'status_note' => 'nullable|string',
        ]);

        $userId = auth()->id();

        // Find the specific row by its ID
        $call = VoiceCall::find($request->call_id);

        if (!$call) {
            return response()->json(['error' => 'Call record not found'], 404);
        }

        // Authorization check
        if ($call->handled_by_user_id !== $userId) {
            return response()->json(['error' => 'You do not have permission to update this call'], 403);
        }

        $statusMap = [
            'Resolved' => 4,
            'Submitted' => 2,
            'Escalated' => 3,
        ];

        $finalStatusCode = $statusMap[$request->final_status] ?? null;

        // Update the fields
        $call->Final_Status = $finalStatusCode;
        $call->status_update_note = $request->status_note;
        $call->updated_by = $userId;
        // updated_at is handled automatically by Laravel, but manual is fine too

        $call->save();

        return response()->json(['message' => 'Status updated successfully for Call ID: ' . $call->call_id]);
    } catch (\Exception $ex) {
        return response()->json(['error' => $ex->getMessage()], 500);
    }
}

// You can add more methods below if needed (like search, reports, etc.)
}

