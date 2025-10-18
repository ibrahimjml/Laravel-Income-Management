<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Http\Request;
class Calendarcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = Carbon::today()->toDateString();
        $upcomigPayments = Income::notDeleted()
                           ->with(['client', 'payments'=> function ($q){
                             $q->where('status','!=','paid');
                           }])
                           ->whereDate('next_payment', '>', $today)
                           ->orwhereDate('next_payment', $today)
                           ->where('status','!=','complete')
                           ->whereHas('client', function($query) {
                               $query->notDeleted();
                               })
                           ->get();
                  
        return view('admin.calendar.index',[
          'upcomigPayments' => $upcomigPayments
        ]);
    }
   public function getEvents()
    {
        $events = Event::all()->map(function ($event) {
            return [
                'id'              => $event->event_id,
                'title'           => $event->event_name,
                'start'           => $event->start_date,
                'end'             => $event->end_date,
                'color'           => $event->color,
                'backgroundColor' => $event->color,
                'textColor'       => '#000000',
                'borderColor'     => $event->color,
            ];
        });

        return response()->json($events);
    }
    
        public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'color'      => 'required|string',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $event = Event::create($request->all());

        return response()->json($event, 201);
    }
    
    public function update(Request $request, string $id)
    {
         $request->validate([
            'event_name' => 'required|string|max:255',
            'color'      => 'required|string',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $event = Event::findOrFail($id);
        $event->update($request->all());

        return response()->json($event);
    }
    public function move(Request $request, string $id)
    {
      $event = Event::findOrFail($id);

        $event->update([
            'start_date' => Carbon::parse($request->input('start_date'))->setTimezone('UTC'),
            'end_date' => Carbon::parse($request->input('end_date', $request->input('start_date')))->setTimezone('UTC')
        ]);
        return response()->json(['message' => 'Event moved successfully.']);
    }

    public function resize(Request $request, string $id)
    {
      $event = Event::findOrFail($id);

        $event->update([
            'end_date' => Carbon::parse($request->input('end_date'))->setTimezone('UTC')
      ]);
      return response()->json(['message' => 'Event resized successfully.']);
    }

    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(null, 204);
    }
}
