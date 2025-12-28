<x-layout title="Login">

    <div class="max-w-md mx-auto mt-20 p-6 border shadow">
        <h2 class="text-2xl font-bold mb-4">Login</h2>

        @if ($errors->any())
            <p class="text-red-600 mb-2">{{ $errors->first() }}</p>
        @endif

        <form action="/login" method="POST">
            @csrf

            <div class="mb-4">
                <label>Email or Username</label>
                <input type="text" name="login" class="w-full border p-2" value="{{ old('login') }}" required
                placeholder="email/username" required>
            </div>  

            <div class="mb-4">
                <label>Password</label>
                <input type="password" name="password" class="w-full border p-2" required
                placeholder="Min 6 char" required>
            </div>

            <button class="w-full bg-blue-600 text-white p-2 rounded">Login</button>

            <p class="mt-4 text-sm">
                Don’t have an account? <a href="/register" class="text-blue-500 underline">Register</a>
            </p>
        </form>
    </div>

</x-layout>