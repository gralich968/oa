<<<<<<< HEAD
<!DOCTYPE html>
<html>
<head>
    <title>Scan Barcode</title>
   <!-- <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script> -->
   <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <center><h1>MS PICK</h1>
<hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
@php use Carbon\Carbon; @endphp
    <div id="reader" style="width:300px;"></div>

    <form method="POST" action="/ms/pick">
        @csrf
         <input type="text" name="username" id="username" placeholder="Enter your username" required />
         <hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
        <textarea name="barcodes" id="barcodes" placeholder="Scan or paste multiple barcodes, one per line" rows="3" cols="30" autofocus required></textarea>
        <hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
        <!-- Hidden unique ID -->
        <input type="hidden" readonly="readonly" name="un" value="<?php echo uniqid(); ?>" />
        <input type="date" name="duedate" id="duedate" required value="{{ \Carbon\Carbon::now()->addDay()->toDateString() }}" />

        <select name="depo" id="depo" required>
            <option value="">Select a depo</option>
            @foreach($depos as $depo)
                <option value="{{ $depo->depo_code }}">{{ $depo->depo_name }}</option>
            @endforeach
        </select>

         <hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
        <button type="submit" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded">Close</button>
    </form>


   <hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">

<table class="table table-hover" width="50%" border="2" style = "border-collapse: collapse; background:#fff;">
    <tr style = "background-color:#86b300;">
	<td style = "text-align: center"> ID</td>
    <td style = "text-align: center"> PRODUCT</td>
	<td style = "text-align: center"> BB DATE</td>
    <td style = "text-align: center"> DEPO</td>
    <td style = "text-align: center"> B. No</td>
	</tr>

  @foreach ($mstblpicked as $index => $row)
  @php
        $sku = substr($row->barcode, 0, 8);
        $description = DB::table('tblproducts')
        ->where('sku', $sku)
        ->value('description');
        $depo = DB::table('tbldestinations')
        ->where('depo_code', $row->depo)
        ->value('depo_name');
    @endphp
      <tr>
		<td><center><b>{{ $index + 1 }}</b></td>
        <td><center><b>{{ $description }}</b></center></td>

@php
    $rawDate = substr($row->barcode, 16, 6); // e.g., "250919"
    $formattedDate = \Carbon\Carbon::createFromFormat('ymd', $rawDate)->format('d-m-Y');
@endphp

<td><center><b>{{ $formattedDate }}</b></center></td>

        <td><center><b>{{ $depo }}</b></center></td>
        <td><center><b>{{ substr($row->barcode, 22, 5) }}</b></center></td>
        <!-- Split barcode into three parts -->
        <!-- Remove leading zeros from each part -->
        <!-- First 5 characters, next 5 characters, last 3 characters -->
        <!-- Assuming barcode length is at least 13 characters -->
        <!-- Adjust substr parameters if barcode length varies -->
        <!-- Use ltrim to remove leading zeros -->
        <!-- Use trim to remove any extra spaces -->
        <!-- Display each part in bold and centered -->
        <!-- Example: If barcode is '000123450006700', it will display as '12345 00067 700' -->
        <!-- If barcode is shorter than expected, it will display as is -->
		<!--<td><center><b>{{ substr(ltrim(trim($row->barcode), '0'), 0, 5) }}</b></center></td>
        <td><center><b>{{ substr(ltrim(trim($row->barcode), '0'), 5, 5) }}</b></center></td>
        <td><center><b>{{ ltrim(substr($row->barcode, -3), '0') }}</b></center></td>-->
        </tr>
	@endforeach
    </table>
<br />
<hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
    <form method="POST" action="{{ url('/ms/picksave') }}">
    @csrf
    <button type="submit" id="saveButton" class="btn btn-success">Send to Print</button>
</form>
<hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
    </center>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('barcodes');

    textarea.addEventListener('input', function() {
        // Split on newlines OR spaces
        let lines = textarea.value
            //.split(/[\r\n\s]+/)           // split by newline or space
            .map(line => line.trim())     // trim spaces
            .filter(line => line !== ''); // remove empty entries

        let seen = new Set();
        let uniqueLines = [];

        lines.forEach(line => {
            if (!seen.has(line)) {
                seen.add(line);
                uniqueLines.push(line);
            } else {
                // Notify user about duplicate
                console.warn(`Duplicate barcode blocked: ${line}`);
                // Optional: alert user
                // alert(`Duplicate barcode detected: ${line}`);
            }
        });

        // Put only unique barcodes back into textarea, one per line
        textarea.value = uniqueLines.join("\n");
    });
});
</script>


</body>
</html>
=======
Dziala Droga!!!
>>>>>>> 74e963bcc8f2bf9698d0bed58c9d3b0b21d67cbe
