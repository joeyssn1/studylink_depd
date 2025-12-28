{{-- views/pomodoro.blade.php

<x-layout title="Pomodoro">
    <x-layoutstudy studyTitle="Pomodoro">
        <div x-data="timerComponent()" class="flex flex-col items-center">
            <p class="text-lg mb-2 mt-8">Timer:</p>

            <h1 class="text-7xl font-bold tracking-wide mb-10" x-text="formattedTime"></h1>

            <div class="flex gap-4 mb-8">
                <button 
                    x-show="!hasStarted || isPaused"
                    x-on:click="start()" 
                    class="bg-[#c9a348] text-white px-6 py-3 rounded-md hover:bg-yellow-500 transition"
                >
                    Start Timer
                </button>

                <button 
                    x-show="hasStarted && !isPaused"
                    x-on:click="pause()" 
                    class="bg-gray-400 text-white px-6 py-3 rounded-md hover:bg-gray-500 transition"
                >
                    Pause Timer
                </button>

                <button 
                    x-show="hasStarted"
                    x-on:click="reset()" 
                    class="bg-red-500 text-white px-6 py-3 rounded-md hover:bg-red-600 transition"
                >
                    Reset
                </button>
            </div>
        </div>
    </x-layoutstudy>

    <script>
        function timerComponent() {
            return {
                originalTime: 25 * 60,
                time: 25 * 60,
                interval: null,
                hasStarted: false,
                isPaused: false,

                get formattedTime() {
                    const hrs = String(Math.floor(this.time / 3600)).padStart(2, '0');
                    const mins = String(Math.floor((this.time % 3600) / 60)).padStart(2, '0');
                    const secs = String(this.time % 60).padStart(2, '0');
                    return `${hrs}:${mins}:${secs}`;
                },

                start() {
                    if (this.interval) return;

                    this.hasStarted = true;
                    this.isPaused = false;

                    this.interval = setInterval(() => {
                        if (this.time > 0) {
                            this.time--;
                        } else {
                            clearInterval(this.interval);
                            this.interval = null;
                        }
                    }, 1000);
                },

                pause() {
                    clearInterval(this.interval);
                    this.interval = null;
                    this.isPaused = true;
                },

                reset() {
                    clearInterval(this.interval);
                    this.interval = null;
                    this.time = this.originalTime;
                    this.hasStarted = false;
                }
            }
        }
    </script>
</x-layout> --}}

{{-- views/pomodoro.blade.php --}}
<x-layout title="Pomodoro Session">
    <div class="max-w-4xl mx-auto mt-20 text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Subject: {{ $study->subject_name }}</h1>
        <p class="text-gray-500 mb-10">Selesaikan sesi belajarmu dengan teknik Pomodoro.</p>

        <div id="timer-container" class="hidden mb-10">
            <h2 id="status-label" class="text-xl font-semibold mb-4 text-[#c9a348]">Focus Time</h2>
            <div class="text-8xl font-mono font-bold text-gray-900 mb-8" id="timer-display">
                00:00
            </div>
            <button onclick="endSession()"
                class="px-8 py-3 bg-red-500 text-white rounded-full font-bold hover:bg-red-600 transition">
                End Session
            </button>
        </div>

        <div id="start-button-container">
            <button type="button" onclick="toggleModal('modal-settings')"
                class="w-64 h-64 bg-[#c9a348] text-white rounded-full shadow-xl hover:scale-105 transition-transform flex flex-col items-center justify-center mx-auto">
                <span class="text-2xl font-bold">Start Study</span>
                <span class="text-sm opacity-80 mt-2">Set your timer</span>
            </button>
        </div>
    </div>

    <div id="modal-settings"
        class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-8">
            <h2 class="text-xl font-bold mb-6 text-center">Set Timer Duration</h2>

            <form id="form-pomodoro-settings">
                @csrf
                <input type="hidden" name="study_id" value="{{ $study->study_id }}">

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1">Focus Time (Min)</label>
                    <input type="number" name="focus_time" id="focus_time" min="1" max="60" value="25"
                        required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#c9a348] outline-none">
                    <p class="text-xs text-gray-400 mt-1">Range: 1 - 60 minutes</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-1">Rest Time (Min)</label>
                    <input type="number" name="rest_time" id="rest_time" min="1" max="60" value="5"
                        required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#c9a348] outline-none">
                    <p class="text-xs text-gray-400 mt-1">Range: 1 - 60 minutes</p>
                </div>

                <div class="flex flex-col gap-2">
                    <button type="button" onclick="startSession()"
                        class="w-full py-3 bg-[#c9a348] text-white rounded-lg font-bold hover:bg-[#b89237] transition">
                        Start Session
                    </button>
                    <button type="button" onclick="toggleModal('modal-settings')"
                        class="w-full py-2 text-gray-500 hover:text-gray-700 font-medium text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>

<script>
    let timerInterval;
    let isFocus = true; // Status awal adalah Focus
    let currentFocusMinutes;
    let currentRestMinutes;

    function toggleModal(modalID) {
        document.getElementById(modalID).classList.toggle('hidden');
    }

    async function startSession() {
        const form = document.getElementById('form-pomodoro-settings');
        const formData = new FormData(form);

        // 1. Simpan ke database via AJAX
        try {
            const response = await fetch("{{ route('pomodoro.store') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (response.ok) {
                // 2. Jika sukses, tutup modal dan sembunyikan tombol start
                toggleModal('modal-settings');
                document.getElementById('start-button-container').classList.add('hidden');
                document.getElementById('timer-container').classList.remove('hidden');

                // 3. Jalankan Logic Timer
                const focusMinutes = document.getElementById('focus_time').value;
                const restMinutes = document.getElementById('rest_time').value;

                runTimer(focusMinutes, restMinutes);
            }
        } catch (error) {
            alert('Gagal memulai session. Coba lagi.');
        }
    }

    function runTimer(focus, rest) {
        // Simpan durasi ke variabel global agar bisa diakses saat switch
        currentFocusMinutes = parseInt(focus);
        currentRestMinutes = parseInt(rest);

        // Mulai dengan sesi Focus
        isFocus = true;
        startCountdown(currentFocusMinutes * 60);
    }

    function startCountdown(seconds) {
        clearInterval(timerInterval); // Bersihkan interval sebelumnya jika ada

        updateDisplay(seconds);
        updateStatusLabel();

        timerInterval = setInterval(() => {
            seconds--;
            updateDisplay(seconds);

            // Jika waktu habis
            if (seconds <= 0) {
                clearInterval(timerInterval);

                // Switch status: Jika tadi Focus, sekarang Rest. Jika tadi Rest, sekarang Focus.
                isFocus = !isFocus;

                // Putar suara notifikasi (Opsional)
                playNotification();

                // Jalankan sesi berikutnya secara otomatis (Looping)
                let nextDuration = isFocus ? currentFocusMinutes : currentRestMinutes;
                startCountdown(nextDuration * 60);
            }
        }, 1000);
    }

    function updateDisplay(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainderSeconds = seconds % 60;

        // Format agar selalu 2 digit (00:00)
        const display = `${minutes}:${remainderSeconds < 10 ? '0' : ''}${remainderSeconds}`;

        // Update di halaman
        document.getElementById('timer-display').innerText = display;

        // Update di Judul Tab Browser (agar user bisa pantau walau pindah tab)
        document.title = `(${display}) ${isFocus ? 'Focus' : 'Rest'} | Pomodoro`;
    }

    function updateStatusLabel() {
        const label = document.getElementById('status-label');
        const container = document.getElementById('timer-container');

        if (isFocus) {
            label.innerText = "🚀 Focus Time";
            label.style.color = "#c9a348"; // Warna emas
        } else {
            label.innerText = "☕ Rest Time";
            label.style.color = "#10b981"; // Warna hijau (emerald)
        }
    }

    function playNotification() {
        // Kamu bisa ganti URL ini dengan file audio lokal di /public/sounds/
        const audio = new Audio('https://actions.google.com/sounds/v1/alarms/beep_short.ogg');
        audio.play().catch(e => console.log("Audio play blocked by browser"));
    }

    function endSession() {
    if (confirm('Akhiri sesi belajar sekarang?')) {
        window.location.href = "/study"; // Sesuaikan dengan route halaman depanmu
    }
}
</script>
