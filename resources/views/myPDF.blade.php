<!DOCTYPE html>
<html>
<head>
    <title>Laravel PDF Example</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrat.min.css" >
    <style>
        body {
            font-family: 'Arial, sans-serif';
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
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $title }}</h1>
            <p>Date: {{ $date }}</p>
        </div>
        <div class="content">
			<table class="table table-bordered">
			<tr>
			  <th>ID</th>
			  <th>L_Code</th>
			  <th>SKU</th>
			  <th>QTY</th>
			  <th>Created</th>
			</tr>	
			@foreach($users as $value)
            <tr>
              <td>{{ $value->id }}</td>
              <td>{{ $value->LCode }}</td>
              <td>{{ $value->sku }}</td>
              <td>{{ $value->qty }}</td>
              <td>{{ $value->created_at }}</td>              
            </tr>
            @endforeach
            </table>
        </div>
    </div>
</body>
</html>
