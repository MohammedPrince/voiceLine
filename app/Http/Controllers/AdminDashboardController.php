<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
public function index()
{
    
return view('dashboard.admin');
}

}
