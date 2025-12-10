<!DOCTYPE html>
<html>

<head>
    <title>Quote PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #333;
            background: #fff;
        }

        .header {
            background: #1E90FF;
            color: #fff;
            padding: 14px;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            border-bottom: 3px solid #1565C0;
        }

        .container {
            padding: 20px 25px;
        }

        h3 {
            color: #1E90FF;
            margin: 0 0 8px 0;
            font-size: 18px;
        }

        p {
            margin: 3px 0;
        }

        .grand-total {
            font-size: 15px;
            font-weight: bold;
            color: #2E7D32;
            margin: 10px 0 20px 0;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #444;
            margin: 15px 0 8px 0;
            border-bottom: 2px solid #1E90FF;
            display: inline-block;
            padding-bottom: 2px;
        }

        .box {
            background: #f9f9f9;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 12px;
            font-size: 12px;
        }

        .flex {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 15px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px 6px;
            text-align: left;
        }

        .table th {
            background: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .table td {
            vertical-align: top;
        }

        .summary {
            margin-top: 15px;
            padding: 10px;
            background: #f1f8ff;
            border-left: 4px solid #1E90FF;
        }

        ul {
            padding-left: 15px;
            margin: 0;
        }

        li {
            margin: 3px 0;
        }
    </style>
</head>

<body>
    <div class="header">Quote Details</div>
    <div class="container">

        <h3>{{ $quote->subject }}</h3>
        <p>{{ $quote->description }}</p>
        <p class="grand-total">Grand Total: ₹{{ number_format($quote->grand_total, 2) }}</p>

        <div class="flex">
            <div class="box" style="flex:1;">
                <div class="section-title">Billing Address</div>
                <ul>
                    <li><strong>Country:</strong> {{ $quote->billing_address['country'] ?? 'N/A' }}</li>
                    <li><strong>State:</strong> {{ $quote->billing_address['state'] ?? 'N/A' }}</li>
                    <li><strong>City:</strong> {{ $quote->billing_address['city'] ?? 'N/A' }}</li>
                    <li><strong>Postcode:</strong> {{ $quote->billing_address['postcode'] ?? 'N/A' }}</li>
                
                </ul>
            </div>
            <div class="box" style="flex:1;">
                <div class="section-title">Shipping Address</div>
                <ul>
                    <li><strong>Country:</strong> {{ $quote->shipping_address['country'] ?? 'N/A' }}</li>
                    <li><strong>State:</strong> {{ $quote->shipping_address['state'] ?? 'N/A' }}</li>
                    <li><strong>City:</strong> {{ $quote->shipping_address['city'] ?? 'N/A' }}</li>
                    <li><strong>Postcode:</strong> {{ $quote->shipping_address['postcode'] ?? 'N/A' }}</li>

                </ul>
            </div>
        </div>

        <div class="flex">
            <div class="box" style="flex:1;">
                <div class="section-title">Contact Person</div>
                @if ($quote->person)
                    <p><strong>Name:</strong> {{ $quote->person->name }}</p>
                    <p><strong>Emails:</strong>
                        {{ $quote->person->emails ? implode(', ', is_string($quote->person->emails) ? json_decode($quote->person->emails, true) : $quote->person->emails) : 'N/A' }}
                    </p>
                    <p><strong>Numbers:</strong>
                        {{ $quote->person->contact_numbers ? implode(', ', is_string($quote->person->contact_numbers) ? json_decode($quote->person->contact_numbers, true) : $quote->person->contact_numbers) : 'N/A' }}
                    </p>
                @else
                    <p>No contact person assigned.</p>
                @endif
            </div>
            <div class="box" style="flex:1;">
                <div class="section-title">Lead Information</div>
                @if ($quote->lead)
                    <p><strong>Title:</strong> {{ $quote->lead->lead_title }}</p>
                    <p><strong>Status:</strong> {{ $quote->lead->status }}</p>
                    <p><strong>Value:</strong> ₹{{ number_format($quote->lead->lead_value, 2) }}</p>
                    <p><strong>Source:</strong> {{ $quote->lead->source ?? 'N/A' }}</p>
                    <p><strong>Created At:</strong> {{ $quote->lead->created_at->format('d M, Y') }}</p>
                @else
                    <p>No lead linked.</p>
                @endif
            </div>
        </div>

        <div class="section-title">Projects</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Tax</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quote->items as $item)
                    <tr>
                        <td>{{ $item->project->name }}</td>
                        <td style="text-align:center;">{{ $item->quantity }}</td>
                        <td style="text-align:right;">₹{{ number_format($item->price, 2) }}</td>
                        <td style="text-align:right;">₹{{ number_format($item->discount_amount ?? 0, 2) }}</td>
                        <td style="text-align:right;">₹{{ number_format($item->tax_amount ?? 0, 2) }}</td>
                        <td style="text-align:right; font-weight:bold;">₹{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</body>

</html>
