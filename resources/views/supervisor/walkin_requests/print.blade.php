<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Walk-In Issuance Slip
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

        .header-table td{

            border:none;

        }

        .info-table td{

            border:none;
            padding:4px 6px;

        }

        h1,h2,h3,h4{

            margin:3px;

        }

        .section{

            margin-top:25px;

        }

        .signature td{

            border:none;
            padding-top:60px;

        }

        @page{

            size:A4 portrait;
            margin:12mm;

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

WALK-IN ISSUANCE SLIP

</h2>

<p class="text-center">

Reference No. {{ $request->reference_no }}

</p>

<table class="section info-table">

<tr>

<td><strong>Date Issued</strong></td>

<td>{{ \Carbon\Carbon::parse($request->issued_at)->format('F d, Y h:i A') }}</td>

<td><strong>Source</strong></td>

<td>Centralized Stockroom</td>

</tr>

<tr>

<td><strong>Requested By</strong></td>

<td>{{ $request->requestor_name }}</td>

<td><strong>Destination</strong></td>

<td>{{ $request->department->department_name ?? '-' }}</td>

</tr>

<tr>

<td><strong>Issued By</strong></td>

<td>{{ $request->issuer->username ?? '-' }}</td>

<td><strong>Room</strong></td>

<td>{{ $request->room }}</td>

</tr>

<tr>

<td><strong>Purpose</strong></td>

<td colspan="3">{{ $request->purpose }}</td>

</tr>

</table>

<h3 class="section">

Issued Materials

</h3>

<table>

<thead>

<tr>

<th width="40">#</th>

<th>Material</th>

<th>Unit</th>

<th>Quantity</th>

<th>Stock Before</th>

<th>Stock After</th>

</tr>

</thead>

<tbody>

@forelse($request->items as $item)

<tr>

<td class="text-center">{{ $loop->iteration }}</td>

<td>{{ $item->material->name ?? '-' }}</td>

<td class="text-center">{{ $item->unit }}</td>

<td class="text-center">{{ $item->quantity }}</td>

<td class="text-center">{{ $item->stock_before }}</td>

<td class="text-center">{{ $item->stock_after }}</td>

</tr>

@empty

<tr>

<td colspan="6" class="text-center">No materials recorded for this issuance.</td>

</tr>

@endforelse

</tbody>

</table>

<table class="signature">

<tr>

<td class="text-center">

_________________________

<br>

Issued By

</td>

<td class="text-center">

_________________________

<br>

Received By

</td>

</tr>

</table>

</body>

</html>
