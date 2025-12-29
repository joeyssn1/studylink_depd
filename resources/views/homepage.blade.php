{{-- views/homepage.blade.php --}}

<x-layout title="Home">

    <!-- HERO SECTION -->
    <section class="max-w-4xl mx-auto text-center mt-10 px-4">
        <h2 class="text-3xl font-bold text-gray-900">
            Improve Your Study <br> Techniques
        </h2>
        <p class="text-sm text-gray-600 mt-4">
            Learn effective methods to study smarter <br> and achieve better results
        </p>
    </section>

    <!-- STUDY TECHNIQUES -->
    <section class="max-w-5xl mx-auto mt-10 px-4">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Study Techniques</h3>

        <div class="grid grid-cols-3 gap-4 mt-6">

            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-yellow-200 rounded-full mb-3"></div>
                <p class="text-sm font-semibold">Active Recall</p>
                <p class="text-gray-600 text-xs mt-2">
                    Improves memory by actively retrieving information.
                </p>
            </div>

            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-yellow-200 rounded-full mb-3"></div>
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-2xl border shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-[#c9a348] rounded-full flex items-center justify-center text-white">
                    🚀
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pomodoro Sessions</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $pomodoro_count }}</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-[#c9a348] rounded-full flex items-center justify-center text-white">
                    🧠
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium">Active Recall Sessions</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $active_count }}</h3>
                </div>
            </div>
    </section>

    @if (session('success'))
        <div class="max-w-5xl mx-auto px-4 mt-4">
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-5xl mx-auto px-4 mt-4">
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- CALENDAR -->
    <section class="max-w-5xl mx-auto mt-8 px-4 mb-10">
        <div class="bg-white border rounded-lg shadow p-4">

            <!-- Calendar Header -->
            <div class="flex items-center justify-between mb-4">

                <div class="flex items-center gap-2">
                    <button onclick="prevMonth()" class="text-2xl">
                        <i class="ri-arrow-down-s-fill"></i>
                    </button>

                    <span id="monthYear" class="font-semibold cursor-pointer" onclick="toggleYearPicker()">
                    </span>

                    <button onclick="nextMonth()" class="text-2xl">
                        <i class="ri-arrow-up-s-fill"></i>
                    </button>
                </div>

                <div class="flex gap-2">
                    <form action="{{ route('events.join') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="code" maxlength="6" placeholder="Add Code"
                            class="border px-2 py-1 rounded text-sm" required>
                        <button class="bg-green-600 text-white px-3 py-1 rounded text-sm">
                            Add
                        </button>
                    </form>
                </div>
            </div>

            <!-- Year Picker -->
            <div id="yearPicker" class="hidden border rounded p-2 mb-4 max-h-32 overflow-y-auto text-sm">
            </div>

            <!-- Weekdays -->
            <div class="grid grid-cols-7 text-center font-semibold text-sm mb-2">
                <div>Sun</div>
                <div>Mon</div>
                <div>Tue</div>
                <div>Wed</div>
                <div>Thu</div>
                <div>Fri</div>
                <div>Sat</div>
            </div>

            <!-- Calendar Days -->
            <div id="calendarDays" class="grid grid-cols-7 gap-2 text-center text-sm">
            </div>
        </div>
    </section>

    <!-- DAY MODAL (TRANSLUCENT) -->
    <div id="dayModal"
        class="fixed inset-0 hidden items-center justify-center
            bg-white/60 backdrop-blur-sm z-50">

        <div class="bg-white w-[90%] max-w-md
                rounded-xl shadow-xl p-6 relative">

            <button onclick="closeDayModal()" class="absolute top-4 right-4 text-gray-500">
                <i class="ri-close-line text-2xl"></i>
            </button>

            <div id="dayContent" class="text-sm"></div>

        </div>
    </div>


    <!-- JAVASCRIPT -->
    <script>
        let currentDate = new Date();

        const events = @json($joinedEvents);

        function renderCalendar() {
            const monthYear = document.getElementById('monthYear');
            const calendarDays = document.getElementById('calendarDays');

            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            const monthNames = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            monthYear.innerText = `${monthNames[month]} ${year}`;
            calendarDays.innerHTML = "";

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            for (let i = 0; i < firstDay; i++) {
                calendarDays.innerHTML += `<div></div>`;
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const hasEvent = events[dateKey];
                const dayClass = hasEvent ?
                    'bg-green-500 text-white' :
                    'hover:bg-gray-100';

                calendarDays.innerHTML += `
                    <div onclick="openDay('${dateKey}')"
                         class="p-2 rounded cursor-pointer border ${dayClass}">
                        ${day}
                    </div>
                `;
            }
        }

        function openDay(date) {
            const modal = document.getElementById('dayModal');
            const content = document.getElementById('dayContent');

            if (events[date]) {
                let html = `<strong>${date}</strong><br><br>`;

                events[date].forEach(e => {
                    html += `
                <div class="mb-2 p-2 border rounded">
                    <strong>${e.event_name}</strong><br>
                    ${e.time}<br>
                    ${e.description}
                </div>
            `;
                });

                content.innerHTML = html;
            } else {
                content.innerHTML = `<strong>${date}</strong><br>No events on this day.`;
            }

            modal.classList.remove('hidden');
        }

        function closeDayModal() {
            const modal = document.getElementById('dayModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }


        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        }

        function prevMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        }

        function toggleYearPicker() {
            document.getElementById('yearPicker').classList.toggle('hidden');
        }

        function renderYearPicker() {
            const picker = document.getElementById('yearPicker');
            picker.innerHTML = "";
            const currentYear = new Date().getFullYear();

            for (let y = currentYear - 10; y <= currentYear + 10; y++) {
                picker.innerHTML += `
                    <div onclick="selectYear(${y})"
                         class="cursor-pointer px-2 py-1 hover:bg-gray-200 rounded">
                        ${y}
                    </div>
                `;
            }
        }

        function selectYear(year) {
            currentDate.setFullYear(year);
            document.getElementById('yearPicker').classList.add('hidden');
            renderCalendar();
        }

        document.addEventListener('click', function(e) {
            const picker = document.getElementById('yearPicker');
            const monthYear = document.getElementById('monthYear');
            if (!picker.contains(e.target) && !monthYear.contains(e.target)) {
                picker.classList.add('hidden');
            }
        });

        renderYearPicker();
        renderCalendar();
    </script>

</x-layout>
