
@extends('layouts.app')
  
@section('title', 'Home Page')
 
@section('content')
  <div class="choices">
        
<!-- <a class="choice" href="{{ route('calls.create') }}">
        <span>User Management </span>
    </a> -->
    <!-- Optional additional nav items -->
    <a class="choice" href="{{ url('reports/') }}">
        <span>Reports</span>
    </a>
    <a class="choice" href="{{ route('student') }}">
        <span>Call Entry</span>
    </a>
    
</div>

@endsection
