<!DOCTYPE html>
<html>
<head>
    <title>Laravel PDF Example</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrat.min.css" >
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
<div class="card">
  <div class="card-header">Merge PDF Files</div>
  <div class="card-body">
    <form method="POST" action="{{ route('pdf.merge') }}" enctype="multipart/form-data">
      @csrf

      <div class="form-group">
        <label for="pdfs">Select PDF files (at least 2)</label>
        <input
          type="file"
          class="form-control"
          id="pdfs"
          name="pdfs[]"
          accept="application/pdf"
          multiple
        >
        @error('pdfs.*')
          <span class="text-danger">{{ $message }}</span>
        @enderror
      </div>
    <br />
      <button type="submit" class="btn btn-primary">Merge & Download</button>
      <a href="{{ route('admin.tblpickings.index') }}" class="btn btn-secondary">Back</a>
    </form>
  </div>
</div>
</body>
</html>
