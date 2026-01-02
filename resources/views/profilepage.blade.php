{{-- views/profilepage.blade.php --}}

<x-layout title="Profile">

    <section class="max-w-3xl mx-auto mt-12 px-4 text-center">

        <div class="w-32 h-32 mx-auto rounded-full bg-gray-200 flex items-center justify-center mb-4">
            <i class="ri-user-fill text-5xl text-gray-600"></i>
        </div>

        <h2 class="text-2xl font-semibold">
            Welcome, {{ auth()->user()->name }}
        </h2>

        <button onclick="openAddEvent()" class="mt-6 bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700 transition">
            Add Event
        </button>

    </section>

    {{-- Notifikasi Sukses/Gagal --}}
    <section class="max-w-3xl mx-auto mt-6 px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
    </section>

    <section class="max-w-3xl mx-auto mt-8 px-4 mb-20">

        <h3 class="text-xl font-semibold mb-4">Your Events</h3>

        @if ($events->isEmpty())
            <p class="text-gray-500 text-sm">
                No events created yet.
            </p>
        @else
            <div class="grid gap-4">
                @foreach ($events as $event)
                    <div class="border rounded-lg p-4 shadow-sm bg-white hover:shadow-md transition">

                        <h4 class="font-semibold text-lg">
                            {{ $event->event_name }}
                        </h4>

                        <p class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                            at {{ $event->time }}
                        </p>

                        <p class="mt-2 text-sm">
                            {{ $event->description }}
                        </p>

                        <p class="mt-3 text-sm mb-4">
                            <span class="font-semibold">Code:</span>
                            <span class="bg-gray-100 px-2 py-1 rounded select-all">
                                {{ $event->code }}
                            </span>
                        </p>

                        <div class="flex gap-2 border-t pt-3 mt-2">
                            <button onclick='openEditEvent(@json($event))' 
                                class="bg-gray-100 text-gray-700 px-3 py-1 rounded text-sm hover:bg-gray-200 transition">
                                Edit
                            </button>

                            <form action="{{ route('events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Delete this event?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-50 text-red-600 px-3 py-1 rounded text-sm hover:bg-red-100 transition">
                                    Delete
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </section>

    <div id="globalModal"
        class="fixed inset-0 hidden items-center justify-center bg-black/40 backdrop-blur-sm z-50">

        <div class="bg-white w-[90%] max-w-xl rounded-xl shadow-xl p-6 relative">

            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                <i class="ri-close-line text-2xl"></i>
            </button>

            <div id="modalContent"></div>
        </div>
    </div>

    <div id="addEventTemplate" class="hidden">
        <h2 class="text-xl font-semibold mb-4">Create New Event</h2>

        <form action="/events" method="POST" class="space-y-4">
            @csrf

            <input type="text" name="event_name" class="w-full border rounded px-3 py-2" placeholder="Event Name" required>

            <input type="date" name="date" class="w-full border rounded px-3 py-2" required>

            <input type="time" name="time" class="w-full border rounded px-3 py-2" required>

            <textarea name="description" rows="4" class="w-full border rounded px-3 py-2" placeholder="Description" required></textarea>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="border px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Upload</button>
            </div>
        </form>
    </div>

    <div id="editEventTemplate" class="hidden">
        <h2 class="text-xl font-semibold mb-4">Edit Event</h2>

        <form id="editForm" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT') <div>
                <label class="text-sm text-gray-600">Event Name</label>
                <input type="text" id="edit_name" name="event_name" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="text-sm text-gray-600">Date</label>
                <input type="date" id="edit_date" name="date" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="text-sm text-gray-600">Time</label>
                <input type="time" id="edit_time" name="time" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="text-sm text-gray-600">Description</label>
                <textarea id="edit_desc" name="description" rows="4" class="w-full border rounded px-3 py-2" required></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="border px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Changes</button>
            </div>
        </form>
    </div>

    <script>
        // Buka Modal Add (Template Asli)
        function openAddEvent() {
            const template = document.getElementById('addEventTemplate').innerHTML;
            openModal(template);
        }

        // Buka Modal Edit (Template Baru + Auto Fill)
        function openEditEvent(event) {
            const template = document.getElementById('editEventTemplate').innerHTML;
            openModal(template);

            // Isi form dengan data lama
            document.getElementById('edit_name').value = event.event_name;
            document.getElementById('edit_date').value = event.date;
            document.getElementById('edit_time').value = event.time;
            document.getElementById('edit_desc').value = event.description;

            // Update URL Form agar mengarah ke ID event yang benar
            document.getElementById('editForm').action = `/events/${event.id}`;
        }

        // Helper Functions (Bawaan Kamu)
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

        // Tutup modal kalau klik background
        document.getElementById('globalModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>

</x-layout>