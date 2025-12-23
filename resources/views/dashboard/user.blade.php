
@extends('layouts.app')
  
@section('title', 'Home Page')
 
@section('content')
  <div class="choices">
   
    <!-- Optional additional nav items -->
        <a class="choice" href="javascript:void(0);">
            <span>Get Direct Call</span>
        </a>
       
    
    <a class="choice" href="{{ url('reports') }}">
        <span>Start a call/ticket</span>
    </a>

</div>

@endsection
