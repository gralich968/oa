<!DOCTYPE html>
<html>
<head>
    <title>Scan Barcode</title>
   <!-- <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script> -->
   <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <center><h1>Stock OUT</h1>
<hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <div id="reader" style="width:300px;"></div>

    <form method="POST" action="/stock/scanout">
        @csrf
         <input type="text" name="username" id="username" placeholder="Enter your username" required />
         <hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
        <textarea name="barcodes" id="barcodes" placeholder="Scan or paste multiple barcodes, one per line" rows="3" cols="30" autofocus required></textarea>
        <hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
        <!-- Hidden unique ID -->
        <input type="hidden" readonly="readonly" name="un" value="<?php echo uniqid(); ?>" />
        <button type="submit" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded">Save Barcode</button>
    </form>

   <hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">

<table class="table table-hover" width="50%" border="2" style = "border-collapse: collapse; background:#fff;">
    <tr style = "background-color:#86b300;">
	<td style = "text-align: center"> ID</td>
	<td style = "text-align: center"> BARCODE</td>
    <td style = "text-align: center"> LCODE</td>
    <td style = "text-align: center"> CASES</td>
	</tr>
  @foreach ($tblout as $row)
      <tr>
		<td><center><b>{{ $row->id }}</b></td>
		<td><center><b>{{ substr(ltrim(trim($row->barcode), '0'), 0, 5) }}</b></center></td>
        <td><center><b>{{ substr(ltrim(trim($row->barcode), '0'), 5, 5) }}</b></center></td>
        <td><center><b>{{ ltrim(substr($row->barcode, -3), '0') }}</b></center></td>
        </tr>
	@endforeach
    </table>
<br />
<hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
    <form method="POST" action="{{ url('/stock/scanoutsave') }}">
    @csrf
    <button type="submit" id="saveButton" class="btn btn-success">Send to Customer</button>
</form>
<hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
    </center>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById('barcodes').value = decodedText;
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
    <script>
if (/Mobi|Android/i.test(navigator.userAgent)) {
    // Mobile-specific behavior
    document.body.style.backgroundColor = "#e0f7fa";
    // You can also redirect or show/hide elements
}
</script>
</body>
</html>
