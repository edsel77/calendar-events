<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CalendarEvents;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CalendarEventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $month = $request->year . '-' . $request->month;
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        $dates = CarbonPeriod::create($start, $end)->toArray();

        $month_events = [];
        for ($i = 0; $i < count($dates); $i++) {
            $event = CalendarEvents::whereDate('date', $dates[$i])->first();
            if ($event) {
                $event->date = Carbon::parse($event->date)->format('Y-m-d');
                array_push($month_events, $event);
            }
        }

        return $month_events;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event_name = $request->event_name;
        $range = $request->range;
        $selected_days = $request->selected_days;
        $dates = CarbonPeriod::create(Carbon::parse($range[0]), Carbon::parse($range[1]))->toArray();
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        $selected_days_array = [];
        for ($i = 0; $i < count($selected_days); $i++) { 
            if ($selected_days[$i] === true) array_push($selected_days_array, $days[$i]);
        }

        for ($i = 0; $i < count($dates); $i++) {
            $event = CalendarEvents::whereDate('date', $dates[$i])->first();
            // $is_selected = Carbon::parse($event->date)->format('l');
            if ($event && in_array(Carbon::parse($event->date)->format('l'), $selected_days_array)) {
                CalendarEvents::whereDate('date', $dates[$i])->update(['event_name' => $event_name]);
            } else {
                if (in_array(Carbon::parse($dates[$i])->format('l'), $selected_days_array)) {
                    CalendarEvents::create([
                        'date' => $dates[$i],
                        'event_name' => $event_name
                    ]);
                } else {
                    CalendarEvents::whereDate('date', $dates[$i])->delete();
                }
            }
        }

        return ['status' => 'Event successfully updated.'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
