<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Orders Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            color: #fff;
            display: inline-block;
        }

        .completed {
            background-color: #28a745;
        }

        .pending {
            background-color: #ffc107;
        }

        .cancelled {
            background-color: #dc3545;
        }

        .returned {
            background-color: #17a2b8;
        }
    </style>
</head>

<body>
    <h2>Orders Report</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Order No</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total</th>
                <th>Payment</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order['Order No'] }}</td>
                    <td>{{ $order['Customer'] }}</td>
                    <td>{{ $order['Date'] }}</td>
                    <td>
                        <span class="badge {{ strtolower($order['Status']) }}">
                            {{ $order['Status'] }}
                        </span>
                    </td>
                    <td>{{ currency_symbol() }}{{ number_format($order['Total'], 2) }}</td>
                    <td>Cash</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
