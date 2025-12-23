<?php

namespace App\Http\Controllers;
// app/Http/Controllers/SearchController.php


use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Ticket;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->input('query'); // رقم الطالب أو التذكرة

        // البحث عن الطالب
        $student = Student::where('stud_id', $search)->first();

        // البحث عن التذكرة
        $ticket = Ticket::where('ticket_number', $search)->first();

        if ($student) {
            return response()->json([
                'type' => 'student',
                'data' => $student
            ]);
        } elseif ($ticket) {
            return response()->json([
                'type' => 'ticket',
                'data' => $ticket
            ]);
        } else {
            return response()->json(['message' => 'لم يتم العثور على نتائج'], 404);
        }
    }
}
