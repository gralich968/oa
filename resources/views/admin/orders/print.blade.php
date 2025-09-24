<!DOCTYPE html>
<html>
<head>
    <title>PDF Print</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" >
    <style>
        body {
            font-family: 'Arial, sans-serif';
            font-size: 16px;
            margin-top: 10px;
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
#hp  {
float: right;
 margin: -100px 0 0 5px;
}

    </style>
</head>
<body>
@foreach($data as $depo)
    <div>
        <div style="text-align: left;">
            <div class="deponame">
            <center><h1>{{ $depo['depoName'] }}</h1></center>
            <strong>Depo Date:   {{ \Carbon\Carbon::parse($depo['dueDate'])->isoFormat('Do MMMM YYYY, dddd') }}</strong><br>
            <strong>Po Number:   {{ $depo['poNumber'] }}</strong><br>

 @foreach($depo['orders'] as $order)
             @php
                    // Calculate trayod sums once per partnerRef
                   $groupedSums = DB::table('tblorder')
    ->join('tblproducts', 'tblorder.itemNumber', '=', 'tblproducts.sku')
    ->selectRaw('tblorder.dueDate,
                 SUM(CASE WHEN tblproducts.trayod = 36 THEN tblorder.requestQty ELSE 0 END) as sum36,
                 SUM(CASE WHEN tblproducts.trayod = 18 THEN tblorder.requestQty ELSE 0 END) as sum18')
    ->where('tblorder.partnerRef', $order->partnerRef)
    ->whereDate('tblorder.dueDate', $order->dueDate)
    ->groupBy('tblorder.dueDate')
    ->orderBy('tblorder.dueDate')
    ->get();
    $dollies = $groupedSums->sum('sum36') / 36 + $groupedSums->sum('sum18') / 18;
            @endphp
                @endforeach

@if ($groupedSums->isNotEmpty())
    <div style="margin-bottom:10px;">
        <strong>Total Qty (HalfTrays = 36):   {{ $groupedSums->sum('sum36') }}</strong><br>
        <strong>Total Qty (MetricsTrays = 18):   {{ $groupedSums->sum('sum18') }}</strong><br>
        <strong>Dollies:   {{ ceil($dollies) }} </strong>
@endif
 <div class="flex-container">
                <img src="data:image/png;base64,{!! $depo['barcode'] !!}" width="300" height="100" id="hp">
            </div>

    <br />

        <div class="content">
    <table>
        <tr>
           <th style="width: 10%">POSITION</th>
           <th style="width: 45%">PRODUCT</th>
           <th style="width: 5%">QTY</th>
           <th>NOTE</th>
           <th style="width: 5%">UPT</th>
           <th style="width: 15%">SLIFE</th>
        </tr>
        <tbody>

            @foreach($depo['orders'] as $order)
                <tr>
                    <td>{{ $order->positionsposId }}</td>
                    <td>
                        @php
                            $product = \App\Models\Tblproducts::where('sku', $order->itemNumber)->first();
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

@if (!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif

@endforeach
</body>
</html>
