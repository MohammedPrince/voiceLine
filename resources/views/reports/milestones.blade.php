@extends('layouts.app')

@section('title', 'Milestones Report')

@section('content')


<div class="reports-container">

    <!-- Navigation -->
    <ul>
      <li><a href="/reports"> General Report </a></li>
      <li><a href="/reports/calls-per-user">Detailed Report</a></li>
      <li><a href="/reports/voice-calls">Voice Calls Report</a></li>
      <li><a href="/reports/milestones"  class="active"> Milestones Report </a></li>
    </ul>

    <!-- Page Title -->
    <h5>Calls done today (per user)</h5>

    <!-- Calls Table -->
    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <thead class="table">
                <tr>
                    <th>User Name</th>
                    <th>Calls Today</th>
                </tr>
            </thead>
            <tbody>
                @forelse($callsPerUser as $user)
                    <tr>
                        <td>{{ $user->user_name }}</td>
                        <td>
                            <span class="badge bg-success">
                                {{ $user->total_calls }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-muted">
                            No calls recorded today
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
