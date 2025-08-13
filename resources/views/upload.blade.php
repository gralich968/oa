
<form action="{{ route('pdf.merge') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label>Select PDF files:</label>
    <input type="file" name="pdfs[]" multiple accept="application/pdf">
    <button type="submit">Merge PDFs</button>
</form
