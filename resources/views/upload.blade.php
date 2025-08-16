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
