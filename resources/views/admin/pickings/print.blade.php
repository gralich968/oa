<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Picking List</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Picking List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>UPC</th>
                <th>Dollies|Tracks</th>
                <th>Quantity</th>
                <th>Picked</th>
                <th>Remaining</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->product }}</td>
                    <td>{{ $item->sku }}</td>
                    <td>{{ $item->trayod }}</td>
                    <td>{{ $item->quantity_sum }}</td>
                    <td>{{ $item->picked_sum }}</td>
                    <td>{{ $item->remaining }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
            <td><strong>{{ $totals['dollies_sum'] }} | {{ $totals['tracks'] }}</strong></td>
            <td><strong>{{ $totals['quantity_sum'] }}</strong></td>
            <td><strong>{{ $totals['picked_sum'] }}</strong></td>
            <td><strong>{{ $totals['remaining'] }}</strong></td>
        </tr>
    </tfoot>
    </table>
</body>
</html>
