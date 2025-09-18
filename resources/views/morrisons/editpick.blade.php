<div class="container">
    <h2>Edit Picked Item (Morrisons)</h2>

    {{-- Show validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Edit form --}}
    <form action="{{ route('morrisons.updatepick', $pick->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="barcode" class="form-label">Barcode</label>
            <input type="text" name="barcode" id="barcode" class="form-control"
                   value="{{ old('barcode', $pick->barcode) }}" required>
        </div>

        <div class="mb-3">
            <label for="depo" class="form-label">Depot</label>
            <select name="depo" id="depo" class="form-select" required>
                <option value="">Select a depot</option>
                @foreach($depos as $depo)
                    <option value="{{ $depo->depo_code }}"
                        {{ old('depo', $pick->depo) == $depo->depo_code ? 'selected' : '' }}>
                        {{ $depo->depo_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="brand" class="form-label">Brand</label>
            <input type="text" name="brand" id="brand" class="form-control"
                   value="{{ old('brand', $pick->brand) }}" readonly>
        </div>

        <div class="mb-3">
            <label for="duedate" class="form-label">Due Date</label>
            <input type="date" name="duedate" id="duedate" class="form-control"
                   value="{{ old('duedate', $pick->duedate ? $pick->duedate->format('Y-m-d') : '') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Pick</button>
        <a href="{{ route('morrisons.deletepick.show') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
