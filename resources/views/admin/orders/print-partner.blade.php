<!DOCTYPE html>
<html>
<head>
    <title>PDF Print</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" >
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
#hp  {
float: right;
 margin: -100px 0 0 5px;
}

    </style>
</head>
<body>
    @foreach($data as $depo)
    <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: flex-start; width: 100%;">
        <div style="text-align: left;">
            <div class="deponame">
            <center><h1>{{ $depo['depoName'] }} - {{ $depo['dueDate'] }}</h1></center>
           <!-- <strong>Depo Date:   {{ \Carbon\Carbon::parse($depo['dueDate'])->isoFormat('Do MMMM YYYY, dddd') }}</strong><br>-->
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
        </div>
    </div>

    <br />

    <table>
        <thead>
            <tr>
               <th style="width: 10%">POSITION</th>
           <th style="width: 45%">PRODUCT</th>
           <th style="width: 5%">QTY</th>
           <th>NOTE</th>
           <th style="width: 5%">UPT</th>
           <th style="width: 15%">SLIFE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($depo['orders'] as $order)
                <tr>
                    <td>{{ $order->positionsposId }}</td>
                    <td>{{ $order->itemNumber }} - {{ optional($order->product)->description }}</td>
                    <td>{{ $order->requestQty }}</td>
                    <td>{{ $order->note }}</td>
                    <td>{{ $order->sparenumber1 }}</td>
                     @php
                        $bbd = \App\Models\tblproducts::select('slife')
                            ->where('sku', $order->itemNumber)
                            ->first();
                        $slifeDate = '';
                        if ($bbd && $bbd->slife) {
                            $slifeDate = \Carbon\Carbon::parse($order->dueDate)
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

@if (!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif

    @endforeach
</body>
</html>
