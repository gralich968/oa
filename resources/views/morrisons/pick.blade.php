<!DOCTYPE html>
<html>
<head>
    <title>Scan Barcode</title>
   <!-- <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script> -->
   <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <center><h1>MORRISONS PICK</h1>
<hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
@php use Carbon\Carbon; @endphp
    <div id="reader" style="width:300px;"></div>

    <form method="POST" action="/morrisons/pick">
        @csrf
        <input type="text" name="username" id="username" placeholder="Enter your username" required value="{{ old('username', session('username')) }}" />
        <hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
        <!-- Hidden unique ID -->
        <input type="hidden" readonly="readonly" name="un" value="<?php echo uniqid(); ?>" />
        <input type="date" name="duedate" id="duedate" required value="{{ \Carbon\Carbon::now()->addDay()->toDateString() }}" />
        <select name="depo" id="depo" required>
            <option value="">Select a depo</option>
            @foreach($depos as $depo)
            @php
                // Get poNumber for this depo
                $orderNumber = \DB::table('morrisons_tblorders')
                ->where('partnerRef', $depo->depo_code)
                ->value('orderNumber');
            @endphp
            <option
                value="{{ $depo->depo_code }}"
                data-ponumber="{{ $orderNumber ?? '' }}"
                {{ old('depo', session('depo')) == $depo->depo_code ? 'selected' : '' }}>
                {{ $depo->depo_name }}
            </option>
            @endforeach
        </select>
        <input type="number" name="ponumber" id="ponumber" value="{{ old('ponumber', session('ponumber')) }}" readonly required/>
        <hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
        <label for="barcodes">Scan or Enter Barcode:</label><br />
        <input type="text" name="barcode" id="barcode" placeholder="Scan or enter barcode" autofocus required /><br /><br />
        <input type="number" name="quantity" id="quantity" placeholder="Enter quantity" min="1" required /><br /><br />
        <hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
        <label for="bbdate">BB Date:</label>
        <input type="date" name="bbdate" id="bbdate" required />
        <hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
        <button type="submit" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded">DONE</button>
        <script>
document.addEventListener('DOMContentLoaded', function() {
    const depoSelect = document.getElementById('depo');
    const ponumberInput = document.getElementById('ponumber');
    const closePalletBtn = document.getElementById('closePallet');

    // Restore ponumber if available
    if (sessionStorage.getItem('ponumber')) {
        ponumberInput.value = sessionStorage.getItem('ponumber');
    }

    function updatePoNumber() {
        const selected = depoSelect.options[depoSelect.selectedIndex];
        ponumberInput.value = selected.getAttribute('data-ponumber') || '';
        sessionStorage.setItem('ponumber', ponumberInput.value);
    }

    depoSelect.addEventListener('change', updatePoNumber);

    // Save manually entered ponumber
    ponumberInput.addEventListener('input', function() {
        sessionStorage.setItem('ponumber', ponumberInput.value);
    });

    // Clear ponumber when closing pallet
    if (closePalletBtn) {
        closePalletBtn.addEventListener('click', function() {
            sessionStorage.removeItem('ponumber');
            ponumberInput.value = '';
        });
    }
});
</script>
    </form>

   <hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">

<table class="table table-hover" width="50%" border="2" style = "border-collapse: collapse; background:#fff;">
    <tr style = "background-color:#86b300;">
	<td style = "text-align: center"> ID</td>
    <td style = "text-align: center"> PRODUCT</td>
	<td style = "text-align: center"> DUE DATE</td>
    <td style = "text-align: center"> DEPO</td>
    <td style = "text-align: center"> BB DATE</td>
    <td style = "text-align: center"> QTY</td>
    <td style = "text-align: center"> ORG QTY</td>
    <td style = "text-align: center"> REMAINING</td>
    <td style = "text-align: center"> ACTION</td>
	</tr>

  @foreach ($MorrisonsTblpicked as $index => $row)
  @php
        $sku = $row->barcode;
        $duedate = \Carbon\Carbon::parse($row->duedate)->format('d-m-Y');
        $duedate1 = \Carbon\Carbon::parse($row->duedate)->format('Y-m-d');
        $description = DB::table('tblproducts')
        ->where('sku', $sku)
        ->value('description');
        $depo = DB::table('tbldestinations')
        ->where('depo_code', $row->depo)
        ->value('depo_name');
        $orgqty = DB::table('morrisons_tblorders')
            ->where('itemNumber', $row->barcode)
            ->whereDate('dueDate', $duedate1)
            ->where('partnerRef', $row->depo)
            ->value('requestQty');
    @endphp
      <tr>
		<td><center><b>{{ $index + 1 }}</b></td>
        <td><center><b>{{ $description }}</b></center></td>

@php
    $duedate = $row->duedate; // e.g., "250919"
    $bbdate = $row->bbdate; // e.g., "250919"
    $formattedDate = \Carbon\Carbon::parse($duedate)->format('d-m-Y');
    $formattedBBDate = \Carbon\Carbon::parse($bbdate)->format('d-m-Y');
@endphp

        <td><center><b>{{ $formattedDate }}</b></center></td>
        <td><center><b>{{ $depo }}</b></center></td>
        <td><center><b>{{ $formattedBBDate }}</b></center></td>
        <td><center><b>{{ $row->quantity }}</b></center></td>
        <td><center><b>{{ $orgqty }}</b></center></td>
        <td>
            <center>
            <b>
            @php
            $remaining = $orgqty - $row->quantity;
            @endphp
            {!! $remaining < 0
            ? '<span style="color: red;">OVER PICK</span>'
            : ($remaining == 0 ? '0' : $remaining) !!}
            </b>
            </center>
        </td>
        <td>
            <form method="POST" action="/morrisons/deletepick/{{ $row->id }}" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                @csrf
                {{-- @method('DELETE') --}}
                <button type="submit" class="btn btn-danger">Delete</button>
               <!-- <button type="button" class="btn btn-secondary" onclick="window.location.href='/morrisons/editpick/{{ $row->id }}'">Edit</button>-->
            </form>
        </tr>
	@endforeach
    </table>
<br />
<hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">

    <form method="POST" action="{{ url('/morrisons/picksave') }}">
    @csrf
    <button type="submit" id="closePallet" class="btn btn-success">Close Pallet</button>
</form>


<hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
    </center>
   <script>
document.addEventListener('DOMContentLoaded', function () {
    const usernameInput = document.getElementById('username');
    const depoSelect = document.getElementById('depo');
    const dueDateInput = document.getElementById('duedate');
    const ponumberInput = document.getElementById('ponumber'); // Make sure this input exists

    // Restore from localStorage
    if (localStorage.getItem('username')) {
        usernameInput.value = localStorage.getItem('username');
    }
    if (localStorage.getItem('depo')) {
        depoSelect.value = localStorage.getItem('depo');
    }
    if (localStorage.getItem('duedate')) {
        dueDateInput.value = localStorage.getItem('duedate');
    }
    if (localStorage.getItem('ponumber')) {
        ponumberInput.value = localStorage.getItem('ponumber');
    }

    // Save to localStorage on change
    usernameInput.addEventListener('change', () => localStorage.setItem('username', usernameInput.value));
    depoSelect.addEventListener('change', () => localStorage.setItem('depo', depoSelect.value));
    dueDateInput.addEventListener('change', () => localStorage.setItem('duedate', dueDateInput.value));
    ponumberInput.addEventListener('change', () => localStorage.setItem('ponumber', ponumberInput.value));

    // Handle "Send to Print" button
    const printButton = document.getElementById('closePallet'); // Your print button's ID
    const printForm = printButton?.closest('form');

    if (printForm) {
        ['username', 'depo', 'duedate', 'ponumber'].forEach(field => {
            let input = printForm.querySelector(`input[name="${field}"]`);
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = field;
                printForm.appendChild(input);
            }

            printButton.addEventListener('click', () => {
                input.value = document.getElementById(field)?.value || '';
            });
        });
    }
});
</script>


</body>
</html>
