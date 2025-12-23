<?php

namespace App\Http\Controllers;
use App\Models\ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class TicketController extends Controller
{
    public function view(Request $request)
{
    $ticketNumber = $request->ticket_number;

    // You can now fetch the ticket or pass it to a view
    return view('student', compact('ticketNumber'));
}
public function search($ticketId)
{
    // استعلام باستخدام قاعدة بيانات 'mysql_Hdesk' لجلب التذكرة
    $ticket = DB::connection('mysql_Hdesk')  // تحديد الاتصال بقاعدة بيانات التذاكر
        ->table('hesk_tickets')               // تحديد الجدول
        ->where('id', $ticketId)              // شرط البحث على رقم التذكرة
        ->first();                            // جلب أول نتيجة

    if (!$ticket) {
        // إذا كانت التذكرة غير موجودة
        return response()->json([
            'status' => 'error',
            'message' => 'التذكرة غير موجودة'
        ], 404);
    }

    // استرجاع البيانات الخاصة بالطالب من قاعدة بيانات 'mysql_sis2' باستخدام join
    $student = DB::connection('mysql_sis2')
        ->table('student_profile_e as sp')
        ->join('student_profile_common as spc', 'spc.stud_id', '=', 'sp.stud_id')
        ->join('faculty as f', 'f.faculty_code', '=', 'spc.faculty_code')
        ->join('major as m', function($join) {
            $join->on('m.major_code', '=', 'spc.major_code')
                 ->on('m.faculty_code', '=', 'f.faculty_code');
        })
        ->where('sp.stud_id', $ticket->custom1)  // استخدام stud_id من التذكرة
        ->select([
            DB::raw("CASE 
                        WHEN spc.status_code = 1 THEN 'Active'
                        WHEN spc.status_code = 0 THEN 'Not Active'
                        ELSE 'No Data'
                    END as status_code"),
            'm.abbreviation as major_code',
            'f.abbreviation as faculty_code',
            'sp.stud_id',
            'sp.stud_name',
            'sp.stud_surname',
            'sp.familyname',
            'sp.lastName',
            'spc.batch',
            'spc.curr_sem',
        ])
        ->first();  // جلب أول نتيجة
 
    if (!$student) {
        // إذا كانت بيانات الطالب غير موجودة
        return response()->json([
            'status' => 'error',
            'message' => 'الطالب غير موجود'
        ], 404);
    }

    // حفظ بيانات التذكرة والطالب في السيشن
    session([
        'ticket_id' => $ticket->id,
        'student_id' => $student->stud_id,
        'student_name' => $student->stud_name,
        'student_surname' => $student->stud_surname,
        'status_code' => $student->status_code,  // تخزين حالة الطالب
        'major_code' => $student->major_code,    // تخزين تخصص الطالب
        'faculty_code' => $student->faculty_code, // تخزين كلية الطالب
      //  'ticket_number'=>$ticket->ticket_number,
        
    ]);

    // إرجاع استجابة JSON تتضمن التذكرة والطالب
    return response()->json([
        'status' => 'success',
        'ticket' => $ticket,
        'student' => $student
    ]);
}


    }
 
