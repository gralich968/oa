
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
td {
  font-size: 22px;
}
#hp  {
float: right;
 margin: -100px 0 0 5px;
}

    </style>
</head>
@php
use App\Models\MorrisonsTblprint;
use App\Models\Tblproducts;
@endphp
<body>

@foreach($data as $depo)
    @for ($i = 0; $i < 2; $i++)


    <br />

    <table>
        <tbody>
           <tr>
                <th colspan="2">
                    <strong style="font-size: 350%">{{ $depo['companyCode'] }} MONTANA BAKERY SLOUGH</strong>
                </th>
            </tr>
            <tr>
                <th colspan="2" style="font-size: 180%">
                    <i>{{ $depo['depoName'] }} ({{ $depo['partnerRef'] }})</i>
                </th>
            </tr>
            <tr>
                <th style="font-size: 180%">
                    INTO DEPOT DATE
                </th>
                <td style="font-size: 180%">
                    {{ \Carbon\Carbon::parse($depo['dueDate'])->addDays(1)->isoFormat('Do MMMM YYYY, dddd') }}
                </td>

            </tr>
            <tr>
                <th style="font-size: 180%">
                    DELIVERY METHOD
                </th>
                <td style="font-size: 180%">
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
                <th colspan="2" style="font-size: 180%">
                    <strong>SSCC</strong>
                </th>
                <td colspan="2" style="font-size: 180%">
                    (00){{ $depo['batch_no'] }}
                </td>
            </tr>
            <tr>
                <th style="font-size: 180%">
                    <strong>PRODUCT</strong>
                </th>
                <td style="font-size: 180%">
                    {{ MorrisonsTblprint::where('depo', $depo['partnerRef'])->groupBy(['batch_no', 'depo'])->count() }}
                </td>
                <th style="font-size: 180%">
                    <strong>TOTAL TRAYS</strong>
                </th>
                <td style="font-size: 180%">
                   {{ MorrisonsTblprint::where('depo', $depo['partnerRef'])->groupBy(['batch_no', 'depo'])->sum('quantity') }}
                </td>
            </tr>
        </tbody>
    </table>
    <br />

    <table>
        <thead>
            <tr>
                <th>GTIN</th>
                <th>PO</th>
                <th>UPT</th>
                <th>QTY</th>
                <th>DESCRIPTION</th>
            @foreach($depo['orders'] as $order)
                <tr>
                    <td>{{ $order->barcode }}</td>
                    <td>{{ $order->ponumber }}</td>
                    <td>{{ Tblproducts::where('sku', $order->barcode)->value('upt') }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>{{ Tblproducts::where('sku', $order->barcode)->value('description') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@if (!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif
@endfor
    @endforeach
</body>
</html>

