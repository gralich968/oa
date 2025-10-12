<!DOCTYPE html>
<html>
<head>
    <title>Scan Barcode</title>
   <!-- <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script> -->
    <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


   <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <style>
        form div {
  display: flex;
  flex-direction: column;
  margin-bottom: 10px;
  width: max-content;
}

form div label {
  font-size: 1rem;
  color: #333;
}

form div input {
  border: 1px solid #efefef;
  padding: 10px;
  margin-top: 5px
  
}
</style>
    <center><h2>MORRISONS STOCK</h2>
<hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
   
<div class="container">

    <form id="scan-stock-form" method="post" action="{{ route ('morrisons.stock') }}">
        @csrf
        <div class="mb-3">
            <label for="barcode" class="form-label" style="color: teal;font-weight: 800;">Scan Barcode:</label>
            <input type="text" name="barcode" id="barcode" placeholder="Scan or enter barcode" autofocus required />
        </div>
        <br />
        <div class="mb-3">
            <label for="bbdate" class="form-label" style="color: teal;font-weight: 800;">BB Date:</label>
            <input type="date" class="form-control" id="bbdate" name="bbdate" required/>
        </div>
        <br />
         <div class="mb-3">
            <label for="qty" class="form-label" style="color: teal;font-weight: 800;">Quantity:</label>
            <input type="number" class="form-control" id="qty" name="qty"  placeholder="Enter quantity" min="1" required />
        </div>
        <br />
        <button type="submit" class="flex items-center px-4 py-2 bg-green-600 text-green rounded">SAVE</button>
    </form>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif

   @foreach ($stocks as $stock)
   @php
   $sku = $stock->barcode;
   $description = DB::table('tblproducts')
        ->where('sku', $sku)
        ->value('description');
    $formattedBBDate = \Carbon\Carbon::parse($stock->bbdate)->format('d-m-Y');
@endphp
        <div class="mt-4">
            <h4>Stock Details</h4>
            <ul>
                <li><strong>Product:</strong> {{  $description }}</li>
                <li><strong>Quantity:</strong> {{ $stock->qty }}</li>
                <li><strong>BB Date:</strong> {{ $formattedBBDate }}</li>
            </ul>
        </div>
    @endforeach
</div>

