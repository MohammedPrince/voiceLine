<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VoiceCall; 
use Carbon\Carbon;
class AdminDashboardController extends Controller
{
public function index()
{
   //     $user = Auth::user();
   //  $userId = $user->id;  // just pass the user ID

   //  return view('dashboard.admin', compact('userId'));
$user = auth()->user();

    // You can get totalCalls for this user as example:
    $totalCalls = \App\Models\VoiceCall::where('handled_by_user_id', $user->id)->count();

    // Pass variables to the view
    return view('dashboard.admin', compact('user', 'totalCalls'));
}

}
