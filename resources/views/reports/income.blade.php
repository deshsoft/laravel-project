<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Income Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Search Filters Section -->
                    <div class="mb-4" id="search-section">
                        <form method="GET" action="{{ route('income.report') }}"
                            class="flex flex-wrap gap-2 items-center">
                            <input type="text" id="start_date" name="start_date" value="{{ request('start_date') }}"
                                placeholder="Start Date" class="form-control" style="width: 15%" autocomplete="off" />
                            <input type="text" id="end_date" name="end_date" value="{{ request('end_date') }}"
                                placeholder="End Date" class="form-control" style="width: 15%" autocomplete="off" />

                            <select name="customer_id" class="form-select select2" style="width: 20%">
                                <option value="">All Customers</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->company_name ?? $customer->first_name . ' ' . $customer->last_name }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="asset_type[]" class="form-select select2" style="width: 20%">
                                <option value="">All</option>
                                @foreach ($assetTypes as $type)
                                    <option value="{{ $type }}" {{ in_array($type, (array) request('asset_type')) ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit" class="btn btn-info">Filter</button>
                            <a href="{{ route('income.report') }}" class="btn btn-primary">Reset</a>
                            <button type="button" onclick="printReport()" class="btn btn-secondary">Print
                                Report</button>
                        </form>
                    </div>

                    <!-- Report Section -->
                    <div id="report-section">
                        <h4 class="text-lg font-bold mb-3">Income Report</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th>SL</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Asset Types</th>
                                        <th>Aggregable</th>
                                        <th>Non-Aggregable</th>
                                        <th>Total Price</th>
                                        <th>Discount</th>
                                        <th>Final Price</th>
                                        <th>VAT</th>
                                        <th>Final Price With VAT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalAgg = $totalNonAgg = $totalTotal = $totalDiscount = $totalFinal = $totalVat = $totalWithVat = 0;
                                    @endphp
                                    @forelse ($events as $event)
                                                                        @php
                                                                            $agg = $event->slots->sum('aggregable_price');
                                                                            $nonAgg = $event->slots->sum('non_aggregable_price');
                                                                            $totalPrice = $event->total_price;
                                                                            $final = $event->final_price;
                                                                            $discount = $totalPrice - $final;
                                                                            $discountType = $event->discount_percen_flat ?? '';

                                                                            $percentage = $totalPrice > 0 ? round(($discount / $totalPrice) * 100) : 0;
                                                                            $discountDisplay = '€' . number_format($discount, 2, ',', '.') .
                                                                                ($discountType === 'flat' ? ' (Flat)' : ($discountType === '%' ? " ({$percentage}%)" : ''));

                                                                            $vat = $event->vat_amount ?? 0;
                                                                            $finalWithVat = $event->final_price_with_vat ?? ($final + $vat);

                                                                            $types = $event->assets->pluck('asset.asset_type')->filter()->unique()->implode(', ');
                                                                            $fromDate = optional($event->slots->first())->from_date;
                                                                            $formattedDate = $fromDate ? \Carbon\Carbon::parse($fromDate)->format('d/m/Y') : '-';

                                                                            $totalAgg += $agg;
                                                                            $totalNonAgg += $nonAgg;
                                                                            $totalTotal += $totalPrice;
                                                                            $totalDiscount += $discount;
                                                                            $totalFinal += $final;
                                                                            $totalVat += $vat;
                                                                            $totalWithVat += $finalWithVat;
                                                                        @endphp
                                                                        <tr>
                                                                            <td>{{ $loop->iteration }}</td>
                                                                            <td>{{ $formattedDate }}</td>
                                                                            <td>{{ $event->customer->company_name ?? ($event->customer->first_name . ' ' . $event->customer->last_name) }}
                                                                            </td>
                                                                            <td>{{ $types }}</td>
                                                                            <td>€{{ number_format($agg, 2, ',', '.') }}</td>
                                                                            <td>€{{ number_format($nonAgg, 2, ',', '.') }}</td>
                                                                            <td>€{{ number_format($totalPrice, 2, ',', '.') }}</td>
                                                                            <td>{!! $discountDisplay !!}</td>
                                                                            <td>€{{ number_format($final, 2, ',', '.') }}</td>
                                                                            <td>€{{ number_format($vat, 2, ',', '.') }}</td>
                                                                            <td class="fw-bold">€{{ number_format($finalWithVat, 2, ',', '.') }}</td>
                                                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center text-muted">No income data found for the
                                                selected filters.</td>
                                        </tr>
                                    @endforelse

                                    <tr class="bg-light font-weight-bold">
                                        <td colspan="4" class="text-right">TOTAL</td>
                                        <td>€{{ number_format($totalAgg, 2, ',', '.') }}</td>
                                        <td>€{{ number_format($totalNonAgg, 2, ',', '.') }}</td>
                                        <td>€{{ number_format($totalTotal, 2, ',', '.') }}</td>
                                        <td>€{{ number_format($totalDiscount, 2, ',', '.') }}</td>
                                        <td>€{{ number_format($totalFinal, 2, ',', '.') }}</td>
                                        <td>€{{ number_format($totalVat, 2, ',', '.') }}</td>
                                        <td>€{{ number_format($totalWithVat, 2, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function printReport() {
            const originalContent = document.body.innerHTML;
            const printContent = document.getElementById('report-section').innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            window.location.reload();
        }

        $(document).ready(function () {
            $('#start_date, #end_date').datepicker({
                dateFormat: 'dd/mm/yy', // ✅ jQuery UI correct format
                showAnim: 'fadeIn'
            });
            // 🔍 Activate Select2 on your dropdowns
            $('.select2').select2({
                width: 'resolve',
                placeholder: 'Select an option',
                allowClear: true
            });
        });

    </script>
</x-app-layout>