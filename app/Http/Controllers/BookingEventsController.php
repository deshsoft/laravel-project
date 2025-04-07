<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingEventInvoiceMail;
use Illuminate\Http\Request;
use App\Models\BookingEvent;
use App\Models\BookingEventAsset;
use App\Models\BookingEventSlot;
use App\Models\Asset;
use App\Models\Customer;
use Carbon\Carbon;
// use DateTime;

class BookingEventsController extends Controller
{
    public function fetchBookedTimes(Request $request)
    {
        $assetIds = $request->asset_ids;
        $date = \DateTime::createFromFormat('d/m/Y', $request->date);

        if (!$date || empty($assetIds)) {
            return response()->json(['booked_times' => []]);
        }

        $targetDate = $date->format('Y-m-d');
        $bookedTimes = [];

        $assets = BookingEventAsset::with('bookingEvent.slots')
            ->whereIn('fk_asset', $assetIds)
            ->get();

        foreach ($assets as $asset) {
            foreach ($asset->bookingEvent->slots ?? [] as $slot) {
                $slotFromDate = new \DateTime($slot->from_date);
                $slotToDate = new \DateTime($slot->to_date);

                while ($slotFromDate <= $slotToDate) {
                    $dateKey = $slotFromDate->format('Y-m-d');

                    if ($dateKey === $targetDate) {
                        $bookedTimes[] = [
                            'from' => date('H', strtotime($slot->from_time)), // e.g., "09"
                            'to' => date('H', strtotime($slot->to_time))      // e.g., "11"
                        ];
                    }

                    $slotFromDate->modify('+1 day');
                }
            }
        }

        return response()->json([
            'booked_times' => $bookedTimes
        ]);
    }

    public function fetchBookedDates(Request $request)
    {
        $assetIds = $request->asset_ids;
        $bookedDates = [];

        if (empty($assetIds)) {
            return response()->json(['booked_dates' => []]);
        }

        $fullStart = strtotime('09:00');
        $fullEnd = strtotime('18:00');
        $buffer = 3600; // 1 hour buffer

        $assets = BookingEventAsset::with('bookingEvent.slots')
            ->whereIn('fk_asset', $assetIds)
            ->get();

        $assetDateTimeBlocks = [];

        foreach ($assets as $asset) {
            $assetId = $asset->fk_asset;
            foreach ($asset->bookingEvent->slots ?? [] as $slot) {
                $slotFromDate = new \DateTime($slot->from_date);
                $slotToDate = $slot->to_date ? new \DateTime($slot->to_date) : clone $slotFromDate;


                while ($slotFromDate <= $slotToDate) {
                    $dateKey = $slotFromDate->format('Y-m-d');

                    $fromTime = strtotime($slot->from_time);
                    $toTime = strtotime($slot->to_time);

                    // Skip slots with blank/invalid time blocks
                    if ($fromTime === $toTime) {
                        // Optional: log if needed
                        // \Log::warning("Skipping blank slot on {$dateKey} for asset {$assetId}");
                        $slotFromDate->modify('+1 day');
                        continue;
                    }

                    $assetDateTimeBlocks[$assetId][$dateKey][] = [
                        'start' => $fromTime,
                        'end' => $toTime,
                    ];

                    $slotFromDate->modify('+1 day');
                }
            }
        }

        $finalBookedDates = [];

        foreach ($assetDateTimeBlocks as $assetId => $dateBlocks) {
            foreach ($dateBlocks as $date => $blocks) {
                usort($blocks, fn($a, $b) => $a['start'] <=> $b['start']);

                // Merge time blocks with 1-hour buffer
                $merged = [];
                foreach ($blocks as $block) {
                    if (empty($merged)) {
                        $merged[] = $block;
                    } else {
                        $last = &$merged[count($merged) - 1];
                        if ($block['start'] <= $last['end'] + $buffer) {
                            $last['end'] = max($last['end'], $block['end']);
                        } else {
                            $merged[] = $block;
                        }
                    }
                }

                // Check if any merged block covers full booking day
                foreach ($merged as $block) {
                    if ($block['start'] <= $fullStart && $block['end'] >= $fullEnd) {
                        $finalBookedDates[] = (new \DateTime($date))->format('d/m/Y');
                        break;
                    }
                }
            }
        }

        return response()->json([
            'booked_dates' => array_values(array_unique($finalBookedDates))
        ]);
    }




    public function checkAggregableAvailability(Request $request)
    {
        $fromDate = \DateTime::createFromFormat('d/m/Y', $request->from_date);
        $toDate = \DateTime::createFromFormat('d/m/Y', $request->to_date);
        $fromTime = $request->from_time; // expected format 'HH:MM'
        $toTime = $request->to_time;
        $assetIds = $request->asset_ids;

        $conflicts = [];

        // Format for DB query
        $fromDateFormatted = $fromDate->format('Y-m-d');
        $toDateFormatted = $toDate->format('Y-m-d');

        // Filter slots only within the selected date range
        $assets = BookingEventAsset::with([
            'asset',
            'bookingEvent.slots' => function ($query) use ($fromDateFormatted, $toDateFormatted) {
                $query->where(function ($q) use ($fromDateFormatted, $toDateFormatted) {
                    $q->whereBetween('from_date', [$fromDateFormatted, $toDateFormatted])
                        ->orWhereBetween('to_date', [$fromDateFormatted, $toDateFormatted])
                        ->orWhere(function ($q2) use ($fromDateFormatted, $toDateFormatted) {
                            $q2->where('from_date', '<=', $fromDateFormatted)
                                ->where('to_date', '>=', $toDateFormatted);
                        });
                });
            }
        ])
            ->whereIn('fk_asset', $assetIds)
            ->get();

        // Prepare list of dates to check
        $checkDates = [];
        $temp = clone $fromDate;
        while ($temp <= $toDate) {
            $checkDates[] = $temp->format('Y-m-d');
            $temp->modify('+1 day');
        }

        // Loop through assets and check for time slot conflicts
        foreach ($assets as $asset) {
            foreach ($asset->bookingEvent->slots ?? [] as $slot) {
                // Convert slot dates
                $slotFromDate = new \DateTime($slot->from_date);
                $slotToDate = new \DateTime($slot->to_date);

                $slotDates = [];
                $slotTemp = clone $slotFromDate;
                while ($slotTemp <= $slotToDate) {
                    $slotDates[] = $slotTemp->format('Y-m-d');
                    $slotTemp->modify('+1 day');
                }

                // Check if any dates overlap
                $overlappingDates = array_intersect($checkDates, $slotDates);

                foreach ($overlappingDates as $date) {
                    // // Compare time blocks
                    if (
                        strtotime($fromTime) < strtotime($slot->to_time) &&
                        strtotime($toTime) > strtotime($slot->from_time)
                    ) {
                        $conflicts[] = [
                            'asset_id' => $asset->fk_asset,
                            'asset_name' => $asset->asset->asset_type ?? 'Unknown',
                            'conflict_date' => (new \DateTime($date))->format('d/m/Y'),
                            'from' => $slot->from_time,
                            'to' => $slot->to_time,
                        ];
                    }
                }
            }
        }

        return response()->json(['conflicts' => $conflicts]);
    }



    public function calendar()
    {
        return view('booking_events.calendar'); // Calendar view
    }

    public function fetchEvents()
    {
        $events = BookingEvent::with(['customer', 'slots'])->get()->flatMap(function ($event) {
            $clientName = $event->customer->company_name ?? 'Unknown Client'; // Ensure relation exists
            $eventDays = [];

            foreach ($event->slots as $slot) {
                $startDate = Carbon::parse($slot->from_date)->startOfDay();
                $endDate = Carbon::parse($slot->to_date)->endOfDay();

                while ($startDate->lte($endDate)) {
                    $eventDays[] = [
                        'id' => $event->id,
                        'is_done' => $event->is_done,
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
    public function index(Request $request)
    {
        $events = BookingEvent::with(['customer', 'firstSlot']) // add firstSlot here
            ->select(
                'id',
                'title',
                'fk_customer',
                'total_price',
                'discount',
                'discount_percen_flat',
                'final_price',
                'vat_amount',
                'final_price_with_vat',
                'note',
                'is_done'
            )
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                        ->orWhere('total_price', 'like', "%$search%")
                        ->orWhere('discount', 'like', "%$search%");
                });
            })
            ->when($request->has('is_done'), function ($query) use ($request) {
                if ($request->is_done === '0' || $request->is_done === '1') {
                    return $query->where('is_done', $request->is_done);
                }
            }, function ($query) {
                return $query->where('is_done', 0);
            })
            ->orderByDesc('id')
            ->distinct()
            ->paginate(10);

        return view('booking_events.index', compact('events'));
    }


    public function create(Request $request)
    {
        $selectedDate = $request->query('date');
        $assets = Asset::all();
        $customers = Customer::all();

        // Generate 24-hour time slots
        $timeSlots = [];
        // First loop: 08:00 to 24:00
        // for ($i = 8; $i <= 24; $i++) {
        //     $hour = ($i == 24) ? '00' : str_pad($i, 2, '0', STR_PAD_LEFT);
        //     $timeSlots[] = $hour . ':00';
        // }
        for ($i = 9; $i <= 18; $i++) {
            $hour = ($i == 24) ? '00' : str_pad($i, 2, '0', STR_PAD_LEFT);
            $timeSlots[] = $hour . ':00';
        }

        // // Second loop: 01:00 to 07:00
        // for ($i = 1; $i <= 7; $i++) {
        //     $timeSlots[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
        // }

        return view('booking_events.create', compact('assets', 'customers', 'timeSlots', 'selectedDate'));
    }

    public function store(Request $request)
    {
        // print_r($request->title);
        // die;
        // Validate request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'fk_customer' => 'required|exists:customers,id',
            // 'aggregable_price' => 'nullable|numeric|min:0',
            // 'non_aggregable_price' => 'nullable|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_percen_flat' => 'nullable|string',
            'final_price' => 'nullable|numeric|min:0',
            'vat_amount' => 'nullable|numeric|min:0',
            'final_price_with_vat' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
            'assets' => 'nullable|array',
            'slots' => 'nullable|array',
        ]);

        // Create Booking Event
        $bookingEvent = BookingEvent::create([
            'title' => $request->title,
            'fk_customer' => $request->fk_customer,
            // 'aggregable_price' => $request->aggregable_price ?? 0,
            // 'non_aggregable_price' => $request->non_aggregable_price ?? 0,
            'total_price' => $request->total_price ?? 0,
            'discount' => $request->discount ?? 0,
            'discount_percen_flat' => $request->discount_percen_flat ?? null,
            'final_price' => $request->final_price ?? 0,
            'vat_amount' => $request->vat_amount ?? 0,
            'final_price_with_vat' => $request->final_price_with_vat ?? 0,
            'note' => $request->note ?? null,
            'created_by' => auth()->id(),
            'create_date' => now(),
        ]);

        // Handle assets if provided
        if ($request->has('assets')) {
            foreach ($request->assets as $assetId => $data) {
                // Check if the asset is selected (checkbox checked)
                if (isset($data['selected']) && $data['selected'] == '1') {
                    $asset = Asset::find($assetId);
                    if ($asset) {
                        $qty = isset($data['qty']) ? (int) $data['qty'] : 1;
                        $total = isset($data['total']) ? (int) $data['total'] : 1;
                        BookingEventAsset::create([
                            'fk_asset' => $assetId,
                            'asset_qty' => $asset->mode == 'non-aggregable' ? $qty : null,
                            'asset_price' => $asset->rental_value,
                            'total' => $total,
                            'booking_event_id' => $bookingEvent->id,
                        ]);
                    }
                }
            }
        }


        // Handle slots if provided
        if ($request->has('slots')) {
            foreach ($request->slots as $slot) {
                BookingEventSlot::create([
                    'from_date' => Carbon::createFromFormat('d/m/Y', $slot['from_date'])->format('Y-m-d'),
                    'to_date' => isset($slot['to_date']) && $slot['to_date']
                        ? Carbon::createFromFormat('d/m/Y', $slot['to_date'])->format('Y-m-d')
                        : null,
                    'from_time' => $slot['from_time'],
                    'to_time' => $slot['to_time'] ?? null,
                    'slot_price' => $slot['slot_price'],
                    'aggregable_price' => $slot['aggregable_price'],
                    'non_aggregable_price' => $slot['non_aggregable_price'],
                    'booking_event_id' => $bookingEvent->id,
                ]);
            }
        }

        return redirect()->route('booking-events.index')->with('success', 'Booking event created successfully.');
    }

    public function sendInvoiceEmail($id)
    {
        // Get Booking Event and Customer
        $bookingEvent = BookingEvent::with(['slots', 'customer'])->findOrFail($id);

        // Prepare Asset Data
        $selectedAssets = BookingEventAsset::where('booking_event_id', $id)
            ->with('asset')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->fk_asset => [
                        'asset_type' => $item->asset->asset_type ?? 'N/A',
                        'qty' => $item->asset_qty,
                        'price' => $item->asset_price,
                        'fixed_hourly' => $item->asset->fixed_hourly,
                        'total' => $item->total,
                    ]
                ];
            })
            ->toArray();

        // Get Slots
        $bookingSlots = BookingEventSlot::where('booking_event_id', $id)->get();

        // Send Email
        Mail::to($bookingEvent->customer->email)->send(
            new BookingEventInvoiceMail($bookingEvent, $selectedAssets, $bookingSlots)
        );

        return redirect()->route('booking-events.index', $id)
            ->with('success', 'Invoice has been sent to ' . $bookingEvent->customer->email);
    }

    public function view($id)
    {
        $bookingEvent = BookingEvent::with(['slots', 'customer'])->findOrFail($id);

        // Fetch selected assets and their details
        $selectedAssets = BookingEventAsset::where('booking_event_id', $id)
            ->with('asset') // Eager load asset details
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->fk_asset => [
                        'asset_type' => $item->asset->asset_type ?? 'N/A', // Ensure asset data exists
                        'qty' => $item->asset_qty,
                        'price' => $item->asset_price,
                        'fixed_hourly' => $item->asset->fixed_hourly,
                        'total' => $item->total,
                    ]
                ];
            })
            ->toArray();

        $bookingSlots = BookingEventSlot::where('booking_event_id', $id)->get();

        return view('booking_events.view', compact('bookingEvent', 'selectedAssets', 'bookingSlots'));
    }

    public function toggleStatus($id)
    {
        $bookingEvent = BookingEvent::findOrFail($id);
        $bookingEvent->is_done = !$bookingEvent->is_done; // Toggle between 0 and 1
        $bookingEvent->save();

        return redirect()->back()->with('success', 'Booking event status updated successfully.');
    }
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'fk_asset');
    }

    public function edit($id)
    {
        $bookingEvent = BookingEvent::with('slots')->findOrFail($id);
        $customers = Customer::all();
        $assets = Asset::all();

        // Generate 24-hour time slots
        $timeSlots = [];
        // First loop: 08:00 to 24:00
        // for ($i = 8; $i <= 24; $i++) {
        //     $hour = ($i == 24) ? '00' : str_pad($i, 2, '0', STR_PAD_LEFT);
        //     $timeSlots[] = $hour . ':00';
        // }

        for ($i = 9; $i <= 18; $i++) {
            $hour = ($i == 24) ? '00' : str_pad($i, 2, '0', STR_PAD_LEFT);
            $timeSlots[] = $hour . ':00';
        }

        // // Second loop: 01:00 to 07:00
        // for ($i = 1; $i <= 7; $i++) {
        //     $timeSlots[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
        // }


        // Fetch selected assets and their quantities
        $selectedAssets = BookingEventAsset::where('booking_event_id', $id)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->fk_asset => ['qty' => $item->asset_qty, 'price' => $item->asset_price, 'total' => $item->total]];
            })
            ->toArray();


        // Fetch existing slots for the event
        $bookingSlots = BookingEventSlot::where('booking_event_id', $id)->get();


        return view('booking_events.edit', compact('bookingEvent', 'customers', 'assets', 'selectedAssets', 'bookingSlots', 'timeSlots'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'fk_customer' => 'required|exists:customers,id',
            // 'aggregable_price' => 'nullable|numeric|min:0',
            // 'non_aggregable_price' => 'nullable|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_percen_flat' => 'nullable|string',
            'final_price' => 'nullable|numeric|min:0',
            'vat_amount' => 'nullable|numeric|min:0',
            'final_price_with_vat' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
            'assets' => 'nullable|array',
            'slots' => 'nullable|array',
        ]);

        $bookingEvent = BookingEvent::findOrFail($id);
        $bookingEvent->update([
            'title' => $request->title,
            'fk_customer' => $request->fk_customer,
            // 'aggregable_price' => $request->aggregable_price,
            // 'non_aggregable_price' => $request->non_aggregable_price,
            'total_price' => $request->total_price ?? 0,
            'discount' => $request->discount ?? 0,
            'discount_percen_flat' => $request->discount_percen_flat ?? null,
            'final_price' => $request->final_price ?? 0,
            'vat_amount' => $request->vat_amount ?? 0,
            'final_price_with_vat' => $request->final_price_with_vat ?? 0,
            'note' => $request->note ?? null,
            'updated_by' => auth()->id(),
            'update_date' => now(),
        ]);

        // Remove previous assets
        BookingEventAsset::where('booking_event_id', $id)->delete();

        // Insert updated assets
        if ($request->has('assets')) {
            foreach ($request->assets as $assetId => $data) {
                if (!isset($data['selected'])) {
                    continue;
                }

                $asset = Asset::findOrFail($assetId);
                $qty = isset($data['qty']) ? (int) $data['qty'] : 1;
                $total = isset($data['total']) ? (int) $data['total'] : 1;
                BookingEventAsset::create([
                    'booking_event_id' => $bookingEvent->id,
                    'fk_asset' => $assetId,
                    'asset_qty' => ($asset->mode === 'non-aggregable') ? $qty : 1,
                    'asset_price' => $asset->rental_value,
                    'total' => $total,
                ]);
            }
        }

        // Remove previous slots
        BookingEventSlot::where('booking_event_id', $id)->delete();

        // Insert updated slots
        if ($request->has('slots')) {
            foreach ($request->slots as $slotData) {
                BookingEventSlot::create([
                    'booking_event_id' => $bookingEvent->id,
                    'from_date' => Carbon::createFromFormat('d/m/Y', $slotData['from_date'])->format('Y-m-d'),
                    'to_date' => isset($slotData['to_date']) && $slotData['to_date']
                        ? Carbon::createFromFormat('d/m/Y', $slotData['to_date'])->format('Y-m-d')
                        : null,
                    'from_time' => $slotData['from_time'],
                    'to_time' => $slotData['to_time'],
                    'slot_price' => $slotData['slot_price'],
                    'aggregable_price' => $slotData['aggregable_price'],
                    'non_aggregable_price' => $slotData['non_aggregable_price'],
                ]);
            }
        }

        return redirect()->route('booking-events.index')->with('success', 'Booking event updated successfully.');
    }


    public function destroy($id)
    {
        $event = BookingEvent::findOrFail($id);
        $event->delete();
        return redirect()->route('booking-events.index')->with('success', 'Booking event deleted successfully.');
    }



}
