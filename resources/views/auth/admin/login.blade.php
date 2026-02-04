@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto mt-10">
        <h1 class="text-2xl mb-4">Admin Login</h1>

        @if ($errors->any())
            <div class="mb-4 text-red-600">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full border px-2 py-1" />
            </div>
            <div class="mb-4">
                <label class="block mb-1">Password</label>
                <input type="password" name="password" required class="w-full border px-2 py-1" />
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white">Login</button>
            </div>
        </form>
    </div>
@endsection
