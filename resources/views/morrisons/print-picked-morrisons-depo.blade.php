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
            font-size: 24px;
        }
        table {
  border-collapse: collapse;
  width: 100%;
}

table, th, td {
  border: 2px solid black;
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
@php
use App\Models\MorrisonsTblprint;
@endphp
<body>
    @foreach($data as $depo)

    <br />

    <table>
        <tbody>
           <tr>
                <th colspan="2">
                    <strong style="font-size: 350%">{{ $depo['companyCode'] }} MONTANA BAKERY SLOUGH</strong>
                </th>
            </tr>
            <tr>
                <th colspan="2" style="font-size: 150%">
                    <i>{{ $depo['depoName'] }} ({{ $depo['partnerRef'] }})</i>
                </th>
            </tr>
            <tr>
                <th>
                    INTO DEPOT DATE
                </th>
                <td>
                    {{ \Carbon\Carbon::parse($depo['dueDate'])->isoFormat('Do MMMM YYYY, dddd') }}
                </td>

            </tr>
            <tr>
                <td>
                    <strong>DELIVERY METHOD</strong>
                </td>
                <td>
                    Chilled (-5° to 0°)
                </td>
            </tr>
</tbody>
    </table>
    <br />
    <center><img src="data:image/png;base64,{!! $depo['barcode'] !!}" width="800" height="300"></center>
    <br />
    <br />

     <table>
        <tbody>
            <tr>
                <td>
                    <strong>SSCC</strong>
                </td>
                <td colspan="3">
                    (00)050385430000180267
                </td>
            </tr>
            <tr>
                <td>
                    <strong>PRODUCT</strong>
                </td>
                <td>
                    {{ MorrisonsTblprint::where('depo', $depo['partnerRef'])->groupBy(['batch_no', 'depo'])->count() }}
                </td>
                <td>
                    <strong>TOTAL TRAYS</strong>
                </td>
                <td>
                   {{ MorrisonsTblprint::where('depo', $depo['partnerRef'])->groupBy(['batch_no', 'depo'])->sum('quantity') }}
                </td>
            </tr>
        </tbody>
    </table>
    <br />
    <table>
        <thead>
            <tr>
                <th>ORDER ID</th>
                <th>PRODUCT</th>
                <th>QTY</th>
                <th>NOTE</th>
                <th>SPARE NUMBER 1</th>
            @foreach($depo['orders'] as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->barcode }} - {{ optional($order->product)->description }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>{{ $order->note }}</td>
                    <td>{{ $order->sparenumber1 }}</td>
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
