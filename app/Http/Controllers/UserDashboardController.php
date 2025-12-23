<?php

namespace App\Http\Controllers;
use Illuminate\View\View;


class UserDashboardController extends Controller
{
    //

public function index()  
{
    
return view('dashboard.user');
}

}
