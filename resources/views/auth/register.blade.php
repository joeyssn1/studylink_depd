<x-layout title="Register">

    <div class="h-screen overflow-hidden relative">

        <div class="fixed inset-0 bg-gradient-to-br from-yellow-50 via-white to-red-50 -z-10"></div>

        <div class="absolute inset-0 flex items-center justify-center px-4">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

                <!-- Header -->
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-800">
                        Create Account ✨
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Build better study habits with Pomodoro & Active Recall
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-5 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                        <p class="text-red-600 text-sm font-medium">
                            {{ $errors->first() }}
                        </p>
                    </div>
                @endif

                <form action="/register" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Full Name
                        </label>
                        <input
                            type="text"
                            name="fullname"
                            value="{{ old('fullname') }}"
                            required
                            class="w-full px-4 py-2 rounded-xl border border-gray-300 bg-gray-50
                                   focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none transition"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Username
                        </label>
                        <input
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            required
                            class="w-full px-4 py-2 rounded-xl border border-gray-300 bg-gray-50
                                   focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none transition"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Email
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full px-4 py-2 rounded-xl border border-gray-300 bg-gray-50
                                   focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none transition"
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
                                   focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none transition"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Confirm Password
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            required
                            class="w-full px-4 py-2 rounded-xl border border-gray-300 bg-gray-50
                                   focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none transition"
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded-xl
                               font-bold shadow-md transition active:scale-95 uppercase tracking-wide"
                    >
                        Register
                    </button>
                </form>

                <p class="mt-6 text-sm text-center text-gray-600">
                    Already have an account?
                    <a href="/login" class="text-red-500 font-semibold hover:underline">
                        Login
                    </a>
                </p>

            </div>
        </div>

    </div>

</x-layout>
