<!DOCTYPE html>
<html>
<head>
    <title>Laravel PDF Example</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrat.min.css" >
    <style>
        body {
            font-family: 'Arial, sans-serif';
            font-size: 16px;
        }
        .container {
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;

        }
        .content {
            font-size: 16px;
        }
        table {
  border-collapse: collapse;
  width: 100%;
}

table, th, td {
  border: 1px solid black;
  text-align: center;
}

th, td {
  padding: 5px;
}

    </style>
</head>
<body>
@foreach($data as $depo)
    <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: flex-start; width: 100%;">
        <div style="text-align: left;">
            <div class="deponame">
            <h1>{{ $depo['depoName'] }}</h1>
            <p>Depo Date: {{ \Carbon\Carbon::parse($depo['dueDate'])->isoFormat('Do MMMM YYYY') }}</p>
            </div>
        </div>
        <div style="text-align: right;">
            <div class="code"><br />
                <img src="data:image/png;base64,{!! $depo['barcode'] !!}" width="300" height="100">
                <br />
                {{ 'Po No: (400)'.$depo['poNumber'] }}
            </div>
        </div>
    </div>

    <br />

        <div class="content">
    <table>
        <tr>
          <th>Position</th>
          <th style="width: 45%">Product</th>
          <th>Qty</th>
          <th>NOTE</th>
          <th>UPT</th>
          <th>SLIFE</th>
        </tr>
        <tbody>

            @foreach($depo['orders'] as $order)
                <tr>
                    <td>{{ $order->positionsposId }}</td>
                    <td>
                        @php
                            $product = \App\Models\tblproducts::where('sku', $order->itemNumber)->first();
                        @endphp
                        @if($product)
                            <small>{{ $order->itemNumber }} - {{ $product->description }}</small>
                        @endif
                    </td>
                    <td>{{ $order->requestQty }}</td>
                    <td>{{ $order->nic }}</td>
                    <td>{{ $order->sparenumber1 }}</td>
                    @php
                        $bbd = \App\Models\tblproducts::select('slife')
                            ->where('sku', $order->itemNumber)
                            ->first();
                        $slifeDate = '';
                        if ($bbd && $bbd->slife) {
                            $slifeDate = \Carbon\Carbon::parse($depo['dueDate'])
                                ->subDay()
                                ->addDays($bbd->slife)
                                ->format('d-m-Y');
                        }
                    @endphp
                    <td>{{ $slifeDate }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    </div>
    <div style="page-break-after: always;"></div>
@endforeach
</body>
</html>
