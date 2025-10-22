<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <title>Invoice - {{ $order->order_number }}</title>
    <link rel="icon" type="image/png" href="{{ siteSetting()->favicon_url }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- Reset & Compact Styling --- */
        body {
            font-family: 'Poppins', sans-serif;
            color: #2c3e50;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }

        .invoice-container {
            max-width: 900px;
            margin: 10px auto;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            border: 1px solid #e0e0e0;
            padding: 10px;
        }

        /* --- Header --- */
        .invoice-header {
            background: #34495e;
            color: #fff;
            padding: 15px 20px;
        }

        .invoice-header h1 {
            margin: 0;
            font-weight: 700;
            font-size: 20px;
        }

        .invoice-header p {
            margin: 2px 0;
            font-size: 11px;
        }

        .invoice-header .header-right {
            text-align: right;
        }

        /* --- Sections --- */
        .invoice-section {
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .invoice-section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-weight: 600;
            margin-bottom: 8px;
            color: #34495e;
            font-size: 13px;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 3px;
        }

        .customer-info p,
        .payment-info p {
            margin: 3px 0;
            font-size: 11px;
        }

        /* --- Table --- */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 11px;
        }

        table th,
        table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }

        table th {
            background: #f5f5f5;
            font-weight: 600;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .total-row {
            font-weight: 600;
            background: #f5f5f5;
            font-size: 12px;
        }

        /* --- Product Image --- */
        .product-info {
            display: flex;
            align-items: center;
        }

        .product-img {
            width: 35px;
            height: 35px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 8px;
        }

        /* --- Footer --- */
        .invoice-footer {
            text-align: center;
            font-size: 10px;
            padding: 8px;
            color: #7f8c8d;
        }

        @media print {
            body {
                background: #fff;
                font-size: 10px;
            }

            .invoice-container {
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="invoice-container">

        <!-- Header -->
        <div class="invoice-header">
            <div style="display:flex; justify-content:space-between; flex-wrap: wrap;">
                <div>
                    <h1>{{ siteSetting()->site_title }}</h1>
                    <p>{{ siteSetting()->site_phone_number }}</p>
                    <p>{{ siteSetting()->site_email }}</p>
                </div>
                <div class="header-right">
                    <p>Invoice #: <strong>{{ $order->order_number }}</strong></p>
                    <p>Date: {{ $order->date }}</p>
                    <p>Status: <strong>{{ ucfirst($order->status) }}</strong></p>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="invoice-section customer-info">
            <div class="section-title">Customer Information</div>
            <p><strong>Name:</strong> {{ $order->customer->name ?? 'Walk-In Customer' }}</p>
            <p><strong>Phone:</strong> {{ $order->customer->phone ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $order->customer->email ?? 'N/A' }}</p>
            <p><strong>Address:</strong> {{ $order->customer->address ?? 'N/A' }}</p>
        </div>

        <!-- Order Items -->
        <div class="invoice-section">
            <div class="section-title">Order Details</div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th style="text-align:left;">Product</th>
                        <th>SKU</th>
                        <th>QTY</th>
                        <th>Discount</th>
                        <th>Tax</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderdetails as $item)
                        @php $product = $item->product; @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <div class="product-info">
                                    <img src="{{ $product->image_url }}" class="product-img"
                                        alt="{{ $product->name }}">
                                    <span>{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="text-center">{{ $product->sku ?? 'N/A' }}</td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td class="text-center">{{ $item->discount ?? ($order->discount ?? 0) }}%</td>
                            <td class="text-center">{{ number_format($item->tax ?? 0, 2) }}</td>
                            <td class="text-right">{{ currency_symbol() }}{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="6" class="text-right">Total:</td>
                        <td class="text-right">{{ currency_symbol() }}{{ number_format($order->total_price, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Payment Info -->
        <div class="invoice-section payment-info">
            <div class="section-title">Payment Information</div>
            <p><strong>Payment Mode:</strong> Cash</p>
            <p><strong>Gross Total:</strong> {{ currency_symbol() }}{{ number_format($order->total_price, 2) }}</p>
            <p><strong>Discount:</strong> {{ $order->discount ?? 0 }}%</p>
            <p><strong>Paid Amount:</strong> {{ currency_symbol() }}{{ number_format($order->total_price, 2) }}</p>
            <p><strong>Due Amount:</strong> {{ currency_symbol() }}0.00</p>
        </div>

        <div class="invoice-footer">
            Thank you for your business! Contact us at {{ siteSetting()->site_email }} for any questions.
        </div>
    </div>
</body>

</html>
