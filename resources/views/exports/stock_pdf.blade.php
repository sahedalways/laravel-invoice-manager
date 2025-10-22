<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Stock Report</title>
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
    </style>
</head>

<body>
    <h2>Stock Report</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>SKU</th>
                <th>Name</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product['SKU'] }}</td>
                    <td>{{ $product['Name'] }}</td>
                    <td>{{ $product['Stock'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
