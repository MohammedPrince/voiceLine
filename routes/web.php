<?php

use  App\Http\Controllers\{
    StudentController,
    DashboardRedirectController,TicketController,
    AdminDashboardController,
    SupervisorDashboardController,ProfileController,
    UserDashboardController,CallController,ReportController,
    AuthenticatedSessionController,
    UserProfileController
};
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
 use Illuminate\Support\Facades\Log;




Route::get('/', function () {
    return view('auth.login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
      Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});

require __DIR__.'/auth.php';

// بعد تسجيل الدخول: التوجيه حسب الدور
Route::get('/dashboard', DashboardRedirectController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile-data/{id}', [UserProfileController::class, 'profileData'])
        ->name('profile.data');
});

// لوحات الأدوار
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/supervisor/dashboard', [SupervisorDashboardController::class, 'index'])
        ->middleware('role:supervisor')
        ->name('supervisor.dashboard');

    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])
        ->middleware('role:user')
        ->name('user.dashboard');
});
// student section
 Route::get('/student', function () {
    return view('student');
})->name('student');

Route::post('/student-view', [StudentController::class, 'studentView'])->name('studentview');

// routes/web.php


Route::get('/search', [SearchController::class, 'search'])->name('search');


Route::post('/student/insert', [StudentController::class, 'insert'])->name('student.insert');

// go get std info from controller 
Route::get('/get-student/{id}', [StudentController::class, 'getStudent']);
Route::get('/search-ticket/{trackid}', [StudentController::class, 'getTicket']);


Route::get('/studentview/{stud_id}', [StudentController::class, 'getStudentData']);
 


// call section 
// routes/web.php
Route::prefix('calls')->group(function() {
    Route::post('/store', [CallController::class, 'store'])->name('calls.store');
    Route::get('/search-student', [CallController::class, 'searchStudent'])->name('calls.searchStudent');
     Route::get('/search-ticket', [CallController::class, 'searchTicket'])->name('calls.searchTicket');
  
});

Route::get('/calls/search', [CallController::class, 'search'])->name('calls.search');
Route::get('/calls/create', function () {
    return view('calls.create');
})->name('calls.create');

/// for tickets
Route::get('/ticket/view', [TicketController::class, 'view'])->name('ticket.view');

Route::get('/search-ticket/{ticketId}', [TicketController::class, 'search'])->name('ticket.search');
// for Voice Call submit
  Route::post('/voice-calls/store', [CallController::class, 'store'])
    ->name('voicecalls.store')
    ->middleware('auth');  
    
   
Route::get('/test-db/{conn}', function($conn) {
    // قائمة الاتصالات المسموح بها - عدلها حسب ما عندك في config/database.php
    $allowed = ['mysql', 'mysql_sis2', 'mysql_Hdesk'];

    if (!in_array($conn, $allowed)) {
        return response()->json(['success' => false, 'message' => 'Invalid connection name'], 400);
    }

    try {
        // استعلام بسيط للتحقق
        $result = DB::connection($conn)->select('SELECT NOW() as now');
        
        return response()->json([
            'success' => true,
            'connection' => $conn,
            'server_time' => $result[0]->now ?? null
        ]);
    } catch (\Exception $e) {
        // سجل الخطأ في اللّوجز لتحليل لاحق
        Log::error("DB test failed [$conn]: " . $e->getMessage());

        return response()->json([
            'success' => false,
            'connection' => $conn,
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::get('/report-data', [ReportController::class, 'getReportData']);
Route::view('/reports', 'reports.index');
Route::get('/reports/milestones', function () {
    return view('reports.milestones');
})->name('reports.milestones');
Route::get('/reports/milestones', [ReportController::class, 'milestones']);

/* 
// Dashboard page (returns blade)
Route::get('/dashboard', function () {
    return view('reports.dashboard'); // loads dashboard.blade.php
});
Route::get('/dashboard', [ReportController::class, 'callsPerUser'])->name('dashboard');
// API endpoint (returns JSON)
Route::get('/reports/calls-per-user', [ReportController::class, 'callsPerUser']);
//for test
Route::get('/dashboard', [ReportController::class, 'index'])->name('reports.dashboard');
Route::get('/dashboard/data', [ReportController::class, 'dashboardData'])->name('reports.dashboard.data');
*/

// عشان  Dashboard
//Route::get('/report', [ReportController::class, 'index'])->name('dashboard');

// جلب البيانات عشان Dashboard (AJAX)
Route::get('/report/data', [ReportController::class, 'dashboardData'])->name('dashboard.data');

// عشان تقرير المكالمات لكل مستخدم
Route::get('/reports/calls-per-user', [ReportController::class, 'callsPerUser'])->name('reports.calls_per_user');

// تقرير الفئات عشان
Route::get('/reports/data', [ReportController::class, 'getReportData'])->name('reports.dashboard.data');

// بيانات الأداء >> عشان تجيب لي 
Route::get('/reports/dashboard-data', [ReportController::class, 'getDashboardData'])->name('reports.dashboard_data');

Route::get('/log-url', function (\Illuminate\Http\Request $request) {
    \Log::info("Dashboard URL: " . $request->query('url'));
    return response()->json(['status' => 'logged']);
})->name('log.url');


//dyn rep
// routes/web.php
Route::get('/reports/voice-calls', [ReportController::class, 'voiceCallsReport'])->name('reports.voicecalls');
Route::post('/reports/voice-calls/search', [ReportController::class, 'search'])->name('reports.voicecalls.search');
Route::post('/reports/calls/update-status', [ReportController::class, 'updateStatus'])
->name('reports.calls.update-status');
// added routes for user popup code
Route::get('/user-profile', [UserProfileController::class, 'dashboard'])->name('user.profile.dashboard');
Route::get('/profile-data/{userId}', [UserProfileController::class, 'getProfileData'])
    ->name('profile.data')
    ->middleware('auth');


Route::get('/profile-data/{userId}', [UserProfileController::class, 'profileData'])->middleware('auth');

Route::get('/user-calls', [UserProfileController::class, 'search'])->name('usercalls');

Route::get('/call-archive', [UserProfileController::class, 'callArchive'])->name('call.archive');

Route::post('/calls/update-status', [UserProfileController::class, 'updateStatus'])->name('calls.update-status');
Route::get('/get-users-list', [App\Http\Controllers\ReportController::class, 'getUsersList']);


