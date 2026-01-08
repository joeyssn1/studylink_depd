{{-- views/studypage.blade.php --}}
<x-layout title="Study">

    <!-- HEADER -->
    <section class="max-w-5xl mx-auto text-center mt-16 mb-12 px-4">
        <h1 class="text-4xl font-extrabold text-gray-900 leading-snug">
            How would you like to study today,
            <br>
            <span class="text-[#c9a348]">
                {{ auth()->user()->fullname }}?
            </span>
        </h1>

        <p class="text-gray-500 mt-4 max-w-2xl mx-auto">
            Choose a study technique that fits your learning style.
            Focus deeply or train your memory actively.
        </p>
    </section>

    <!-- STUDY OPTIONS -->
    <section class="max-w-6xl mx-auto px-4 mb-24">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            <!-- ACTIVE RECALL CARD -->
            <div onclick="toggleModal('modal-active-recall')"
                class="group cursor-pointer bg-white border rounded-3xl shadow-sm hover:shadow-xl transition p-10 text-center">
                <div
                    class="w-16 h-16 rounded-full bg-yellow-200 flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition">
                    <span class="text-2xl font-bold text-yellow-600">🧠</span>
                </div>

                <h2 class="text-2xl font-bold mb-3 text-gray-800">
                    Active Recall
                </h2>

                <p class="text-gray-500 text-sm leading-relaxed mb-6">
                    {{-- Strengthen your memory by recalling information
                    actively with help from an AI study partner. --}}
                    Strengthen your memory by recalling information
                    actively with help from an AI study partner.
                </p>

                <span
                    class="inline-block mt-auto px-6 py-2 rounded-full text-sm font-bold bg-[#c9a348] text-white group-hover:bg-[#b89237] transition">
                    Start Recall
                </span>
            </div>

            <!-- POMODORO CARD -->
            <div onclick="toggleModal('modal-pomodoro')"
                class="group cursor-pointer bg-white border rounded-3xl shadow-sm hover:shadow-xl transition p-10 text-center">
                <div
                    class="w-16 h-16 rounded-full bg-red-200 flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition">
                    <span class="text-2xl font-bold text-yellow-600">🍅</span>
                </div>

                <h2 class="text-2xl font-bold mb-3 text-gray-800">
                    Pomodoro Technique
                </h2>

                <p class="text-gray-500 text-sm leading-relaxed mb-6">
                    {{-- Manage your focus using time-boxed sessions
                    with structured breaks. --}}
                    Work in focused intervals with breaks to maintain energy and consistency.
                </p>

                <span
                    class="inline-block mt-auto px-6 py-2 rounded-full text-sm font-bold bg-[#c9a348] text-white group-hover:bg-[#b89237] transition">
                    Start Pomodoro
                </span>
            </div>

        </div>
    </section>

    <!-- POMODORO -->
    <div id="modal-pomodoro" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center px-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 animate-fade-in">
            <h2 class="text-2xl font-bold mb-6 text-center">
                Start Pomodoro Session
            </h2>

            <form action="{{ route('study.store') }}" method="POST">
                @csrf
                <input type="hidden" name="study_type" value="Pomodoro">

                <div class="mb-6">
                    <label class="block font-semibold mb-2 text-gray-700">
                        What will you study?
                    </label>
                    <input type="text" name="subject_name" required placeholder="Example: Laravel Basics"
                        class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-[#c9a348]">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="toggleModal('modal-pomodoro')"
                        class="px-4 py-2 text-gray-500 hover:text-gray-700">
                        Cancel
                    </button>

                    <button type="submit"
                        class="px-6 py-2 bg-[#c9a348] hover:bg-[#b89237] text-white rounded-xl font-bold transition">
                        Continue
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ACTIVE RECALL -->
    <div id="modal-active-recall" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center px-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 animate-fade-in">
            <h2 class="text-2xl font-bold mb-6 text-center">
                Start Active Recall
            </h2>

            <form action="{{ route('study.store') }}" method="POST">
                @csrf
                <input type="hidden" name="study_type" value="ActiveRecall">

                <div class="mb-6">
                    <label class="block font-semibold mb-2 text-gray-700">
                        Recall Topic
                    </label>
                    <input type="text" name="subject_name" required placeholder="Example: Human Anatomy"
                        class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-[#c9a348]">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="toggleModal('modal-active-recall')"
                        class="px-4 py-2 text-gray-500 hover:text-gray-700">
                        Cancel
                    </button>

                    <button type="submit"
                        class="px-6 py-2 bg-[#c9a348] hover:bg-[#b89237] text-white rounded-xl font-bold transition">
                        Start Learning
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layout>

{{-- SCRIPT --}}
<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.classList.toggle('hidden');
    }

    window.onclick = function(event) {
        ['modal-pomodoro', 'modal-active-recall'].forEach(id => {
            const modal = document.getElementById(id);
            if (event.target === modal) toggleModal(id);
        });
    }
</script>

<style>
    .animate-fade-in {
        animation: fadeIn .2s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>
