<x-layout title="Login">
    <div class="h-screen overflow-hidden relative">

        <div class="fixed inset-0 bg-gradient-to-br from-red-50 via-white to-yellow-50 -z-10"></div>
        <div class="absolute inset-0 flex items-center justify-center px-4">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

                <!-- Header -->
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-800">
                        Welcome Back 👋
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Stay focused. One pomodoro at a time 🍅
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="/login" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Email or Username
                        </label>
                        <input
                            type="text"
                            name="login"
                            value="{{ old('login') }}"
                            required
                            class="w-full px-4 py-2 rounded-xl border border-gray-300 bg-gray-50
                                   focus:ring-2 focus:ring-red-400 focus:border-red-400 outline-none transition"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Password
                        </label>
                        <input
                            type="password"
                            name="password"
                            required
                            class="w-full px-4 py-2 rounded-xl border border-gray-300 bg-gray-50
                                   focus:ring-2 focus:ring-red-400 focus:border-red-400 outline-none transition"
                        >
                    </div>

                    <button
                        class="w-full mt-2 bg-red-500 hover:bg-red-600 text-white py-2 rounded-xl
                               font-bold shadow-md transition active:scale-95"
                    >
                        Login
                    </button>
                </form>

                <p class="mt-6 text-sm text-center text-gray-600">
                    Don’t have an account?
                    <a href="/register" class="text-red-500 font-semibold hover:underline">
                        Register
                    </a>
                </p>

            </div>
        </div>

    </div>

</x-layout>
