@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Search Results</h2>

    @if($students->isEmpty())
        <div class="alert alert-warning">No students found.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Surname</th>
                    <th>Faculty</th>
                    <th>Batch</th>
                    <th>GPA</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->stud_id }}</td>
                        <td>{{ $student->stud_name }}</td>
                        <td>{{ $student->stud_surname }}</td>
                        <td>{{ $student->faculty_code }}</td>
                        <td>{{ $student->batch }}</td>
                        <td>{{ $student->stud_gpa }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('students.search.form') }}" class="btn btn-secondary">Back to Search</a>
</div>
@endsection
