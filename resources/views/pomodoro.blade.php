{{-- views/pomodoro.blade.php --}}
<x-layout title="Pomodoro Session">

    <div class="min-h-[80vh] flex items-center justify-center px-4">

        <!-- START SCREEN -->
        <div id="start-button-container" class="text-center max-w-xl">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2">
                Pomodoro Session 🍅
            </h1>
            <p class="text-gray-500 mb-10">
                Subject: <span class="font-semibold">{{ $study->subject_name }}</span>
            </p>

            <button
                onclick="toggleModal('modal-settings')"
                class="w-64 h-64 rounded-full bg-[#c9a348] text-white shadow-2xl hover:scale-105 transition flex flex-col items-center justify-center mx-auto"
            >
                <span class="text-3xl font-bold">Start</span>
                <span class="text-sm opacity-80 mt-1">Focus & Recall</span>
            </button>
        </div>

        <!-- TIMER SCREEN -->
        <div id="timer-container" class="hidden text-center">
            <p id="status-label" class="text-lg font-bold tracking-wide mb-2 text-[#c9a348]">
                Focus Time
            </p>

            <h1
                id="timer-display"
                class="text-7xl md:text-8xl font-extrabold tracking-wider mb-8 text-gray-900"
            >
                00:00
            </h1>

            <button
                onclick="endSession()"
                class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold transition"
            >
                End Session
            </button>
        </div>

    </div>

    <!-- SETTINGS MODAL -->
    <div
        id="modal-settings"
        class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center px-4"
    >
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-8 animate-fade-in">
            <h2 class="text-xl font-bold text-center mb-6">
                Pomodoro Settings
            </h2>

            <form id="form-pomodoro-settings">
                @csrf
                <input type="hidden" name="study_id" value="{{ $study->study_id }}">

                <div class="mb-5">
                    <label class="block font-semibold mb-1">Focus Time (minutes)</label>
                    <input
                        type="number"
                        id="focus_time"
                        name="focus_time"
                        min="1"
                        max="60"
                        value="25"
                        required
                        class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-[#c9a348]"
                    >
                </div>

                <div class="mb-8">
                    <label class="block font-semibold mb-1">Break Time (minutes)</label>
                    <input
                        type="number"
                        id="rest_time"
                        name="rest_time"
                        min="1"
                        max="60"
                        value="5"
                        required
                        class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-[#c9a348]"
                    >
                </div>

                <button
                    type="button"
                    onclick="startSession()"
                    class="w-full py-3 bg-[#c9a348] hover:bg-[#b89237] text-white rounded-xl font-bold"
                >
                    Start Session
                </button>

                <button
                    type="button"
                    onclick="toggleModal('modal-settings')"
                    class="w-full mt-3 text-sm text-gray-500 hover:text-gray-700"
                >
                    Cancel
                </button>
            </form>
        </div>
    </div>

</x-layout>

{{-- SCRIPT (UNCHANGED LOGIC) --}}
<script>
    let timerInterval;
    let isFocus = true;
    let currentFocusMinutes;
    let currentRestMinutes;

    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    async function startSession() {
        const form = document.getElementById('form-pomodoro-settings');
        const formData = new FormData(form);

        const response = await fetch("{{ route('pomodoro.store') }}", {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });

        if (response.ok) {
            toggleModal('modal-settings');
            document.getElementById('start-button-container').classList.add('hidden');
            document.getElementById('timer-container').classList.remove('hidden');

            runTimer(
                document.getElementById('focus_time').value,
                document.getElementById('rest_time').value
            );
        }
    }

    function runTimer(focus, rest) {
        currentFocusMinutes = parseInt(focus);
        currentRestMinutes = parseInt(rest);
        isFocus = true;
        startCountdown(currentFocusMinutes * 60);
    }

    function startCountdown(seconds) {
        clearInterval(timerInterval);
        updateDisplay(seconds);
        updateStatusLabel();

        timerInterval = setInterval(() => {
            seconds--;
            updateDisplay(seconds);

            if (seconds <= 0) {
                clearInterval(timerInterval);
                isFocus = !isFocus;
                playNotification();
                startCountdown((isFocus ? currentFocusMinutes : currentRestMinutes) * 60);
            }
        }, 1000);
    }

    function updateDisplay(seconds) {
        const m = String(Math.floor(seconds / 60)).padStart(2, '0');
        const s = String(seconds % 60).padStart(2, '0');
        document.getElementById('timer-display').innerText = `${m}:${s}`;
        document.title = `(${m}:${s}) Pomodoro`;
    }

    function updateStatusLabel() {
        const label = document.getElementById('status-label');
        label.innerText = isFocus ? "Focus Time" : "Break Time";
        label.style.color = isFocus ? "#c9a348" : "#10b981";
    }

    function playNotification() {
        new Audio('https://actions.google.com/sounds/v1/alarms/beep_short.ogg').play().catch(()=>{});
    }

    function endSession() {
        if (confirm('End this session?')) window.location.href = "/study";
    }
</script>

<style>
.animate-fade-in { animation: fade .2s ease-out }
@keyframes fade { from {opacity:0; transform:scale(.95)} to {opacity:1} }
</style>
