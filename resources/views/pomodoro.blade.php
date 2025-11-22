<x-layout title="Pomodoro">
    <x-layoutstudy studyTitle="Pomodoro">
        <div x-data="timerComponent()" class="flex flex-col items-center">
            <p class="text-lg mb-2 mt-8">Timer:</p>

            <h1 class="text-7xl font-bold tracking-wide mb-10" x-text="formattedTime"></h1>

            <div class="flex gap-4 mb-8">
                <button 
                    x-show="!hasStarted || isPaused"
                    x-on:click="start()" 
                    class="bg-yellow-400 text-white px-6 py-3 rounded-md hover:bg-yellow-500 transition"
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
                originalTime: 20 * 60,
                time: 20 * 60,
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
</x-layout>