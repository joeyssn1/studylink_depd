<x-layout title="Register">

    <div class="max-w-md mx-auto my-10 p-6 border shadow">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">Register</h2>

        @if ($errors->any())
            <div class="mb-5 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                <p class="text-red-600 text-sm font-medium">{{ $errors->first() }}</p>
            </div>
        @endif

        <form action="/register" method="POST">
            @csrf

            <div class="mb-5">
                <label class="block mb-2 font-semibold text-gray-700">Full Name</label>
                <input type="text" name="fullname" value="{{ old('fullname') }}"
                    class="w-full border border-gray-300 p-2 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition-all bg-gray-50"
                    placeholder="Nama Lengkap" required>
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold text-gray-700">Username</label>
                <input type="text" name="username" value="{{ old('username') }}"
                    class="w-full border border-gray-300 p-2 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition-all bg-gray-50"
                    placeholder="Username" required>
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold text-gray-700">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border border-gray-300 p-2 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition-all bg-gray-50"
                    placeholder="email@gmail.com" required>
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold text-gray-700">Password</label>
                <input type="password" name="password"
                    class="w-full border border-gray-300 p-2 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition-all bg-gray-50"
                    placeholder="Min. 6 karakter" required>
            </div>

            <div class="mb-8">
                <label class="block mb-2 font-semibold text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full border border-gray-300 p-2 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition-all bg-gray-50"
                    placeholder="Ulangi password" required>
            </div>

            <button type="submit"
                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white p-2 rounded-xl font-bold shadow-md transform transition active:scale-95 uppercase tracking-wider">
                Register
            </button>

            <p class="mt-6 text-sm text-center text-gray-600">
                Already have an account? <a href="/login" class="text-blue-600 font-bold hover:underline">Login</a>
            </p>
        </form>
    </div>

</x-layout>
