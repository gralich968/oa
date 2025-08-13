
<!DOCTYPE html>
<html>
 <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Import Pickings to Database</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
            font-size: 18px;
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

    </style>
 </head>
 <body>
  <br />

  <div class="container">
   <h3 align="center">Import Pickings to Database</h3>
    <br />
   @if(count($errors) > 0)
    <div class="alert alert-danger">
     Upload Validation Error<br><br>
     <ul>
      @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
     </ul>
    </div>
   @endif

   @if($message = Session::get('success'))
   <div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
           <strong>{{ $message }}</strong>
   </div>
   @endif
   <form method="post" enctype="multipart/form-data" action="{{ url('/import_pickings/import') }}">
    {{ csrf_field() }}
    <div class="form-group">
     <table class="table table-bordered">
      <tr>
       <td width="40%" align="right"><label>Select File for Upload</label></td>
       <td width="30">
        <input type="file" name="select_file" />
       </td>
       <td width="30%" align="left">
        <input type="submit" name="upload" class="btn btn-primary" value="Upload">
       </td>
      </tr>
      <tr>
       <td width="40%" align="right">File Type----></td>
       <td width="30"><span class="text-muted">.xls, .xslx, .ods</span></td>
       <td width="30%" align="left"></td>
      </tr>
     </table>
    </div>
   </form>

    <button type="button" class="btn btn-warning" onclick="window.location='{{ route('admin.tblpickings.index') }}'"> Go Back</button>
   <br />
   <br />
   <div class="panel panel-default">
    <div class="panel-heading">
     <h3 class="panel-title">Picking Visibility</h3>
    </div>
    <div class="panel-body">
     <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover">
       <tr>
        <th>No</th>
        <th>Position</th>
        <th>Product</th>
        <th>Quantity</th>
        <th>Picked</th>
        <th>SKU</th>
       </tr>
       @foreach($data as $row)
       <tr>
        <td scope="row">{{ $loop->iteration }}</td>
        <td scope="row">{{ $row->position }}</td>
        <td>{{ $row->product }}</td>
        <td>{{ $row->quantity }}</td>
        <td>{{ $row->picked }}</td>
        <td>{{ $row->sku }}</td>
       </tr>
       @endforeach
      </table>
     </div>
    </div>
   </div>
  </div>
 </body>
</html>

