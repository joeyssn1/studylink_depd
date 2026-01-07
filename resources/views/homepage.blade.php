<x-layout title="Home">

    <!-- ================= HERO ================= -->
    <section class="max-w-6xl mx-auto mt-14 px-4 grid md:grid-cols-2 gap-10 items-center animate-fade-in-up">
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                Focus Better.<br>
                <span class="text-red-500">Remember More.</span> 🍅
            </h1>

            <p class="mt-5 text-gray-600 text-lg">
                Combine the power of <strong>Active Recall</strong> and
                <strong>Pomodoro Technique</strong> to study smarter, not longer.
            </p>

            <div class="mt-6 flex gap-4">
                {{-- <a href="#calendar" --}}
                <a href="{{ route('studypage') }}"
                    class="px-6 py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white font-bold shadow-md transition">
                    Start Study
                </a>

                <a href="#calendar"
                    class="px-6 py-3 rounded-xl border-2 border-gray-300 hover:bg-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition">
                    My Events
                </a>
            </div>
        </div>

        <div class="flex justify-center">
            <div class="relative w-64 h-64 rounded-full bg-red-200 flex items-center justify-center shadow-lg">
                <div class="w-40 h-40 rounded-full bg-red-500 flex items-center justify-center shadow-inner">
                    <img src="{{ asset('images/StudyLink_Logo_3.svg') }}" alt="StudyLink Logo"
                        class="w-36 h-36 object-contain">
                </div>
            </div>
        </div>

    </section>

    <!-- ================= TECHNIQUES ================= -->
    <section class="max-w-6xl mx-auto mt-20 px-4">
        <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">
            Study Techniques 🧠
        </h2>

        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-3xl shadow-md hover:shadow-lg transition">
                <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center text-2xl mb-4">
                    🧠
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Active Recall</h3>
                <p class="text-gray-600">
                    Strengthen long-term memory by actively retrieving information
                    instead of rereading notes.
                </p>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-md hover:shadow-lg transition">
                <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center text-2xl mb-4">
                    🍅
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Pomodoro Technique</h3>
                <p class="text-gray-600">
                    Work in focused intervals with breaks to maintain energy and consistency.
                </p>
            </div>
        </div>
    </section>

    <!-- ================= STATS ================= -->
    <section class="max-w-6xl mx-auto mt-20 px-4">
        <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">
            Your Progress 📊
        </h2>

        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-3xl border shadow-sm flex items-center gap-6">
                <div class="w-14 h-14 bg-red-400 rounded-full flex items-center justify-center text-white text-2xl">
                    🚀
                </div>
                <div>
                    {{-- <p class="text-gray-500 text-sm font-medium">Pomodoro Sessions</p>
                    <h3 class="text-3xl font-extrabold text-gray-900">{{ $pomodoro_count }}</h3> --}}
                    <p class="text-gray-500 text-sm font-medium">Active Recall Sessions</p>
                    <h3 class="text-3xl font-extrabold text-gray-900">{{ $active_count }}</h3>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl border shadow-sm flex items-center gap-6">
                <div class="w-14 h-14 bg-yellow-400 rounded-full flex items-center justify-center text-white text-2xl">
                    🧠
                </div>
                <div>
                    {{-- <p class="text-gray-500 text-sm font-medium">Active Recall Sessions</p>
                    <h3 class="text-3xl font-extrabold text-gray-900">{{ $active_count }}</h3> --}}
                    <p class="text-gray-500 text-sm font-medium">Pomodoro Sessions</p>
                    <h3 class="text-3xl font-extrabold text-gray-900">{{ $pomodoro_count }}</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FLASH ================= -->
    @if (session('success'))
        <div class="max-w-5xl mx-auto px-4 mt-6">
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl shadow-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-5xl mx-auto px-4 mt-6">
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl shadow-sm">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- ================= CALENDAR ================= -->
    <section id="calendar" class="max-w-6xl mx-auto mt-20 px-4 mb-24">
        <div class="bg-white border rounded-3xl shadow-xl p-6 md:p-10">

            <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">
                Study Calendar 🗓️
            </h2>

            <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-6">
                <div class="flex items-center gap-6">
                    <button onclick="prevMonth()" class="w-10 h-10 rounded-full bg-gray-50 hover:bg-green-200">
                        <i class="ri-arrow-left-s-line text-xl"></i>
                    </button>

                    <div class="relative flex items-center gap-2">

                        <!-- MONTH -->
                        <button id="monthBtn" class="text-2xl font-bold hover:text-green-600 transition">
                            January
                        </button>

                        <!-- YEAR -->
                        <button id="yearBtn" class="text-2xl font-bold hover:text-green-600 transition">
                            2026
                        </button>

                        <!-- MONTH DROPDOWN -->
                        <div id="monthDropdown"
                            class="hidden absolute top-12 left-0 bg-white border rounded-xl shadow-lg grid grid-cols-3 gap-2 p-4 z-50 w-72">
                        </div>


                        <!-- YEAR DROPDOWN -->
                        <div id="yearDropdown"
                            class="hidden absolute top-12 left-0 bg-white border rounded-xl shadow-lg grid grid-cols-3 gap-2 p-4 z-50 w-60">
                        </div>

                    </div>

                    <button onclick="nextMonth()" class="w-10 h-10 rounded-full bg-gray-50 hover:bg-green-200">
                        <i class="ri-arrow-right-s-line text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('events.join') }}" method="POST" class="flex gap-2 w-full md:w-auto">
                    @csrf
                    <input type="text" name="code" maxlength="6" placeholder="ENTER CODE"
                        class="border px-4 py-2 rounded-lg text-center tracking-widest font-mono w-full md:w-40">
                    <button class="bg-gray-900 text-white px-6 py-2 rounded-lg font-bold">JOIN</button>
                </form>
            </div>

            <div class="grid grid-cols-7 text-center mb-4">
                @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $d)
                    <div class="text-xs font-bold text-gray-400 uppercase py-2">{{ $d }}</div>
                @endforeach
            </div>

            <div id="calendarDays" class="grid grid-cols-7 gap-2 md:gap-4"></div>
        </div>
    </section>

    <!-- ================= MODAL ================= -->
    <div id="dayModal" class="fixed inset-0 hidden items-center justify-center bg-black/60 z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-zoom-in">
            <div class="px-6 py-4 flex justify-between border-b">
                <h3 id="modalTitle" class="font-bold text-lg"></h3>
                <button onclick="closeDayModal()"><i class="ri-close-line text-xl"></i></button>
            </div>
            <div id="dayContent" class="p-4 max-h-[60vh] overflow-y-auto bg-gray-50"></div>
        </div>
    </div>

    <!-- ================= SCRIPT ================= -->
    <script>
        let currentDate = new Date();
        const events = @json($joinedEvents);
        const authUserId = {{ auth()->id() ?? 'null' }};

        const monthBtn = document.getElementById('monthBtn');
        const yearBtn = document.getElementById('yearBtn');
        const monthDropdown = document.getElementById('monthDropdown');
        const yearDropdown = document.getElementById('yearDropdown');

        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        /* =====================
           MONTH NAVIGATION
        ====================== */
        function prevMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        }

        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        }

        /* =====================
           CALENDAR RENDER
        ====================== */
        function renderCalendar() {
            const calendarDays = document.getElementById('calendarDays');

            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const today = new Date();

            monthBtn.innerText = monthNames[month];
            yearBtn.innerText = year;

            calendarDays.innerHTML = "";

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            // Empty cells before first day
            for (let i = 0; i < firstDay; i++) {
                calendarDays.innerHTML += `<div></div>`;
            }

            // Days
            for (let day = 1; day <= daysInMonth; day++) {
                const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const dayEvents = events[dateKey] ? Object.values(events[dateKey]) : [];

                const isToday =
                    day === today.getDate() &&
                    month === today.getMonth() &&
                    year === today.getFullYear();

                calendarDays.innerHTML += `
                <div onclick="openDay('${dateKey}')"
                     class="h-24 md:h-32 rounded-xl p-3 cursor-pointer
                     ${isToday ? 'bg-green-100' : 'bg-gray-50 hover:bg-white'}
                     border transition">
                    <div class="font-bold mb-1">${day}</div>
                    ${dayEvents.length ? `
                                <div class="flex gap-1 mt-1">
                                    ${dayEvents.slice(0,3).map(() =>
                                        `<span class="w-2 h-2 bg-red-500 rounded-full"></span>`
                                    ).join('')}
                                </div>` : ''}
                </div>`;
            }
        }

        /* =====================
           DAY MODAL
        ====================== */
        function openDay(date) {
            const modal = document.getElementById('dayModal');
            const title = document.getElementById('modalTitle');
            const content = document.getElementById('dayContent');

            title.innerText = `Events on ${date}`;
            content.innerHTML = "";

            const dayEvents = events[date] ? Object.values(events[date]) : [];

            if (!dayEvents.length) {
                content.innerHTML = `<p class="text-center text-gray-500 py-10">No events</p>`;
            } else {
                dayEvents.forEach(event => {
                    const isCreator = authUserId === event.user_id;

                    content.innerHTML += `
                    <div class="bg-white border rounded-xl p-4 mb-3">
                        <div class="flex justify-between mb-2">
                            <strong>${event.event_name}</strong>
                            <span class="text-xs px-2 py-1 rounded-full ${
                                isCreator ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'
                            }">
                                ${isCreator ? 'Creator' : 'Joined'}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">⏰ ${event.start_time} - ${event.end_time}</p>
                        <p class="text-sm text-gray-700 mt-2">${event.description}</p>

                        <div class="text-center border-t mt-3 pt-3">
                            ${
                                isCreator
                                    ? `<a href="{{ route('profile') }}" class="text-green-600 font-semibold hover:underline">Manage Event</a>`
                                    : `<form action="/events/${event.id}/leave" method="POST"
                                                     onsubmit="return confirm('Remove this event?')">
                                                   @csrf
                                                   @method('DELETE')
                                                   <button class="text-red-600 font-semibold hover:underline">Remove Event</button>
                                               </form>`
                            }
                        </div>
                    </div>`;
                });
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDayModal() {
            const modal = document.getElementById('dayModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        /* =====================
           MONTH DROPDOWN
        ====================== */
        monthDropdown.innerHTML = monthNames.map((m, i) => `
        <button class="px-3 py-2 rounded-lg hover:bg-green-100 text-sm"
                onclick="selectMonth(${i})">
            ${m}
        </button>
    `).join('');

        function selectMonth(index) {
            currentDate.setMonth(index);
            monthDropdown.classList.add('hidden');
            renderCalendar();
        }

        monthBtn.addEventListener('click', () => {
            monthDropdown.classList.toggle('hidden');
            yearDropdown.classList.add('hidden');
        });

        /* =====================
           YEAR DROPDOWN
        ====================== */
        function renderYearDropdown() {
            const currentYear = currentDate.getFullYear();
            let years = [];

            for (let i = currentYear - 4; i <= currentYear + 4; i++) {
                years.push(i);
            }

            yearDropdown.innerHTML = years.map(y => `
            <button class="px-3 py-2 rounded-lg hover:bg-green-100 text-sm ${
                y === currentYear ? 'bg-green-50 font-bold' : ''
            }"
            onclick="selectYear(${y})">
                ${y}
            </button>
        `).join('');
        }

        function selectYear(year) {
            currentDate.setFullYear(year);
            yearDropdown.classList.add('hidden');
            renderCalendar();
        }

        yearBtn.addEventListener('click', () => {
            renderYearDropdown();
            yearDropdown.classList.toggle('hidden');
            monthDropdown.classList.add('hidden');
        });

        /* =====================
           OUTSIDE CLICK CLOSE
        ====================== */
        document.addEventListener('click', e => {
            if (
                !e.target.closest('#monthBtn') &&
                !e.target.closest('#yearBtn') &&
                !e.target.closest('#monthDropdown') &&
                !e.target.closest('#yearDropdown')
            ) {
                monthDropdown.classList.add('hidden');
                yearDropdown.classList.add('hidden');
            }
        });

        // Initial render
        renderCalendar();
    </script>

</x-layout>
