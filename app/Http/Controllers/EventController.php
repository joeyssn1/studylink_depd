<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'event_name'  => 'required',
            'date'        => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
            'description' => 'required',
        ]);

        do {
            $code = random_int(100000, 999999);
        } while (Event::where('code', $code)->exists());

        Event::create([
            'user_id'     => Auth::id(),
            'event_name'  => $request->event_name,
            'date'        => $request->date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'description' => $request->description,
            'code'        => $code,
        ]);

        return back()->with('success', 'Event created!');
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

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 🔒 TIME CONFLICT CHECK
        $conflict = $user->joinedEvents()
            ->where('date', $event->date)
            ->where(function ($query) use ($event) {
                $query->where(function ($q) use ($event) {
                    $q->where('start_time', '<', $event->end_time)
                        ->where('end_time', '>', $event->start_time);
                });
            })
            ->exists();

        if ($conflict) {
            return back()->with(
                'error',
                'Cannot join event. Time conflict detected.'
            );
        }

        $user->joinedEvents()->syncWithoutDetaching([$event->id]);

        return back()->with('success', 'Event added to calendar!');
    }

    public function update(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return back()->with('error', 'Event not found.');
        }

        if ($event->user_id !== Auth::id()) {
            return back()->with('error', 'You are not the event maker!');
        }

        $request->validate([
            'event_name'  => 'required',
            'date'        => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
            'description' => 'required',
        ]);

        $event->update([
            'event_name'  => $request->event_name,
            'date'        => $request->date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Event updated!');
    }

    public function destroy($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return back()->with('error', 'Event not found.');
        }

        if ($event->user_id !== Auth::id()) {
            return back()->with('error', 'You are not authorized.');
        }

        $event->delete();

        return back()->with('success', 'Event deleted!');
    }

    public function leave($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $event = Event::find($id);

        if (!$event) {
            return back()->with('error', 'Event not found.');
        }

        // Creator cannot leave their own event
        if ($event->user_id === $user->id) {
            return back()->with('error', 'You cannot leave your own event.');
        }

        // Detach user from event
        $user->joinedEvents()->detach($event->id);

        return back()->with('success', 'Event removed from your calendar.');
    }
}
