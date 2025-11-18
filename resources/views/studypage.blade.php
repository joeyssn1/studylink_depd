<x-layout title="Study">

    <section class="max-w-4xl mx-auto text-center mt-10 mb-8 px-4">
        <h2 class="text-3xl font-bold text-gray-900 leading-snug">
            What would type of <br> study today <br>
            <span class="font-bold">@username?</span>
        </h2>
    </section>

    <!-- Study Options -->
    <section class="max-w-5xl mx-auto mt-10 mb-8 px-4">

        <div class="grid grid-cols-3 gap-8 place-items-center">

            <!-- Active Recall -->
            <a href="" class="w-10 h-64 border shadow-sm flex flex-col items-center justify-center hover:shadow-md transition">
                <div class="w-12 h-12 bg-yellow-400 rounded-full mb-4 mt-8">
                    <span class="text-yellow-600 font-bold">Image</span>
                </div>
                <p class="text-lg font-semibold text-center">Active Recall</p>
            </a>

            <!-- Spaced Repetition -->
            <a href="" class="w-64 h-64 border shadow-sm flex flex-col items-center justify-center hover:shadow-md transition">
                <div class="w-12 h-12 bg-yellow-400 rounded-full mb-4 mt-8">
                    <span class="text-yellow-600 font-bold">Image</span>
                </div>
                <p class="text-lg font-semibold text-center">Spaced Repetition</p>
            </a>

            <!-- Pomodoro -->
            <a href="" class="w-64 h-64 border shadow-sm flex flex-col items-center justify-center hover:shadow-md transition col-span-2">
                <div class="w-12 h-12 bg-yellow-400 rounded-full mb-4 mt-8">
                    <span class="text-yellow-600 font-bold">Image</span>
                </div>
                <p class="text-lg font-semibold text-center">Pomodoro</p>
            </a>

        </div>

    </section>

</x-layout>
