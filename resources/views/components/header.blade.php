{{-- views/components/header.blade.php --}}

<header class="w-full bg-[#22335F] text-white">
    <div class="max-w-5xl mx-auto flex items-center justify-between px-4">

        <!-- Logo -->
        <div class="flex items-center space-x-2 py-1">
            <img src="{{ asset('images/StudyLink_Logo_3.svg') }}" alt="StudyLink Logo" class="w-50 h-16 object-contain">
        </div>

        <!-- Navigation -->
        <nav class="flex items-center space-x-6 font-medium h-full">

            @php
                // ACTIVE: text + underline kuning emas
                $activeClass = 'text-[#c9a348] border-b-4 border-[#c9a348] shadow-[0_2px_0_rgba(201,163,72,0.9)]';

                // INACTIVE: putih, underline muncul saat hover
                $inactiveClass = 'text-white border-b-4 border-transparent hover:border-[#c9a348]';
            @endphp

            <!-- Home -->
            <a href="{{ route('home') }}"
                class="py-5 transition-all duration-200 {{ request()->routeIs('home') ? $activeClass : $inactiveClass }}">
                Home
            </a>

            @auth
                <!-- Study -->
                <a href="/study"
                    class="py-5 transition-all duration-200 {{ request()->is('study*') ? $activeClass : $inactiveClass }}">
                    Study
                </a>

                <!-- History -->
                <a href="/history"
                    class="py-5 transition-all duration-200 {{ request()->is('history*') ? $activeClass : $inactiveClass }}">
                    History
                </a>

                <!-- Profile -->
                <a href="{{ route('profile') }}"
                    class="py-5 transition-all duration-200 {{ request()->routeIs('profile') ? $activeClass : $inactiveClass }}">
                    Profile
                </a>

                <!-- Logout -->
                <form action="/logout" method="POST" class="inline ml-4">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded transition">
                        Logout
                    </button>
                </form>
            @else
                <!-- Login -->
                <a href="{{ route('login') }}"
                    class="py-5 transition-all duration-200 {{ request()->routeIs('login') ? $activeClass : $inactiveClass }}">
                    Login
                </a>
            @endauth

        </nav>

    </div>
</header>
