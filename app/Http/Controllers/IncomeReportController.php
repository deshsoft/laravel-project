<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingEvent;
use App\Models\BookingEventAsset;
use App\Models\Asset;
use Carbon\Carbon;

class IncomeReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d') : null;
        $endDate = $request->end_date ? Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d') : null;

        $query = BookingEvent::with(['customer', 'assets.asset', 'slots'])
            ->when($request->customer_id, fn($q) => $q->where('fk_customer', $request->customer_id))
            ->when(!empty($request->asset_type) && is_array($request->asset_type) && count(array_filter($request->asset_type)), function ($q) use ($request) {
                $q->whereHas('assets.asset', function ($q2) use ($request) {
                    $q2->whereIn('asset_type', $request->asset_type);
                });
            })


            ->when($startDate, function ($q) use ($startDate) {
                $q->whereHas('slots', function ($slotQuery) use ($startDate) {
                    $slotQuery->whereDate('from_date', '>=', $startDate);
                });
            })
            ->when($endDate, function ($q) use ($endDate) {
                $q->whereHas('slots', function ($slotQuery) use ($endDate) {
                    $slotQuery->whereDate('from_date', '<=', $endDate);
                });
            });

        $events = $query->get();

        $customers = \App\Models\Customer::select('id', 'company_name', 'first_name', 'last_name')->get();
        $assetTypes = Asset::where('mode', 'Aggregable')
            ->select('asset_type')
            ->distinct()
            ->pluck('asset_type');


        return view('reports.income', compact('events', 'customers', 'assetTypes', 'request'));
    }

}
