{{-- views/studypage.blade.php --}}

<x-layout title="Study">

    <section class="max-w-4xl mx-auto text-center mt-10 mb-8 px-4">
        <h2 class="text-3xl font-bold text-gray-900 leading-snug">
            What type of <br> study would you use today, <br>
            {{-- Memanggil nama user dari table users --}}
            <span class="font-bold">{{ auth()->user()->fullname }}?</span>
        </h2>
    </section>

    <!-- Study Options -->
    <section class="max-w-5xl mx-auto mt-10 mb-8 px-4">

        <div class="justify-center items-center flex flex-col gap-8 place-items-center">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- active recall -->
                <button type="button" onclick="toggleModal('modal-active-recall')"
                    class="w-64 h-64 border shadow-sm flex flex-col items-center justify-center hover:shadow-md transition bg-white">
                    <div class="w-12 h-12 bg-[#c9a348] rounded-full mb-4 mt-8">
                        <span class="text-yellow-600 font-bold"></span>
                    </div>
                    <p class="text-lg font-semibold text-center">Active Recall</p>
                    <p class="text-sm text-gray-500 mt-2 px-4">
                        Mengingat kembali informasi yang telah dipelajari sebelumnya eengan bantuan AI partner.
                    </p>
                </button>

                <!-- pomodoro -->
                <button type="button" onclick="toggleModal('modal-pomodoro')"
                    class="w-64 h-64 border shadow-sm flex flex-col items-center justify-center hover:shadow-md transition col-span-2 bg-white">
                    <div class="w-12 h-12 bg-[#c9a348] rounded-full mb-4 mt-8">
                        <span class="text-yellow-600 font-bold"></span>
                    </div>
                    <p class="text-lg font-semibold text-center">Pomodoro</p>
                    <p class="text-sm text-gray-500 mt-2 px-4">
                        Teknik manajemen waktu yang membagi sesi belajar menjadi interval fokus dan istirahat.
                    </p>
                </button>
            </div>


        </div>

    </section>

    <!-- pomodoro -->
    <div id="modal-pomodoro"
        class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-bold mb-4">Mulai Sesi Pomodoro</h2>

            <form action="{{ route('study.store') }}" method="POST">
                @csrf
                <input type="hidden" name="study_type" value="Pomodoro">

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Apa yang ingin kamu pelajari?
                    </label>
                    <input type="text" name="subject_name" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#c9a348]"
                        placeholder="Contoh: Belajar Laravel Dasar">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggleModal('modal-pomodoro')"
                        class="px-4 py-2 text-gray-500 hover:text-gray-700 font-semibold">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-[#c9a348] text-white rounded-lg hover:bg-[#b89237] font-semibold transition">
                        Lanjut ke Page Pomodoro
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- active recall -->
    <div id="modal-active-recall"
        class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-bold mb-4">Mulai Sesi Active Recall</h2>

            <form action="{{ route('study.store') }}" method="POST">
                @csrf
                <input type="hidden" name="study_type" value="ActiveRecall">

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Subjek atau Topik Recall
                    </label>
                    <input type="text" name="subject_name" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#c9a348]"
                        placeholder="Contoh: Anatomi Tubuh Manusia">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggleModal('modal-active-recall')"
                        class="px-4 py-2 text-gray-500 hover:text-gray-700 font-semibold">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-[#c9a348] text-white rounded-lg  hover:bg-[#b89237] font-semibold transition">
                        Mulai Belajar
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layout>


{{-- 4. TARUH SCRIPT DI SINI (Di luar layout agar bersih) --}}
<script>
    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        if (modal) {
            // Menghapus 'hidden' untuk memunculkan, menambah 'hidden' untuk menyembunyikan
            modal.classList.toggle('hidden');
        }
    }

    // Supaya kalau klik di luar kotak putih, modalnya tertutup
    window.onclick = function(event) {
        const modalPomodoro = document.getElementById('modal-pomodoro');
        const modalRecall = document.getElementById('modal-active-recall');

        if (event.target == modalPomodoro) {
            toggleModal('modal-pomodoro');
        }
        if (event.target == modalRecall) {
            toggleModal('modal-active-recall');
        }
    }
</script>
