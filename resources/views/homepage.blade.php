{{-- views/homepage.blade.php --}}

<x-layout title="Home">

    <section class="max-w-4xl mx-auto text-center mt-10 px-4 animate-fade-in-up">
        <h2 class="text-3xl font-bold text-gray-900">
            Improve Your Study <br> Techniques
        </h2>
        <p class="text-sm text-gray-600 mt-4">
            Learn effective methods to study smarter <br> and achieve better results
        </p>
    </section>

    <section class="max-w-5xl mx-auto mt-10 px-4">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Study Techniques</h3>

        <div class="grid grid-cols-2 gap-4 mt-6">
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
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded flex items-center gap-2">
                <i class="ri-checkbox-circle-line"></i> {{ session('success') }}
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="max-w-5xl mx-auto px-4 mt-4">
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded flex items-center gap-2">
                <i class="ri-error-warning-line"></i> {{ session('error') }}
            </div>
        </div>
    @endif


    <section class="max-w-5xl mx-auto mt-8 px-4 mb-20">
        <div class="bg-white border border-gray-200 rounded-3xl shadow-lg p-6 md:p-8">

            <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-6">
                
                <div class="flex items-center gap-6">
                    <button onclick="prevMonth()" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-50 text-gray-600 hover:bg-green-50 hover:text-green-600 transition shadow-sm">
                        <i class="ri-arrow-left-s-line text-xl"></i>
                    </button>

                    <span id="monthYear" class="text-2xl font-bold text-gray-800 min-w-[200px] text-center cursor-pointer select-none" onclick="toggleYearPicker()">
                    </span>

                    <button onclick="nextMonth()" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-50 text-gray-600 hover:bg-green-50 hover:text-green-600 transition shadow-sm">
                        <i class="ri-arrow-right-s-line text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('events.join') }}" method="POST" class="flex gap-2 w-full md:w-auto">
                    @csrf
                    <div class="relative w-full md:w-auto">
                        <input type="text" name="code" maxlength="6" placeholder="ENTER CODE"
                            class="pl-4 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 outline-none w-full md:w-40 text-center tracking-widest uppercase font-mono shadow-sm">
                    </div>
                    <button class="bg-gray-900 hover:bg-black text-white px-6 py-2 rounded-lg text-sm font-bold transition shadow-md whitespace-nowrap">
                        JOIN
                    </button>
                </form>
            </div>

            <div id="yearPicker" class="hidden grid grid-cols-5 gap-3 mb-6 p-4 bg-gray-50 rounded-2xl border max-h-56 overflow-y-auto custom-scrollbar">
            </div>

            <div class="grid grid-cols-7 text-center mb-4">
                <div class="text-xs font-bold text-gray-400 uppercase py-2 tracking-widest">Sun</div>
                <div class="text-xs font-bold text-gray-400 uppercase py-2 tracking-widest">Mon</div>
                <div class="text-xs font-bold text-gray-400 uppercase py-2 tracking-widest">Tue</div>
                <div class="text-xs font-bold text-gray-400 uppercase py-2 tracking-widest">Wed</div>
                <div class="text-xs font-bold text-gray-400 uppercase py-2 tracking-widest">Thu</div>
                <div class="text-xs font-bold text-gray-400 uppercase py-2 tracking-widest">Fri</div>
                <div class="text-xs font-bold text-gray-400 uppercase py-2 tracking-widest">Sat</div>
            </div>

            <div id="calendarDays" class="grid grid-cols-7 gap-2 md:gap-4">
                </div>
        </div>
    </section>

    <div id="dayModal" class="fixed inset-0 hidden items-center justify-center bg-gray-900/60 backdrop-blur-sm z-50 p-4 transition-all duration-300">
        
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform scale-100 animate-zoom-in">
            
            <div class="bg-white px-6 py-5 flex justify-between items-center border-b border-gray-100">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    </h3>
                <button onclick="closeDayModal()" class="text-gray-400 hover:text-gray-600 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-50 transition">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>

            <div id="dayContent" class="p-4 max-h-[60vh] overflow-y-auto custom-scrollbar bg-gray-50/50 min-h-[150px]">
                </div>
            
            <div class="bg-white p-4 border-t border-gray-100 text-center">
                <a href="{{ route('profile') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-green-600 hover:text-green-800 hover:underline transition">
                    Manage & Create Events in Profile <i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
    </div>


    <script>
        let currentDate = new Date();
        const events = @json($joinedEvents);

        // Palet warna untuk Dots
        const dotColors = [
            'bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 
            'bg-purple-500', 'bg-pink-500', 'bg-indigo-500'
        ];

        function renderCalendar() {
            const monthYear = document.getElementById('monthYear');
            const calendarDays = document.getElementById('calendarDays');

            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const today = new Date();

            const monthNames = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            monthYear.innerText = `${monthNames[month]} ${year}`;
            calendarDays.innerHTML = "";

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            // Spacer (Ukuran Besar Kembali)
            for (let i = 0; i < firstDay; i++) {
                calendarDays.innerHTML += `<div class="h-24 md:h-32"></div>`;
            }

            // Render Tanggal
            for (let day = 1; day <= daysInMonth; day++) {
                const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                
                // Normalisasi Data
                const rawEvents = events[dateKey] || [];
                const dayEvents = Array.isArray(rawEvents) ? rawEvents : Object.values(rawEvents);
                const hasEvent = dayEvents.length > 0;
                
                const isToday = day === today.getDate() && month === today.getMonth() && year === today.getFullYear();

                // Style Dasar (UKURAN BESAR h-32 & rounded-2xl)
                let containerClass = "h-24 md:h-32 rounded-2xl border border-transparent p-2 flex flex-col items-center justify-start cursor-pointer transition-all duration-200 group relative overflow-hidden";
                let numberClass = "w-8 h-8 flex items-center justify-center rounded-full text-sm font-semibold z-10 transition mb-1";

                if (isToday) {
                    containerClass += " bg-white shadow-md ring-2 ring-green-400 transform scale-105 z-20";
                    numberClass += " bg-green-600 text-white shadow-lg shadow-green-200";
                } else if (hasEvent) {
                    containerClass += " bg-green-50/30 hover:bg-green-50 border-green-100 hover:shadow-md";
                    numberClass += " text-green-800 bg-green-200/50";
                } else {
                    containerClass += " bg-gray-50/50 hover:bg-white hover:shadow-md hover:border-gray-100 text-gray-400";
                    numberClass += " group-hover:bg-gray-100 group-hover:text-gray-600";
                }

                // Render DOTS (Maksimal 3)
                let dotsHtml = "";
                if (hasEvent) {
                    dotsHtml = `<div class="flex gap-1 justify-center w-full px-1 mb-2 mt-1">`;
                    
                    const maxDots = 3;
                    const displayEvents = dayEvents.slice(0, maxDots);
                    
                    displayEvents.forEach((evt, index) => {
                        const colorClass = dotColors[index % dotColors.length];
                        dotsHtml += `<span class="w-1.5 h-1.5 rounded-full ${colorClass} ring-1 ring-white"></span>`;
                    });

                    if (dayEvents.length > maxDots) {
                        dotsHtml += `<span class="text-[9px] leading-none text-gray-400 flex items-center ml-0.5">+</span>`;
                    }
                    dotsHtml += `</div>`;
                    
                    // Preview Teks (Hanya di layar besar)
                    const firstEventName = dayEvents[0].event_name;
                    dotsHtml += `
                        <div class="hidden md:block w-full px-1 mt-auto pb-1">
                             <p class="text-[10px] text-center truncate text-green-800 bg-white/80 border border-green-100 rounded-md px-1 py-1 shadow-sm font-medium">
                                ${firstEventName}
                            </p>
                        </div>
                    `;
                }

                calendarDays.innerHTML += `
                    <div onclick="openDay('${dateKey}')" class="${containerClass}">
                        <span class="${numberClass}">${day}</span>
                        ${dotsHtml}
                    </div>
                `;
            }
        }

        // --- POPUP LOGIC ---
        function openDay(date) {
            const modal = document.getElementById('dayModal');
            const content = document.getElementById('dayContent');
            const title = document.getElementById('modalTitle');
            
            const dateObj = new Date(date);
            title.innerHTML = `<i class="ri-calendar-event-fill text-green-600"></i> ${dateObj.toLocaleDateString('en-US', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })}`;

            const rawEvents = events[date] || [];
            const dayEvents = Array.isArray(rawEvents) ? rawEvents : Object.values(rawEvents);

            let html = "";
            if (dayEvents.length > 0) {
                dayEvents.forEach((e, idx) => {
                    const colorBorder = dotColors[idx % dotColors.length].replace('bg-', 'border-'); 
                    
                    html += `
                    <div class="mb-3 bg-white border-l-4 ${colorBorder} rounded-r-xl shadow-sm border-y border-r border-gray-100 p-4 hover:shadow-md transition group">
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold text-gray-800 text-base leading-tight group-hover:text-green-700 transition">${e.event_name}</h4>
                            <span class="text-[11px] font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded-md tracking-wide font-mono">${e.time}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 leading-relaxed">${e.description}</p>
                    </div>
                    `;
                });
            } else {
                html = `
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm border border-gray-100">
                            <i class="ri-calendar-line text-3xl text-gray-300"></i>
                        </div>
                        <p class="text-sm text-gray-500 font-medium">No events scheduled.</p>
                        <p class="text-xs text-gray-400 mt-1">Check your profile to add events.</p>
                    </div>
                `;
            }

            content.innerHTML = html;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
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
            const picker = document.getElementById('yearPicker');
            if(picker.classList.contains('hidden')){
                picker.innerHTML = "";
                const currentYear = new Date().getFullYear();
                for(let y = currentYear - 5; y <= currentYear + 5; y++){
                    picker.innerHTML += `<div onclick="selectYear(${y})" class="text-center p-3 cursor-pointer hover:bg-green-50 text-gray-600 rounded-xl text-sm font-bold transition border border-transparent hover:border-green-100">${y}</div>`;
                }
                picker.classList.remove('hidden');
            } else {
                picker.classList.add('hidden');
            }
        }

        function selectYear(y) {
            currentDate.setFullYear(y);
            document.getElementById('yearPicker').classList.add('hidden');
            renderCalendar();
        }

        document.getElementById('dayModal').addEventListener('click', function(e) {
            if (e.target === this) closeDayModal();
        });

        document.addEventListener('click', function(e) {
            const picker = document.getElementById('yearPicker');
            const monthYear = document.getElementById('monthYear');
            if (!picker.contains(e.target) && !monthYear.contains(e.target)) {
                picker.classList.add('hidden');
            }
        });

        renderCalendar();
    </script>
    
    <style>
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        .animate-zoom-in { animation: zoomIn 0.2s ease-out forwards; }
        @keyframes zoomIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    </style>

</x-layout>