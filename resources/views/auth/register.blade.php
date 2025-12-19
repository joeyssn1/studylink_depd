{{-- views/auth/register.blade.php --}}

<x-layout title="Register">

    <div class="max-w-md mx-auto mt-20 p-6 border shadow">
        <h2 class="text-2xl font-bold mb-4">Register</h2>

        @if ($errors->any())
            <p class="text-red-600 mb-2">{{ $errors->first() }}</p>
        @endif

        <form action="/register" method="POST">
            @csrf

            <div class="mb-4">
                <label>Name</label>
                <input type="text" name="name" class="w-full border p-2" required>
            </div>

            <div class="mb-4">
                <label>Email</label>
                <input type="email" name="email" class="w-full border p-2" required>
            </div>

            <div class="mb-4">
                <label>Password</label>
                <input type="password" name="password" class="w-full border p-2" required>
            </div>

            <div class="mb-4">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border p-2" required>
            </div>

            <button class="w-full bg-yellow-600 text-white p-2 rounded">Register</button>

            <p class="mt-4 text-sm">
                Already have an account? <a href="/login" class="text-blue-500 underline">Login</a>
            </p>
        </form>
    </div>

</x-layout>
