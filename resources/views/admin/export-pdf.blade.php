<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Export PDF - Transactions</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 6px;
            border: 1px solid #000;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h2>Transaction Report</h2>

    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Checkout Time</th>
                <th>Paid Time</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                @foreach($transaction->details as $detail)
                    <tr>
                        <td>{{ $transaction->email }}</td>
                        <td>{{ $detail->name }}</td>
                        <td>{{ $detail->phone_number }}</td>
                        <td>{{ $transaction->checkout_time }}</td>
                        <td>{{ $transaction->paid_time ?? '-' }}</td>
                        <td>{{ ucfirst($transaction->payment_status) }}</td>
                        <td>Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
