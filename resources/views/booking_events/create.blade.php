<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Booking Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class=" mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('booking-events.store') }}">
                @csrf
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-6 text-gray-900">
                        <div class="row gap-4">
                            <div class="mb-4 col">
                                <label class="block text-gray-700">Title</label>
                                <input type="text" name="title"
                                    class="w-full px-4 py-2 border rounded-md focus:outline-none" required>
                            </div>

                            <div class="mb-4 col">
                                <label class="block text-gray-700">Customer</label>
                                <select name="fk_customer"
                                    class="w-full px-4 py-2 border rounded-md focus:outline-none select2" required>
                                    <option value="">Select a Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <!-- Aggregable Assets Section -->
                                <div class="mb-4 border p-4 rounded-md">
                                    <h3 class="mb-2 text-lg font-bold">Aggregable Assets</h3>

                                    <table id="aggregable-assets-table"
                                        class="w-full border-collapse border border-gray-300">

                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border border-gray-300 px-2 py-2 text-left">Select</th>
                                                <th class="border border-gray-300 px-2 py-2 text-left">Asset Type</th>
                                                <th class="border border-gray-300 px-2 py-2 text-left">Rental Value</th>
                                                <th class="border border-gray-300 px-2 py-2 text-left">Fixed / Hourly
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($assets as $asset)
                                                @if($asset->mode == 'aggregable')
                                                    <tr>
                                                        <td class="border border-gray-300 px-2 py-2 text-center">
                                                            <input type="checkbox" name="assets[{{ $asset->id }}][selected]"
                                                                value="1" class="toggle-qty">
                                                        </td>
                                                        <td class="border border-gray-300 px-2 py-2">{{ $asset->asset_type }}
                                                        </td>
                                                        <td class="border border-gray-300 px-2 py-2">
                                                            €{{ number_format($asset->rental_value, 2, ',', '.') }}
                                                            <input type="hidden" name="assets[{{ $asset->id }}][total]"
                                                                class="w-20 px-2 py-1 border rounded-md total-input w-full text-right"
                                                                min="1" placeholder="Total" value="{{ $asset->rental_value }}">
                                                        </td>
                                                        <td class="border border-gray-300 px-2 py-2">{{ $asset->fixed_hourly }}
                                                            <input type="hidden" name="assets[{{ $asset->id }}][fixed_hourly]"
                                                                value="{{ $asset->fixed_hourly }}">

                                                        </td>
                                                        <input type="hidden" name="assets[{{ $asset->id }}][rental_value]"
                                                            value="{{ $asset->rental_value }}">

                                                    </tr>
                                                @endif

                                            @endforeach

                                        </tbody>
                                        <!-- <tfoot>
                                            <tr>
                                                <td colspan="2" class="border border-gray-300 px-2 py-2 text-center">
                                                    Total Price
                                                </td>
                                                <td class="border border-gray-300 px-2 py-2 text-center">
                                                    <input type="text" id="total_aggregable" name="aggregable_price"
                                                        class="w-full px-2 py-2 border rounded-md" readonly>
                                                </td>


                                            </tr>
                                        </tfoot> -->
                                    </table>


                                </div>
                            </div>

                            <div class="col-md-7">
                                <!-- Non-Aggregable Assets Section -->

                                <div class="mb-4 border p-4 rounded-md row">
                                    <div class="col-md-12">
                                        <h3 class="mb-2 text-lg font-bold">Non-Aggregable Assets</h3>

                                        <table id="non-aggregable-assets-table"
                                            class="w-full border-collapse border border-gray-300">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th class="border border-gray-300 px-2 py-2 text-left">Select</th>
                                                    <th class="border border-gray-300 px-2 py-2 text-left">Asset Type
                                                    </th>
                                                    <th class="border border-gray-300 px-2 py-2 text-left">Rental Value
                                                    </th>
                                                    <th class="border border-gray-300 px-2 py-2 text-left">Available Qty
                                                    </th>
                                                    <th class="border border-gray-300 px-2 py-2 text-left">Qty</th>
                                                    <th class="border border-gray-300 px-2 py-2 text-left">Row Total
                                                    </th>
                                                    <th class="border border-gray-300 px-2 py-2 text-left">Fixed /
                                                        Hourly
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($assets as $asset)
                                                    @if($asset->mode == 'non-aggregable')
                                                        <tr>
                                                            <td class="border border-gray-300 px-2 py-2 text-center">
                                                                <input type="checkbox" name="assets[{{ $asset->id }}][selected]"
                                                                    value="1" class="toggle-qty">
                                                            </td>
                                                            <td class="border border-gray-300 px-2 py-2">
                                                                {{ $asset->asset_type }}
                                                            </td>
                                                            <td class="border border-gray-300 px-2 py-2">
                                                                €{{ number_format($asset->rental_value, 2, ',', '.') }}</td>
                                                            <td class="border border-gray-300 px-2 py-2 text-center">
                                                                {{ $asset->available_quantity }}
                                                                <input type="hidden"
                                                                    name="assets[{{ $asset->id }}][available_quantity]"
                                                                    class="w-20 px-2 py-1 border rounded-md available_quantity-input text-center"
                                                                    min="1" readonly value="{{ $asset->available_quantity }}">
                                                            </td>
                                                            <td class="border border-gray-300 px-2 py-2 text-center">
                                                                <input type="number" name="assets[{{ $asset->id }}][qty]"
                                                                    class="w-20 px-2 py-1 border rounded-md qty-input hidden text-center"
                                                                    min="1" placeholder="Qty">
                                                            </td>
                                                            <td class="border border-gray-300 px-2 py-2 text-center">
                                                                <input type="hidden" name="assets[{{ $asset->id }}][total]"
                                                                    class="w-20 px-2 py-1 border rounded-md total-input hidden w-full text-right"
                                                                    min="1" placeholder="Total" readonly>
                                                                <span class="total-input-display">0,00</span>
                                                            </td>
                                                            <td class="border border-gray-300 px-2 py-2 text-center">
                                                                {{ $asset->fixed_hourly }}
                                                                <input type="hidden"
                                                                    name="assets[{{ $asset->id }}][fixed_hourly]"
                                                                    class="w-20 px-2 py-1 border rounded-md fixed_hourly-input text-center"
                                                                    min="1" readonly value="{{ $asset->fixed_hourly }}">
                                                            </td>
                                                            <input type="hidden" name="assets[{{ $asset->id }}][rental_value]"
                                                                value="{{ $asset->rental_value }}">
                                                        </tr>
                                                    @endif

                                                @endforeach
                                            </tbody>
                                            <!-- <tfoot>
                                                <tr>
                                                    <td colspan="5" class="border border-gray-300 px-2 py-2 text-right">
                                                        Total Price for Non-Aggregable
                                                    </td>
                                                    <td class="border border-gray-300 px-2 py-2 text-right">
                                                        <input type="text" id="total_non_aggregable"
                                                            name="non_aggregable_price"
                                                            class="w-full px-2 py-2 border rounded-md text-right"
                                                            readonly>
                                                    </td>


                                                </tr>
                                            </tfoot> -->
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row gap-4">
                            <div class="mb-4 col">
                                <label class="block text-gray-700">Note</label>
                                <textarea name="note" class="w-full px-4 py-2 border rounded-md focus:outline-none"
                                    rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-6 text-gray-900">
                        <div>
                            <table id="bookingSlots" class="w-full border-collapse border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="border border-gray-300 px-2 py-2">From Date</th>
                                        <th class="border border-gray-300 px-2 py-2" style="width:10%;">From Time</th>
                                        <th class="border border-gray-300 px-2 py-2" style="width:10%;">To Time</th>
                                        <th class="border border-gray-300 px-2 py-2">To Date (Optional)</th>
                                        <th class="border border-gray-300 px-2 py-2">Aggregable Price</th>
                                        <th class="border border-gray-300 px-2 py-2">Non-Aggregable Price</th>
                                        <th class="border border-gray-300 px-2 py-2">Slot Price</th>
                                        <th class="border border-gray-300 px-2 py-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="booking-slot">
                                        <td class="border border-gray-300 px-2 py-2">
                                            <input type="text" name="slots[0][from_date]"
                                                class="w-full px-2 py-2 border rounded-md focus:outline-none from-date"
                                                value="{{ old('slots.0.from_date', $selectedDate ?? '') }}" required
                                                autocomplete="off">
                                        </td>
                                        <td class="border border-gray-300 px-2 py-2">
                                            <select name="slots[0][from_time]"
                                                class="w-full px-2 py-2 border rounded-md from-time" required>
                                                <option value=""></option>
                                                @foreach($timeSlots as $key => $value)
                                                    <option value="{{ $value }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="border border-gray-300 px-2 py-2">
                                            <select name="slots[0][to_time]"
                                                class="w-full px-2 py-2 border rounded-md to-time">
                                                <option value=""></option>
                                                @foreach($timeSlots as $key => $value)
                                                    <option value="{{ $value }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="border border-gray-300 px-2 py-2">
                                            <input type="text" name="slots[0][to_date]"
                                                class="w-full px-2 py-2 border rounded-md to-date"
                                                value="{{ old('slots.0.from_date', $selectedDate ?? '') }}"
                                                autocomplete="off">
                                        </td>
                                        <td class="border border-gray-300 px-2 py-2">
                                            <input type="hidden" name="slots[0][aggregable_price]"
                                                class="w-full px-2 py-2 border rounded-md aggregable-price" readonly>
                                            <span class="aggregable-price-display">0,00</span>
                                        </td>
                                        <td class="border border-gray-300 px-2 py-2">
                                            <input type="hidden" name="slots[0][non_aggregable_price]"
                                                class="w-full px-2 py-2 border rounded-md non-aggregable-price"
                                                readonly>
                                            <span class="non-aggregable-price-display">0,00</span>
                                        </td>
                                        <td class="border border-gray-300 px-2 py-2">
                                            <input type="hidden" name="slots[0][slot_price]"
                                                class="w-full px-2 py-2 border rounded-md slot-price" readonly>
                                            <span class="slot-price-display">0,00</span>
                                        </td>
                                        <td class="border border-gray-300 px-2 py-2 text-center">
                                            <button type="button"
                                                class="remove-slot px-2 py-1 bg-red-500 text-white rounded">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </td>

                                    </tr>
                                </tbody>
                                <tfoot>
                                    <td class="border border-gray-300 px-2 py-2 text-right" colspan="6">
                                        Total Price
                                    </td>
                                    <td class="border border-gray-300 px-2 py-2">
                                        <input type="hidden" id="total_price" name="total_price"
                                            class="w-full px-4 py-2 border rounded-md" readonly>
                                        <span id="total_price_display">0,00</span>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-2 text-center">
                                        <button type="button" id="addSlot"
                                            class="add-slot px-2 btn btn-success text-white rounded">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </td>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>


                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-6 text-gray-900">
                        <div class="row">
                            <!-- Discount -->
                            <div class="mb-4 col">
                                <label class="block text-gray-700">Discount Flat/% (Optional)</label>
                                <input style="width:70%; float:left;" type="number" name="discount" id="discount"
                                    class="px-4 py-2 border rounded-md" min="0">
                                <select style="width:30%; float:left;" name="discount_percen_flat"
                                    id="discount_percen_flat" class="px-4 py-2 border rounded-md focus:outline-none">
                                    <option value="Flat">Flat</option>
                                    <option value="%">%</option>
                                </select>
                            </div>



                            <!-- Final Price -->
                            <div class="mb-4 col">
                                <label class="block text-gray-700">Final Price</label>
                                <input type="hidden" id="final_price" name="final_price"
                                    class="w-full px-4 py-2 border rounded-md" readonly>
                                <span id="final_price_display">0,00</span>
                            </div>

                            <div class="mb-4 col">
                                <label class="block text-gray-700">VAT 22%</label>
                                <input type="hidden" name="vat_amount" id="vat_amount"
                                    class="w-full px-4 py-2 border rounded-md" min="0">
                                <span id="vat_amount_display">0,00</span>
                            </div>

                            <!-- Final Price -->
                            <div class="mb-4 col">
                                <label class="block text-gray-700">Final Price With VAT</label>
                                <input type="hidden" id="final_price_with_vat" name="final_price_with_vat"
                                    class="w-full px-4 py-2 border rounded-md" readonly>
                                <span id="final_price_with_vat_display">0,00</span>
                            </div>

                        </div>


                    </div>
                </div>
                <button type="submit" class="btn btn-success">Save Event</button>
                <a href="{{ route('booking-events.index') }}" class="btn btn-secondary no-print">Back to List</a>
                <a href="{{ route('booking-events.calendar') }}" class="btn btn-secondary no-print">Back to Calendar</a>
            </form>
        </div>
    </div>

</x-app-layout>

<script>
    $(document).ready(function () {

        function parseDateString(dateStr) {
            if (!dateStr || !dateStr.includes('/')) return new Date();
            const parts = dateStr.split('/');
            return new Date(`${parts[2]}-${parts[1]}-${parts[0]}`);
        }

        function formatItalian(value) {
            let num = parseFloat(value);
            if (isNaN(num)) return "0,00";
            return num.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function calculatePrices() {
            $("input[type=checkbox]:checked").each(function () {
                let row = $(this).closest("tr");
                let rentalValueInput = row.find("input[type=hidden][name*='[rental_value]']");
                if (!rentalValueInput.length) return;

                let rentalValue = parseFloat(rentalValueInput.val()) || 0;
                let qtyInput = row.find(".qty-input");
                let totalInput = row.find(".total-input");
                let qty = (qtyInput.length && !qtyInput.hasClass("hidden") && qtyInput.val()) ? parseInt(qtyInput.val()) : 1;

                if (row.closest("table").attr("id") !== "aggregable-assets-table") {
                    rentalValue *= qty;
                }

                if (totalInput.length) {
                    totalInput.val((rentalValue).toFixed(2));
                    let totalDisplay = row.find(".total-input-display");
                    if (totalDisplay.length) {
                        totalDisplay.text(formatItalian(rentalValue));
                    }
                }
            });

            calculateSlotPrice();
        }
        function checkAggregableAvailability(slot) {
            const fromDate = slot.find(".from-date").val();
            const toDate = slot.find(".to-date").val() || fromDate;
            const fromTime = slot.find(".from-time").val();
            const toTime = slot.find(".to-time").val();

            if (!fromDate || !fromTime || !toTime) return;

            let selectedAssetIds = [];
            $("#aggregable-assets-table tbody tr").each(function () {
                let checkbox = $(this).find("input.toggle-qty");
                if (checkbox.is(":checked")) {
                    let nameAttr = checkbox.attr("name");
                    let assetId = nameAttr.match(/\[(\d+)\]/)?.[1];
                    if (assetId) {
                        selectedAssetIds.push(assetId);
                    }
                }
            });

            if (selectedAssetIds.length === 0) return;

            $.ajax({
                url: "/booking-events/check-aggregable-availability",
                method: "POST",
                data: {
                    from_date: fromDate,
                    to_date: toDate,
                    from_time: fromTime,
                    to_time: toTime,
                    asset_ids: selectedAssetIds,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (response) {
                    if (response.conflicts && response.conflicts.length > 0) {
                        let message = "The following assets are already booked:\n\n";

                        response.conflicts.forEach(conflict => {
                            message += `-Date ${conflict.conflict_date} ${conflict.asset_name} from ${conflict.from} to ${conflict.to}\n`;
                        });

                        alert(message);

                        slot.find(".from-time, .to-time, .from-date, .to-date").val('');
                        calculateSlotPrice();
                    }
                }
            });
        }

        function validateSlotOverlap(slot) {
            const fromDateStr = slot.find(".from-date").val();
            const toDateStr = slot.find(".to-date").val() || fromDateStr;
            const fromTimeVal = parseInt(slot.find(".from-time").val()) || 0;
            const toTimeVal = parseInt(slot.find(".to-time").val()) || fromTimeVal + 1;

            if (!fromDateStr || !fromTimeVal || !toTimeVal) return true;

            const fromDate = parseDateString(fromDateStr);
            const toDate = parseDateString(toDateStr);

            let hasOverlap = false;

            $(".booking-slot").each(function () {
                const otherSlot = $(this);
                if (otherSlot.is(slot)) return;

                const otherFromDateStr = otherSlot.find(".from-date").val();
                const otherToDateStr = otherSlot.find(".to-date").val() || otherFromDateStr;
                const otherFromTime = parseInt(otherSlot.find(".from-time").val()) || 0;
                const otherToTime = parseInt(otherSlot.find(".to-time").val()) || otherFromTime + 1;

                if (!otherFromDateStr) return;

                const otherFromDate = parseDateString(otherFromDateStr);
                const otherToDate = parseDateString(otherToDateStr);

                const datesOverlap = fromDate <= otherToDate && toDate >= otherFromDate;
                const timesOverlap = fromTimeVal < otherToTime && toTimeVal > otherFromTime;

                if (datesOverlap && timesOverlap) {
                    hasOverlap = true;
                    return false;
                }
            });

            if (hasOverlap) {
                alert("This slot overlaps with another slot. Please adjust the date/time.");
                slot.find(".from-time, .to-time, .from-date, .to-date").val('');
                return false;
            }

            return true;
        }

        function calculateSlotPrice() {
            let totalSlotPrice = 0;

            $("#bookingSlots tbody tr.booking-slot").each(function () {
                let slot = $(this);
                let totalAggregable = 0;
                let totalNonAggregable = 0;

                let fromDateValue = slot.find(".from-date").val();
                let toDateValue = slot.find(".to-date").val() || fromDateValue;

                let fromTime = parseInt(slot.find(".from-time").val()) || 0;
                let toTime = parseInt(slot.find(".to-time").val()) || fromTime + 1;

                if (!fromDateValue) return;

                let fromDate = parseDateString(fromDateValue);
                let toDate = parseDateString(toDateValue);
                let days = Math.max((toDate - fromDate) / (1000 * 60 * 60 * 24) + 1, 1);
                let hours = Math.max(toTime - fromTime, 1);

                $("#aggregable-assets-table tbody tr").each(function () {
                    let row = $(this);
                    let checkbox = row.find("input[type='checkbox']");
                    if (checkbox.is(":checked")) {
                        let rentalValue = parseFloat(row.find("input[name*='[rental_value]']").val()) || 0;
                        let fixedHourly = row.find("input[name*='[fixed_hourly]']").val();
                        totalAggregable += (fixedHourly === "fixed") ? rentalValue * days : rentalValue * hours * days;
                    }
                });

                $("#non-aggregable-assets-table tbody tr").each(function () {
                    let row = $(this);
                    let checkbox = row.find("input[type='checkbox']");
                    if (checkbox.is(":checked")) {
                        let rentalValue = parseFloat(row.find("input[name*='[rental_value]']").val()) || 0;
                        let quantity = parseInt(row.find("input[name*='[qty]']").val()) || 1;
                        let fixedHourly = row.find("input[name*='[fixed_hourly]']").val();
                        totalNonAggregable += (fixedHourly === "fixed") ? rentalValue * quantity * days : rentalValue * quantity * hours * days;
                    }
                });

                let slotPrice = totalAggregable + totalNonAggregable;

                slot.find(".aggregable-price").val(totalAggregable.toFixed(2));
                slot.find(".aggregable-price-display").text(formatItalian(totalAggregable));
                slot.find(".non-aggregable-price").val(totalNonAggregable.toFixed(2));
                slot.find(".non-aggregable-price-display").text(formatItalian(totalNonAggregable));
                slot.find(".slot-price").val(slotPrice.toFixed(2));
                slot.find(".slot-price-display").text(formatItalian(slotPrice));

                totalSlotPrice += slotPrice;
            });

            $("#total_price").val(totalSlotPrice.toFixed(2));
            $("#total_price_display").text(formatItalian(totalSlotPrice));
            calculateFinalPrice(totalSlotPrice);
        }

        function calculateFinalPrice(totalSlotPrice) {
            let discountValue = parseFloat($("#discount").val()) || 0;
            let discountType = $("select[name='discount_percen_flat']").val();
            let discountAmount = (discountType === "%") ? (totalSlotPrice * discountValue) / 100 : discountValue;
            let finalPrice = Math.max(totalSlotPrice - discountAmount, 0);
            let vatAmount = finalPrice * 0.22;
            let finalPriceWithVAT = finalPrice + vatAmount;

            $("#final_price").val(finalPrice.toFixed(2));
            $("#final_price_display").text(formatItalian(finalPrice));
            $("#vat_amount").val(vatAmount.toFixed(2));
            $("#vat_amount_display").text(formatItalian(vatAmount));
            $("#final_price_with_vat").val(finalPriceWithVAT.toFixed(2));
            $("#final_price_with_vat_display").text(formatItalian(finalPriceWithVAT));
        }

        function attachCalculationListeners(slot) {
            slot.find(".from-time, .to-time, .to-date, .from-date").on("change", function () {
                const isValid = validateSlotOverlap(slot);
                if (isValid) {
                    checkAggregableAvailability(slot);
                    calculateSlotPrice();
                }
            });
        }

        function attachValidationListeners(slot) {
            let fromTime = slot.find(".from-time");
            let toTime = slot.find(".to-time");
            let fromDate = slot.find(".from-date");
            let toDate = slot.find(".to-date");

            fromTime.on("change", function () {
                let selectedIndex = fromTime.prop("selectedIndex");
                toTime.find("option").each(function (index) {
                    $(this).prop("disabled", index <= selectedIndex);
                });
            });

            fromDate.on("change", function () {
                const fromDateVal = fromDate.val();
                if (fromDateVal) {
                    const parts = fromDateVal.split('/');
                    const minDate = new Date(`${parts[2]}-${parts[1]}-${parts[0]}`);
                    toDate.datepicker("option", "minDate", minDate);
                }
            });
        }

        function validateQtyInput(input) {
            input.on("input", function () {
                let row = input.closest("tr");
                let availableQty = parseInt(row.find("input[name*='[available_quantity]']").val()) || 0;
                let enteredQty = parseInt(input.val()) || 1;

                if (enteredQty > availableQty) {
                    alert("Entered quantity exceeds available quantity. Maximum allowed: " + availableQty);
                    input.val(availableQty);
                } else if (enteredQty < 1) {
                    input.val(1);
                }

                calculateSlotPrice();
            });
        }

        $(".qty-input").each(function () {
            validateQtyInput($(this));
        });

        function updateSlotIndexes() {
            $(".booking-slot").each(function (index) {
                $(this).find("input, select").each(function () {
                    let name = $(this).attr("name");
                    if (name) {
                        $(this).attr("name", name.replace(/\d+/, index));
                    }
                });
            });
        }

        function removeSlot() {
            if ($(".booking-slot").length > 1) {
                $(this).closest(".booking-slot").remove();
                updateSlotIndexes();
                calculateSlotPrice();
            }
        }

        $("#addSlot").on("click", function () {
            let slotsContainer = $("#bookingSlots tbody");
            let slotCount = $(".booking-slot").length;
            let newSlot = $(".booking-slot").first().clone(true, true);

            newSlot.find("input, select").each(function () {
                let name = $(this).attr("name");
                if (name) {
                    $(this).attr("name", name.replace(/\d+/, slotCount)).val("");
                }
            });

            let fromDateInput = newSlot.find(".from-date");
            let toDateInput = newSlot.find(".to-date");

            fromDateInput.removeClass('hasDatepicker').removeAttr('id').datepicker({ dateFormat: "dd/mm/yy", minDate: 0 });
            toDateInput.removeClass('hasDatepicker').removeAttr('id').datepicker({ dateFormat: "dd/mm/yy", minDate: 0 });

            fromDateInput.on("change", function () {
                const val = fromDateInput.val();
                if (val) {
                    const parts = val.split('/');
                    const minDate = new Date(`${parts[2]}-${parts[1]}-${parts[0]}`);
                    toDateInput.datepicker("option", "minDate", minDate);
                }
            });

            attachCalculationListeners(newSlot);
            attachValidationListeners(newSlot);

            newSlot.find(".remove-slot").on("click", removeSlot);
            slotsContainer.append(newSlot);
            updateSlotIndexes();
        });

        $(".remove-slot").on("click", removeSlot);

        $(".toggle-qty").on("change", function () {
            let row = $(this).closest("tr");
            let qtyInput = row.find(".qty-input");
            let totalInput = row.find(".total-input");

            if ($(this).is(":checked")) {
                qtyInput.removeClass("hidden");
                totalInput.removeClass("hidden");
                validateQtyInput(qtyInput);
            } else {
                qtyInput.addClass("hidden").val("");
                totalInput.addClass("hidden").val("");
            }
        });

        $('input[type=checkbox], input.qty-input, #discount, select[name="discount_percen_flat"]').on("input change", function () {
            calculatePrices();
        });

        $(".from-date, .to-date").datepicker({ dateFormat: "dd/mm/yy", minDate: 0 });

        $(".booking-slot").each(function () {
            const slot = $(this);
            attachCalculationListeners(slot);
            attachValidationListeners(slot);
        });
        // 🔍 Activate Select2 on your dropdowns
        $('.select2').select2({
            width: 'resolve',
            placeholder: 'Select an option',
            allowClear: true
        });
    });

</script>