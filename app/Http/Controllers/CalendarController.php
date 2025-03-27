<?php

namespace App\Http\Controllers;

use App\Models\BookingEvent;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index'); // Calendar view
    }

    public function fetchEvents()
    {
        $events = BookingEvent::with(['customer', 'slots'])->get()->flatMap(function ($event) {
            $clientName = $event->customer->company_name ?? 'Unknown Client'; // Ensure relation exists
            $eventDays = [];

            foreach ($event->slots as $slot) {
                $startDate = \Carbon\Carbon::parse($slot->from_date)->startOfDay();
                $endDate = \Carbon\Carbon::parse($slot->to_date)->endOfDay();

                while ($startDate->lte($endDate)) {
                    $eventDays[] = [
                        'id' => $event->id,
                        'title' => "{$event->title} - {$clientName} - {$slot->from_time} - {$slot->to_time}",
                        'start' => $startDate->toDateString() . 'T' . $slot->from_time,
                        'end' => $startDate->toDateString() . 'T' . $slot->to_time,
                        'color' => '#3788d8',
                    ];

                    $startDate->addDay(); // Move to the next date
                }
            }

            return $eventDays;
        });

        return response()->json($events);
    }



}



// // Hardcoded demo events for testing
// $events = [
//     [
//         'id' => 1,
//         'title' => 'Meeting with Client',
//         'start' => '2025-03-10T10:00:00',
//         'end' => '2025-03-10T12:00:00',
//         'color' => '#f56954', // Red
//     ],
//     [
//         'id' => 2,
//         'title' => 'Team Stand-up',
//         'start' => '2025-03-11T09:00:00',
//         'end' => '2025-03-11T10:00:00',
//         'color' => '#00a65a', // Green
//     ],
//     [
//         'id' => 3,
//         'title' => 'Project Deadline',
//         'start' => '2025-03-15',
//         'color' => '#f39c12', // Yellow
//     ],
// ];
// return response()->json($events);