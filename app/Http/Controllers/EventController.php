<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'description' => 'required',
        ]);

        do {
            $code = random_int(100000, 999999);
        } while (Event::where('code', $code)->exists());

        Event::create([
            'user_id' => Auth::id(),
            'event_name' => $request->event_name,
            'date' => $request->date,
            'time' => $request->time,
            'description' => $request->description,
            'code' => $code,
        ]);

        return redirect()->back()->with('success', 'Event created!');
    }

    public function joinByCode(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6'
        ]);

        $event = Event::where('code', $request->code)->first();

        if (!$event) {
            return back()->with('error', 'Invalid event code.');
        }

        $user = Auth::user();

        if (! $user instanceof \App\Models\User) {
            return back()->with('error', 'Not authenticated as user.');
        }

        $user->joinedEvents()->syncWithoutDetaching([$event->id]);

        return back()->with('success', 'Event added to calender!');
    }
    public function update(Request $request, $id)
    {
        // 1. Cari event berdasarkan ID
        $event = Event::find($id);

        // 2. Cek apakah event ada?
        if (!$event) {
            return back()->with('error', 'Event not found.');
        }

        // 3. KEAMANAN: Cek apakah yang mau edit adalah pemilik event?
        if ($event->user_id !== Auth::id()) {
            return back()->with('error', 'You are not the event maker!');
        }

        // 4. Validasi input (sama seperti store)
        $request->validate([
            'event_name' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'description' => 'required',
        ]);

        // 5. Simpan perubahan
        $event->update([
            'event_name' => $request->event_name,
            'date' => $request->date,
            'time' => $request->time,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Event updated!');
    }

    // --- FUNGSI DESTROY (Hapus Data) ---
    public function destroy($id)
    {
        // 1. Cari event
        $event = Event::find($id);

        // 2. Cek ada atau tidak
        if (!$event) {
            return back()->with('error', 'Event not found.');
        }

        // 3. KEAMANAN: Cek pemilik
        if ($event->user_id !== Auth::id()) {
            return back()->with('error', 'You are not authorized to delete this event');
        }

        // 4. Hapus
        $event->delete();

        return back()->with('success', 'Event deleted!');
    }
}
