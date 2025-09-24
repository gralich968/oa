<!DOCTYPE html>
<html>
<head>
    <title>Laravel PDF Example</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrat.min.css" >
    <style>
        body {
            font-family: 'Arial, sans-serif';
            font-size: 16px;
            margin-top: 10px;
            margin-bottom: 25px;
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
    <div class="container">
        <div class="header">
            <h1>{{ $title }}</h1>
            <p>Create at: {{ $date }} {{ $time }}</p>
        </div>
        <div class="content">
			<table>
            <thead>
			<tr>
			  <th>ID</th>
			  <th>L_Code</th>
			  <th>SKU</th>
			  <th>QTY</th>
			  <th>Created</th>
			</tr>
            </thead>
			@foreach($users as $index =>$value)
            <tr>
              <td>{{ $index + 1 }}</td>
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
