 @extends('layouts.app') {{-- If you have a layout --}}

@section('content')
<div class="container">
    <h2>Search Students</h2>
    <form method="GET" action="{{ route('students.search') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Stuudent Index No.</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="e.g. 202035001">
        </div>

        <div class="mb-3">
            <label for="surname" class="form-label">Ticket Number</label>
            <input type="text" id="ticket_number" name="ticket_number" class="form-control" placeholder="e.g. X7Q-QPW-M7ZY">
        </div>

        <div class="mb-3">
            <label for="faculty" class="form-label">Voice Call ID</label>
            <input type="text" id="faculty" name="faculty" class="form-control" placeholder="e.g. 0123">
        </div>

        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>
@endsection
