<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Morrisons Stock</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin-top: 10px;}
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
   <p>Printed on: {{ $currentDate }}</p>
    <h2>MORRISONS STOCK</h2>
    <table>

        <thead>
            <tr>
                <th>QTY</th>
                <th>PRODUCT</th>
                <th>SKU</th>
                <th>BB DATE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->barcode }}</td>
                    <td>{{ $item->bbdate }}</td>                    
                </tr>
            @endforeach
        </tbody>
       
    </table>
</body>
</html>
