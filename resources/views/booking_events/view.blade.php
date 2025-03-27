<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Event Invoice') }}
        </h2>
    </x-slot>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .print-area,
            .print-area * {
                visibility: visible;
            }

            .print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="print-area bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4 p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-full text-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto h-16 mb-2">
                        <h3 class="text-lg font-bold">Invoice</h3>
                    </div>
                    <button onclick="window.print()" class="btn btn-success rounded no-print">Print</button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-4"><strong>Title:</strong> {{ $bookingEvent->title }}</div>
                <div class="mb-4"><strong>Customer:</strong> {{ $bookingEvent->customer->company_name }}</div>

                <table class="w-full border-collapse border border-gray-300 mb-4">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-2 py-2 text-left">Asset Type</th>
                            <th class="border border-gray-300 px-2 py-2 text-left">Rental Value</th>
                            <th class="border border-gray-300 px-2 py-2 text-left">Quantity</th>
                            <th class="border border-gray-300 px-2 py-2 text-left">Fixed / Hourly</th>
                            <th class="border border-gray-300 px-2 py-2 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($selectedAssets as $assetId => $selected)
                            <tr>
                                <td class="border border-gray-300 px-2 py-2">{{ $selected['asset_type'] }}</td>
                                <td class="border border-gray-300 px-2 py-2">
                                    €{{ number_format($selected['price'], 2, ',', '.') }}
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center">{{ $selected['qty'] }}</td>
                                <td class="border border-gray-300 px-2 py-2 text-center">{{ $selected['fixed_hourly'] }}
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-right">
                                    €{{ number_format($selected['total'], 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="w-full border-collapse border border-gray-300 mb-4">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-2 py-2">From Date</th>
                            <th class="border border-gray-300 px-2 py-2" style="width:10%;">From Time</th>
                            <th class="border border-gray-300 px-2 py-2" style="width:10%;">To Time</th>
                            <th class="border border-gray-300 px-2 py-2">To Date</th>
                            <th class="border border-gray-300 px-2 py-2">Aggregable Price</th>
                            <th class="border border-gray-300 px-2 py-2">Non-Aggregable Price</th>
                            <th class="border border-gray-300 px-2 py-2">Slot Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookingSlots as $slot)
                            <tr class="booking-slot">
                                <td class="border border-gray-300 px-2 py-2">
                                    {{ \Carbon\Carbon::parse($slot->from_date)->format('d/m/Y') }}
                                </td>
                                <td class="border border-gray-300 px-2 py-2">{{ $slot->from_time }}</td>
                                <td class="border border-gray-300 px-2 py-2">{{ $slot->to_time }}</td>
                                <td class="border border-gray-300 px-2 py-2">
                                    {{ $slot->to_date ? \Carbon\Carbon::parse($slot->to_date)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="border border-gray-300 px-2 py-2">
                                    €{{ number_format($slot->aggregable_price, 2, ',', '.') }}
                                </td>
                                <td class="border border-gray-300 px-2 py-2">
                                    €{{ number_format($slot->non_aggregable_price, 2, ',', '.') }}
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-right">
                                    €{{ number_format($slot->slot_price, 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="border px-2 py-2 text-right">Total Price:</td>
                            <td class="border px-2 py-2 text-right">
                                €{{ number_format($bookingEvent->total_price, 2, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="border px-2 py-2 text-right">Discount:</td>
                            <td class="border px-2 py-2 text-right">
                                €{{ number_format($bookingEvent->discount, 2, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="border px-2 py-2 text-right">Net Price:</td>
                            <td class="border px-2 py-2 text-right">
                                €{{ number_format($bookingEvent->final_price, 2, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="border px-2 py-2 text-right">VAT (22%):</td>
                            <td class="border px-2 py-2 text-right">
                                €{{ number_format($bookingEvent->vat_amount, 2, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="border px-2 py-2 text-right font-bold">Final Price with VAT:</td>
                            <td class="border px-2 py-2 text-right font-bold">
                                €{{ number_format($bookingEvent->final_price_with_vat, 2, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="mb-4"><strong>Note:</strong> {{ $bookingEvent->note }}</div>
            </div>

            <a href="{{ route('booking-events.edit', $bookingEvent->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('booking-events.index') }}" class="btn btn-secondary no-print">Back to List</a>
            <a href="{{ route('booking-events.calendar') }}" class="btn btn-secondary no-print">Back to Calendar</a>

            <!-- Toggle Completion Button -->
            <form style="margin-right:4px; float:left;"
                action="{{ route('booking-events.toggle-status', $bookingEvent->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn {{ $bookingEvent->is_done ? 'btn-danger' : 'btn-primary' }}">
                    {{ $bookingEvent->is_done ? 'Mark Uncompleted' : 'Mark Completed' }}
                </button>
            </form>
        </div>
    </div>
</x-app-layout>