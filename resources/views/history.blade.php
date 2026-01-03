{{-- views/history.blade.php --}}
<x-layout title="Study History">

    <section class="max-w-6xl mx-auto mt-20 px-4 mb-14 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
            Study History 📚
        </h1>
        <p class="text-gray-500 max-w-2xl mx-auto text-lg">
            A timeline of your learning consistency and dedication.
        </p>
    </section>

    <!-- SUMMARY CARDS -->
    <section class="max-w-6xl mx-auto px-4 mb-14">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

            <div class="bg-white border rounded-2xl p-6 shadow-sm">
                <p class="text-sm text-gray-500 mb-1">Total Sessions</p>
                <p class="text-3xl font-extrabold text-gray-900">
                    {{ $histories->count() }}
                </p>
            </div>

            <div class="bg-white border rounded-2xl p-6 shadow-sm">
                <p class="text-sm text-gray-500 mb-1">Pomodoro Sessions</p>
                <p class="text-3xl font-extrabold text-orange-600">
                    {{ $histories->where('study_type', 'Pomodoro')->count() }}
                </p>
            </div>

            <div class="bg-white border rounded-2xl p-6 shadow-sm">
                <p class="text-sm text-gray-500 mb-1">Active Recall Sessions</p>
                <p class="text-3xl font-extrabold text-blue-600">
                    {{ $histories->where('study_type', 'Active Recall')->count() }}
                </p>
            </div>

        </div>
    </section>

    <!-- HISTORY TIMELINE -->
    <section class="max-w-4xl mx-auto px-4 mb-32">

        <div class="relative border-l-2 border-gray-200 pl-8">

            @forelse ($histories as $history)
                <div class="relative mb-10">

                    <!-- DOT -->
                    <div
                        class="absolute -left-[11px] top-1 w-5 h-5 rounded-full
                        {{ $history->study_type === 'Pomodoro'
                            ? 'bg-orange-500'
                            : 'bg-blue-500' }}"
                    ></div>

                    <!-- CARD -->
                    <div class="bg-white border rounded-2xl p-6 shadow-sm hover:shadow-md transition">

                        <div class="flex items-center justify-between gap-3 mb-3">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $history->study_type === 'Pomodoro'
                                    ? 'bg-orange-100 text-orange-600'
                                    : 'bg-blue-100 text-blue-600' }}"
                            >
                                {{ $history->study_type }}
                            </span>

                            <span class="text-sm text-gray-400">
                                {{ \Carbon\Carbon::parse($history->created_at)
                                    ->translatedFormat('d M Y') }}
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900">
                            {{ $history->subject_name }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ \Carbon\Carbon::parse($history->created_at)
                                ->translatedFormat('l') }}
                        </p>

                    </div>
                </div>
            @empty
                <!-- EMPTY STATE -->
                <div class="text-center py-24">
                    <p class="text-2xl mb-3">😴</p>
                    <p class="text-gray-400 italic mb-2">
                        No study history yet.
                    </p>
                    <p class="text-sm text-gray-500">
                        Start a Pomodoro or Active Recall session to build your streak!
                    </p>
                </div>
            @endforelse

        </div>
    </section>

</x-layout>
