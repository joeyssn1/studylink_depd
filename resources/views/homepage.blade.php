<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyLink</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-white">

    <!-- HEADER -->
    <header class="w-full bg-[#22335F] text-white">
        <div class="max-w-5xl mx-auto flex items-center justify-between py-4 px-4">

            <!-- Logo sementara -->
            <div class="flex items-center space-x-2">
                <div class="w-7 h-7 bg-white rounded-md flex items-center justify-center">
                    <span class="text-[#22335F] font-bold text-sm">SL</span>
                </div>
                <h1 class="text-xl font-semibold">StudyLink</h1>
            </div>

            <!-- Navigation button -->
            <nav class="flex items-center space-x-6 text-white">
                <a href="#" class="border-b-2 border-white pb-1">Home</a>
                <a href="#" class="hover:underline">Study</a>
                <a href="#" class="hover:underline">History</a>
                <a href="#" class="hover:underline">Profile</a>
            </nav>

        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="max-w-4xl mx-auto text-center mt-10 px-4">
        <h2 class="text-3xl font-bold text-gray-900">
            Improve Your Study <br> Techniques
        </h2>
        <p class="text-sm text-gray-600 mt-4">
            Learn effective methods to study smarter <br> and achieve better results
        </p>

        <button class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg">
            Get Started
        </button>
    </section>

    <!-- STUDY TECHNIQUES -->
    <section class="max-w-5xl mx-auto mt-10 px-4">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Study Techniques</h3>

        <div class="grid grid-cols-3 gap-4 mt-6">

            <!-- Active Recall -->
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-yellow-200 rounded-full flex items-center justify-center mb-3">
                    <span class="text-yellow-600 text-xl font-bold"></span>
                </div>
                <p class="text-sm font-semibold">Active Recall</p>
                <p class="text-gray-600 text-xs mt-2">
                    Improves memory by actively retrieving information.
                </p>
            </div>

            <!-- Spaced Repetition -->
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-yellow-200 rounded-full flex items-center justify-center mb-3">
                    <span class="text-yellow-600 text-xl font-bold"></span>
                </div>
                <p class="text-sm font-semibold">Spaced Repetition</p>
                <p class="text-gray-600 text-xs mt-2">
                    Enhances long-term retention through intervals.
                </p>
            </div>

            <!-- Pomodoro -->
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-yellow-200 rounded-full flex items-center justify-center mb-3">
                    <span class="text-yellow-600 text-xl font-bold"></span>
                </div>
                <p class="text-sm font-semibold">Pomodoro</p>
                <p class="text-gray-600 text-xs mt-2">
                    Time management technique to stay focused.
                </p>
            </div>

        </div>
    </section>

    <!-- TRACK PROGRESS -->
    <section class="max-w-5xl mx-auto mt-10 px-4 mb-4">
        <h3 class="text-xl font-semibold text-gray-900 mb-3">Track Your Progress</h3>
        <p class="text-gray-600 mb-4">
            Monitor your study habits and stay motivated as you learn.
        </p>

        <div class="flex items-center gap-4">
            <!-- Progress Bar -->
            <div class="flex-1 h-3 bg-gray-200 rounded-full"></div>
            <!-- Update Button -->
            <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg whitespace-nowrap">
                Update
            </button>
        </div>
    </section>


    <!-- FOOTER -->
    <footer class="w-full bg-[#22335F] text-white py-4 text-center text-sm">
        © 2025 Team@DEPD — Universitas Ciputra Surabaya
    </footer>

</body>

</html>
