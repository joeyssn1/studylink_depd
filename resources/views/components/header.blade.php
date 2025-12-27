{{-- views/components/header.blade.php --}}

<header class="w-full bg-[#22335F] text-white">
    <div class="max-w-5xl mx-auto flex items-center justify-between py-4 px-4">

        <!-- Logo -->
        <div class="flex items-center space-x-2">
            <div class="w-7 h-7 bg-white rounded-md flex items-center justify-center">
                <span class="text-[#22335F] font-bold text-sm">SL</span>
            </div>
            <h1 class="text-xl font-semibold">StudyLink</h1>
        </div>

        <!-- Navigation -->
        <nav class="flex items-center space-x-6 text-white">

            <!-- Home -->
            <a href="{{ route('home') }}" class="hover:underline">Home</a>

            @auth
                <a href="/study" class="hover:underline">Study</a>
                <a href="#" class="hover:underline">History</a>

                <!-- Profile -->
                <a href="{{ route('profile') }}" class="hover:underline">Profile</a>

                <!-- Logout -->
                <form action="/logout" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="ml-4 bg-red-500 hover:bg-red-600 px-3 py-1 rounded transition">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:underline">Login</a>
            @endauth

        </nav>

    </div>
</header>
