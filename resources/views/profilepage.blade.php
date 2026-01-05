<x-layout title="Profile">

    <!-- PROFILE HEADER -->
    <section class="max-w-4xl mx-auto mt-16 px-4">
        <div class="bg-white border rounded-2xl shadow-sm">

            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">

                    <!-- AVATAR -->
                    <div
                        class="w-24 h-24 rounded-2xl bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center shadow-inner shrink-0">
                        <i class="ri-user-fill text-4xl text-green-700"></i>
                    </div>

                    <!-- INFO -->
                    <div class="flex-1 text-center md:text-left">
                        <p class="text-xl font-bold text-gray-800">
                            Hello, {{ auth()->user()->username }} 👋
                        </p>

                        <p class="text-gray-500 mt-1">
                            Manage your events & calendar in one place
                        </p>

                        <div class="flex justify-center md:justify-start gap-4 mt-4 text-sm">
                            <div class="bg-gray-50 px-4 py-2 rounded-lg border">
                                📅 <span class="font-semibold">{{ $events->count() }}</span> events
                            </div>
                        </div>
                    </div>

                    <!-- ACTION -->
                    <div class="w-full md:w-auto">
                        <button onclick="openAddEvent()"
                            class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-bold shadow-md transition">
                            <i class="ri-add-line"></i>
                            Add New Event
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- FLASH MESSAGES -->
    <section class="max-w-4xl mx-auto mt-6 px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-4">
                {{ session('error') }}
            </div>
        @endif
    </section>

    <!-- EVENTS LIST -->
    <section class="max-w-4xl mx-auto mt-10 px-4 mb-28">

        <h3 class="text-2xl font-bold mb-6 text-gray-900">
            Your Events 📅
        </h3>

        @if ($events->isEmpty())
            <div class="bg-white border rounded-2xl p-10 text-center text-gray-500">
                No events created yet.
            </div>
        @else
            <div class="grid grid-cols-1 gap-6">
                @foreach ($events as $event)
                    <div class="bg-white border rounded-2xl p-6 shadow-sm hover:shadow-md transition">

                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">
                                    {{ $event->event_name }}
                                </h4>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                                    • {{ $event->start_time }} - {{ $event->end_time }}
                                </p>
                            </div>

                            <span class="text-xs font-bold px-3 py-1 rounded-full bg-green-100 text-green-700">
                                EVENT
                            </span>
                        </div>

                        <p class="mt-4 text-gray-700 text-sm leading-relaxed">
                            {{ $event->description }}
                        </p>

                        <div class="mt-5 text-sm">
                            <span class="font-semibold text-gray-700">Join Code:</span>
                            <span class="ml-2 inline-block bg-gray-100 px-3 py-1 rounded-lg font-mono select-all">
                                {{ $event->code }}
                            </span>
                        </div>

                        <div class="flex gap-2 border-t pt-4 mt-5">
                            <button onclick='openEditEvent(@json($event))'
                                class="px-4 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold transition">
                                Edit
                            </button>

                            <form action="{{ route('events.destroy', $event->id) }}" method="POST"
                                onsubmit="return confirm('Delete this event?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="px-4 py-2 text-sm rounded-lg bg-red-100 hover:bg-red-200 text-red-700 font-semibold transition">
                                    Delete
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </section>

    <!-- GLOBAL MODAL -->
    <div id="globalModal" class="fixed inset-0 hidden items-center justify-center bg-black/50 backdrop-blur-sm z-50">
        <div class="bg-white w-[90%] max-w-xl rounded-2xl shadow-2xl p-6 relative animate-fade-in">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                <i class="ri-close-line text-2xl"></i>
            </button>

            <div id="modalContent"></div>
        </div>
    </div>

    <!-- ADD EVENT TEMPLATE -->
    <div id="addEventTemplate" class="hidden">
        <h2 class="text-xl font-bold mb-5">Create New Event</h2>

        <form action="{{ route('events.store') }}" method="POST" class="space-y-4">
            @csrf

            <input type="text" name="event_name" class="w-full border rounded-xl px-4 py-2" placeholder="Event name"
                required>

            <input type="date" name="date" class="w-full border rounded-xl px-4 py-2" required>

            <div class="grid grid-cols-2 gap-3">
                <input type="time" name="start_time" class="w-full border rounded-xl px-4 py-2" required>
                <input type="time" name="end_time" class="w-full border rounded-xl px-4 py-2" required>
            </div>

            <textarea name="description" rows="4" class="w-full border rounded-xl px-4 py-2" placeholder="Event description"
                required></textarea>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg border">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-bold">
                    Create Event
                </button>
            </div>
        </form>
    </div>

    <!-- EDIT EVENT TEMPLATE -->
    <div id="editEventTemplate" class="hidden">
        <h2 class="text-xl font-bold mb-5">Edit Event</h2>

        <form id="editForm" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <input id="edit_name" type="text" name="event_name" class="w-full border rounded-xl px-4 py-2" required>
            <input id="edit_date" type="date" name="date" class="w-full border rounded-xl px-4 py-2" required>

            <div class="grid grid-cols-2 gap-3">
                <input id="edit_start" type="time" name="start_time" class="w-full border rounded-xl px-4 py-2"
                    required>
                <input id="edit_end" type="time" name="end_time" class="w-full border rounded-xl px-4 py-2"
                    required>
            </div>

            <textarea id="edit_desc" name="description" rows="4" class="w-full border rounded-xl px-4 py-2" required></textarea>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg border">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- SCRIPT -->
    <script>
        function openAddEvent() {
            openModal(document.getElementById('addEventTemplate').innerHTML);
        }

        function openEditEvent(event) {
            openModal(document.getElementById('editEventTemplate').innerHTML);

            document.getElementById('edit_name').value = event.event_name;
            document.getElementById('edit_date').value = event.date;
            document.getElementById('edit_start').value = event.start_time;
            document.getElementById('edit_end').value = event.end_time;
            document.getElementById('edit_desc').value = event.description;

            document.getElementById('editForm').action = `/events/${event.id}`;
        }

        function openModal(content) {
            document.getElementById('modalContent').innerHTML = content;
            const modal = document.getElementById('globalModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('globalModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('globalModal').addEventListener('click', e => {
            if (e.target.id === 'globalModal') closeModal();
        });
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

</x-layout>
