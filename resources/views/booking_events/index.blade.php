<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="mb-4 text-lg font-bold">Booking Events</h2>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif


                    <form method="GET" action="{{ route('booking-events.index') }}"
                        class="mb-3 flex gap-2 items-center">

                        <select name="is_done" class="form-select" style="width: 20%">
                            <option value="" {{ request()->has('is_done') && request('is_done') === '' ? 'selected' : (!request()->has('is_done') ? '' : '') }}>All</option>
                            <option value="1" {{ request('is_done') === '1' ? 'selected' : '' }}>Completed</option>
                            <option value="0" {{ request()->has('is_done') ? (request('is_done') === '0' ? 'selected' : '') : 'selected' }}>Uncompleted</option>
                        </select>


                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search booking events..." class="form-control w-50">

                        <button type="submit" class="btn btn-info">Search</button>
                        <a href="{{ route('booking-events.index') }}" class="btn btn-primary">Reset</a>
                        <a href="{{ route('booking-events.create') }}" class="btn btn-success">Add Booking Event</a>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Title</th>
                                    <th style="width: 15%;">Customer</th>
                                    <th style="width: 15%;">Asset Types</th>
                                    <th style="width: 10%;">From Date</th>

                                    <th>From Time</th>
                                    <th>To Time</th>
                                    <th style="width: 10%;">To Date</th>
                                    <th>Total Price</th>
                                    <th>Discount</th>
                                    <th>Final Price</th>
                                    <th>VAT 22%</th>
                                    <th>Final Price With VAT</th>
                                    <th class="text-nowrap sticky-action-col bg-white">Actions</th>

                                </tr>
                            </thead>

                            <tbody>
                                @foreach($events as $event)
                                                                @php
                                                                    $types = $event->assets->pluck('asset.asset_type')->filter()->unique()->implode(', ');
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $event->title }}</td>
                                                                    <td>{{ $event->customer->company_name ?? 'N/A' }}</td>
                                                                    <td>{{ $types }}</td>
                                                                    {{-- First Slot Details --}}
                                                                    <td>
                                                                        {{ $event->firstSlot?->from_date ? \Carbon\Carbon::parse($event->firstSlot->from_date)->format('d/m/Y') : '-' }}
                                                                    </td>
                                                                    <td>{{ $event->firstSlot?->from_time ?? '-' }}</td>
                                                                    <td>{{ $event->firstSlot?->to_time ?? '-' }}</td>
                                                                    <td>
                                                                        {{ $event->firstSlot?->to_date ? \Carbon\Carbon::parse($event->firstSlot->to_date)->format('d/m/Y') : '-' }}
                                                                    </td>

                                                                    <td>€{{ number_format($event->total_price, 2, ',', '.') }}</td>
                                                                    <td class="text-nowrap">€{{ number_format($event->discount, 2, ',', '.') }}
                                                                        {{ $event->discount_percen_flat }}
                                                                    </td>
                                                                    <td>€{{ number_format($event->final_price, 2, ',', '.') }}</td>
                                                                    <td>€{{ number_format($event->vat_amount, 2, ',', '.') }}</td>
                                                                    <td>€{{ number_format($event->final_price_with_vat, 2, ',', '.') }}</td>

                                                                    <td class="text-nowrap sticky-action-col bg-white">
                                                                        <a href="{{ route('booking-events.edit', $event) }}"
                                                                            class="btn btn-warning btn-sm me-1">Edit</a>
                                                                        <a href="{{ route('booking-events.view', $event) }}"
                                                                            class="btn btn-success btn-sm me-1">View</a>
                                                                        <a href="{{ route('booking-events.email', $event) }}"
                                                                            class="btn btn-info btn-sm me-1">Email</a>
                                                                        <form action="{{ route('booking-events.destroy', $event) }}" method="POST"
                                                                            class="d-inline">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                                onclick="return confirm('Are you sure?')">Delete</button>
                                                                        </form>
                                                                    </td>

                                                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    {{ $events->appends([
    'search' => request('search'),
    'is_done' => request('is_done')
])->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>