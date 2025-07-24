<table>
    <thead>
        <tr>
            <th>Email</th>
            <th>Name</th>
            <th>Phone Number</th>
            <th>Checkout Time</th>
            <th>Paid Time</th>
            <th>Payment Status</th>
            <th>Total Amount</th>
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
                    <td>{{ $transaction->total_amount }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
