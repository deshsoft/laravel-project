<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Booking Event Invoice</title>
</head>

<body style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
    <div style="max-width: 800px; margin: auto; padding: 20px;">

        <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{ asset('images/logo.png') }}" alt="S4B" style="height: 60px;"><br>
            <h2 style="margin: 10px 0;">Booking Event Invoice</h2>
        </div>

        <p><strong>Title:</strong> {{ $bookingEvent->title }}</p>
        <p><strong>Customer:</strong> {{ $bookingEvent->customer->company_name }}</p>

        <h3 style="margin-top: 30px;">Assets</h3>
        <table width="100%" border="1" cellspacing="0" cellpadding="8" style="border-collapse: collapse;">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th align="left">Asset Type</th>
                    <th align="left">Rental Value</th>
                    <th align="center">Quantity</th>
                    <th align="center">Fixed / Hourly</th>
                    <th align="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($selectedAssets as $asset)
                    <tr>
                        <td>{{ $asset['asset_type'] }}</td>
                        <td>€{{ number_format($asset['price'], 2, ',', '.') }}</td>
                        <td align="center">{{ $asset['qty'] }}</td>
                        <td align="center">{{ $asset['fixed_hourly'] }}</td>
                        <td align="right">€{{ number_format($asset['total'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3 style="margin-top: 30px;">Booking Slots</h3>
        <table width="100%" border="1" cellspacing="0" cellpadding="8" style="border-collapse: collapse;">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th>From Date</th>
                    <th>From Time</th>
                    <th>To Time</th>
                    <th>To Date</th>
                    <th>Aggregable Price</th>
                    <th>Non-Aggregable Price</th>
                    <th>Slot Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookingSlots as $slot)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($slot->from_date)->format('d/m/Y') }}</td>
                        <td>{{ $slot->from_time }}</td>
                        <td>{{ $slot->to_time }}</td>
                        <td>{{ $slot->to_date ? \Carbon\Carbon::parse($slot->to_date)->format('d/m/Y') : '-' }}</td>
                        <td>€{{ number_format($slot->aggregable_price, 2, ',', '.') }}</td>
                        <td>€{{ number_format($slot->non_aggregable_price, 2, ',', '.') }}</td>
                        <td>€{{ number_format($slot->slot_price, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3 style="margin-top: 30px;">Summary</h3>
        <table width="100%" cellspacing="0" cellpadding="8" style="border-collapse: collapse;">
            <tr>
                <td align="right"><strong>Total Price:</strong></td>
                <td align="right">€{{ number_format($bookingEvent->total_price, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td align="right"><strong>Discount:</strong></td>
                <td align="right">€{{ number_format($bookingEvent->discount, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td align="right"><strong>Net Price:</strong></td>
                <td align="right">€{{ number_format($bookingEvent->final_price, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td align="right"><strong>VAT (22%):</strong></td>
                <td align="right">€{{ number_format($bookingEvent->vat_amount, 2, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #f2f2f2;">
                <td align="right"><strong>Final Price with VAT:</strong></td>
                <td align="right">
                    <strong>€{{ number_format($bookingEvent->final_price_with_vat, 2, ',', '.') }}</strong>
                </td>
            </tr>
        </table>

        @if($bookingEvent->note)
            <p style="margin-top: 20px;"><strong>Note:</strong> {{ $bookingEvent->note }}</p>
        @endif

        <p style="margin-top: 40px;">Thanks,<br>{{ config('app.name') }}</p>
    </div>
</body>

</html>