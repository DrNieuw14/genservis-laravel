<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        {{ $room->room_name }} — Room Inventory of Property
    </title>

    <script>

        window.onload = function () {

            window.print();

        }

    </script>

    <style>

        body{

            font-family: Arial, Helvetica, sans-serif;
            font-size:12px;
            color:#000;
            margin:30px;

        }

        table{

            width:100%;
            border-collapse:collapse;

        }

        th,
        td{

            border:1px solid #000;
            padding:6px;

        }

        th{

            background:#e5e7eb;

        }

        .text-center{

            text-align:center;

        }

        .text-right{

            text-align:right;

        }

        .header-table td{

            border:none;

        }

        h1,h2,h3,h4{

            margin:3px;

        }

        .meta-line{

            margin:2px 0;

        }

        .signature-block{

            margin-top:60px;

        }

        .signature-name{

            font-weight:bold;
            border-top:1px solid #000;
            padding-top:4px;
            display:inline-block;
            min-width:280px;

        }

        @page{

            size:A4 landscape;
            margin:15mm;

        }

    </style>

</head>

<body>

<table class="header-table">

<tr>

<td width="90">

<img
src="{{ asset('images/logo.png') }}"
width="80">

</td>

<td class="text-center">

<div>Republic of the Philippines</div>

<h2>CAVITE STATE UNIVERSITY</h2>

<div>CvSU Carmona Campus</div>

<div>Carmona, Cavite</div>

<div>(046) 487-6328</div>

<div><a href="https://www.cvsu.edu.ph" style="color:#000;text-decoration:none;">www.cvsu.edu.ph</a></div>

</td>

<td width="90">

<img
src="{{ asset('images/bagong-pilipinas.png') }}"
width="80">

</td>

</tr>

</table>

<hr>

<h2 class="text-center">
    ROOM INVENTORY OF PROPERTY
</h2>

<p class="meta-line"><strong>Room:</strong> {{ $room->room_name }}</p>
@if($room->room_type)
    <p class="meta-line"><strong>Room Type:</strong> {{ $room->room_type }}</p>
@endif
@if($room->building)
    <p class="meta-line"><strong>Building:</strong> {{ $room->building }}</p>
@endif
@if($room->department)
    <p class="meta-line"><strong>Department:</strong> {{ $room->department->department_name }}</p>
@endif
<p class="meta-line"><strong>Date Printed:</strong> {{ now()->format('M d, Y') }}</p>

<table style="margin-top:16px;">

    <thead>
        <tr>
            <th>Property Name</th>
            <th>Property No.</th>
            <th>Description</th>
            <th class="text-center">Qty</th>
            <th class="text-center">Unit Value (₱)</th>
            <th class="text-center">Total (₱)</th>
            <th class="text-center">Date Acquired</th>
            <th class="text-center">Condition</th>
            <th>Remarks</th>
        </tr>
    </thead>

    <tbody>

        @forelse($room->propertyItems as $item)

            <tr>
                <td>{{ $item->property_name }}</td>
                <td>{{ $item->property_number ?? '-' }}</td>
                <td>{{ $item->description ?? '-' }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-center">{{ $item->unit_value !== null ? number_format($item->unit_value, 2) : '-' }}</td>
                <td class="text-center">{{ number_format($item->totalValue(), 2) }}</td>
                <td class="text-center">{{ $item->date_acquired?->format('M d, Y') ?? '-' }}</td>
                <td class="text-center">{{ $item->condition }}</td>
                <td>{{ $item->remarks ?? '-' }}</td>
            </tr>

        @empty

            <tr>
                <td colspan="9" class="text-center">No property items recorded for this room.</td>
            </tr>

        @endforelse

    </tbody>

    <tfoot>
        <tr>
            <th colspan="5" class="text-right">Total Value</th>
            <th class="text-center">{{ number_format($room->totalValue(), 2) }}</th>
            <th colspan="3"></th>
        </tr>
    </tfoot>

</table>

<div class="signature-block">
    Prepared by:<br><br>
    <span class="signature-name">
        {{ strtoupper(auth()->user()->fullname ?? auth()->user()->name) }}
    </span><br>
    Property Custodian
</div>

</body>

</html>
