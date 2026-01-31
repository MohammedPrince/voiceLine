<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;

class DeanDashboardController extends Controller
{
     public function index()     
{
    
return view('dashboard.dean');
}

}
