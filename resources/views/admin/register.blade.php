<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.register.post') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full border px-2 py-1" />
                        @error('name')
                            <div class="text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full border px-2 py-1" />
                        @error('email')
                            <div class="text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Password</label>
                        <input type="password" name="password" required class="w-full border px-2 py-1" />
                        @error('password')
                            <div class="text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" required class="w-full border px-2 py-1" />
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Role</label>
                        <select name="role" class="w-full border px-2 py-1">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        @error('role')
                            <div class="text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
