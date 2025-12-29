{{-- views/profilepage.blade.php --}}

<x-layout title="Profile">

    <!-- PROFILE HEADER -->
    <section class="max-w-3xl mx-auto mt-12 px-4 text-center">

        <!-- Profile Icon -->
        <div
            class="w-32 h-32 mx-auto rounded-full bg-gray-200
                    flex items-center justify-center mb-4">
            <i class="ri-user-fill text-5xl text-gray-600"></i>
        </div>

        <h2 class="text-2xl font-semibold">
            Welcome, {{ auth()->user()->name }}
        </h2>

        <button onclick="openAddEvent()" class="mt-6 bg-green-600 text-white px-5 py-2 rounded">
            Add Event
        </button>

    </section>

    <!-- EVENTS LIST -->
    <section class="max-w-3xl mx-auto mt-10 px-4">

        <h3 class="text-xl font-semibold mb-4">Your Events</h3>

        @if ($events->isEmpty())
            <p class="text-gray-500 text-sm">
                No events created yet.
            </p>
        @else
            <div class="grid gap-4">
                @foreach ($events as $event)
                    <div class="border rounded-lg p-4 shadow-sm bg-white">

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

                        <p class="mt-3 text-sm">
                            <span class="font-semibold">Code:</span>
                            <span class="bg-gray-100 px-2 py-1 rounded">
                                {{ $event->code }}
                            </span>
                        </p>

                    </div>
                @endforeach
            </div>
        @endif

    </section>

    <!-- GLOBAL MODAL -->
    <div id="globalModal"
        class="fixed inset-0 hidden items-center justify-center
                bg-black/40 backdrop-blur-sm z-50">

        <div class="bg-white w-[90%] max-w-xl
                    rounded-xl shadow-xl p-6 relative">

            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500">
                <i class="ri-close-line text-2xl"></i>
            </button>

            <div id="modalContent"></div>
        </div>
    </div>

    <!-- ADD EVENT MODAL CONTENT (HIDDEN) -->
    <div id="addEventModal" class="hidden">

        <h2 class="text-xl font-semibold mb-4">Create New Event</h2>

        <form action="/events" method="POST" class="space-y-4">
            @csrf

            <input type="text" name="event_name" class="w-full border rounded px-3 py-2" placeholder="Event Name"
                required>

            <input type="date" name="date" class="w-full border rounded px-3 py-2" required>

            <input type="time" name="time" class="w-full border rounded px-3 py-2" required>

            <textarea name="description" rows="4" class="w-full border rounded px-3 py-2" placeholder="Description" required></textarea>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="border px-4 py-2 rounded">
                    Cancel
                </button>

                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                    Upload
                </button>
            </div>
        </form>

    </div>

    <!-- MODAL SCRIPT -->
    <script>
        function openAddEvent() {
            openModal(document.getElementById('addEventModal').innerHTML);
        }

        function openModal(content) {
            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('globalModal').classList.remove('hidden');
            document.getElementById('globalModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('globalModal').classList.add('hidden');
        }
    </script>

</x-layout>
