<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\App;
 
use App\Models\User;
use Illuminate\Http\RedirectResponse;


class DashboardRedirectController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        //dd(auth()->guard()->user()->role);
       // return match (auth()->user()->role) 
            return match(auth()->guard()->user()->role){
            User::ROLE_ADMIN      => redirect()->route('admin.dashboard'),
            User::ROLE_SUPERVISOR => redirect()->route('supervisor.dashboard'),
            default               => redirect()->route('user.dashboard'),

        };
    }
}