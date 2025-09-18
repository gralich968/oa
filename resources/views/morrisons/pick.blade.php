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
        <input type="text" name="barcode" id="barcode" placeholder="Scan or enter barcode" autofocus required /><br /><br />
        <input type="number" name="quantity" id="quantity" placeholder="Enter quantity" min="1" required /><br />
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
        <input type="text" name="ponumber" id="ponumber" value="{{ old('PoNumber', session('PoNumber')) }}" readonly />

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const depoSelect = document.getElementById('depo');
            const poNumberInput = document.getElementById('ponumber');
            function updatePoNumber() {
            const selected = depoSelect.options[depoSelect.selectedIndex];
            poNumberInput.value = selected.getAttribute('data-ponumber') || '';
            }
            depoSelect.addEventListener('change', updatePoNumber);
            // Set on load if already selected
            updatePoNumber();
        });
        </script>
        <hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
        <label for="bbdate">BB Date:</label>
        <input type="date" name="bbdate" id="bbdate" required />
        <hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
        <button type="submit" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded">Close Pallet</button>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Restore username and depo from localStorage if available
        const usernameInput = document.getElementById('username');
        const depoSelect = document.getElementById('depo');
        const poNumberInput = document.getElementById('ponumber');
        if(localStorage.getItem('username')) {
            usernameInput.value = localStorage.getItem('username');
        }
        if(localStorage.getItem('depo')) {
            depoSelect.value = localStorage.getItem('depo');
        }

        // Save username and depo to localStorage on change
        usernameInput.addEventListener('change', function() {
            localStorage.setItem('username', usernameInput.value);
        });
        depoSelect.addEventListener('change', function() {
            localStorage.setItem('depo', depoSelect.value);
        });

        // On saveButton click, set hidden fields in the print form
        const saveButton = document.getElementById('saveButton');
        const printForm = saveButton.closest('form');
        if(printForm) {
            // Add hidden fields for username, depo, and ponumber if not present
            let hiddenUsername = printForm.querySelector('input[name="username"]');
            let hiddenDepo = printForm.querySelector('input[name="depo"]');
            let hiddenPoNumber = printForm.querySelector('input[name="ponumber"]');
            if(!hiddenUsername) {
                hiddenUsername = document.createElement('input');
                hiddenUsername.type = 'hidden';
                hiddenUsername.name = 'username';
                printForm.appendChild(hiddenUsername);
            }
            if(!hiddenDepo) {
                hiddenDepo = document.createElement('input');
                hiddenDepo.type = 'hidden';
                hiddenDepo.name = 'depo';
                printForm.appendChild(hiddenDepo);
            }
            if(!hiddenPoNumber) {
                hiddenPoNumber = document.createElement('input');
                hiddenPoNumber.type = 'hidden';
                hiddenPoNumber.name = 'ponumber';
                printForm.appendChild(hiddenPoNumber);
            }
            saveButton.addEventListener('click', function() {
                hiddenUsername.value = usernameInput.value;
                hiddenDepo.value = depoSelect.value;
                hiddenPoNumber.value = poNumberInput.value;
            });
        }
    });
    </script>


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
    <button type="submit" id="saveButton" class="btn btn-success">Send to Print</button>
</form>


<hr style="width: 50%; height: 4px; background-color: #86b300; border: none; margin: 20px auto;">
    </center>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('barcodes');

    textarea.addEventListener('input', function() {
        // Split on newlines OR spaces
        let lines = textarea.value
            //.split(/[\r\n\s]+/)           // split by newline or space
            .map(line => line.trim())     // trim spaces
            .filter(line => line !== ''); // remove empty entries

        let seen = new Set();
        let uniqueLines = [];

        lines.forEach(line => {
            if (!seen.has(line)) {
                seen.add(line);
                uniqueLines.push(line);
            } else {
                // Notify user about duplicate
                console.warn(`Duplicate barcode blocked: ${line}`);
                // Optional: alert user
                // alert(`Duplicate barcode detected: ${line}`);
            }
        });

        // Put only unique barcodes back into textarea, one per line
        textarea.value = uniqueLines.join("\n");
    });
});
</script>


</body>
</html>
