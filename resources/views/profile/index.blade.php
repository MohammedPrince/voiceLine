@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">User Profile</h1>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <!-- Name -->
        <div class="mb-4">
            <label class="block text-gray-700">Name</label>
            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <!-- Update Button -->
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            Update Profile
        </button>
    </form>
</div>
@endsection
