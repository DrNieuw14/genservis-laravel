<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Water Bill Report</title>

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

        h1,h2,h3,h4{

            margin:3px;

        }

        .meta-line{

            margin:2px 0;

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

<h2 class="text-center">WATER BILL REPORT</h2>

@if($meter)
    <p class="meta-line"><strong>Meter:</strong> {{ $meter->label }} @if($meter->account_no) (Account No. {{ $meter->account_no }}) @endif</p>
@endif
@if($monthFrom || $monthTo)
    <p class="meta-line"><strong>Period:</strong> {{ $monthFrom ?: '...' }} to {{ $monthTo ?: '...' }}</p>
@endif
<p class="meta-line"><strong>Date Printed:</strong> {{ now()->format('M d, Y') }}</p>

<table style="margin-top:16px;">

    <thead>
        <tr>
            <th>Meter</th>
            <th>Month</th>
            <th class="text-center">Prev. Reading</th>
            <th class="text-center">Pres. Reading</th>
            <th class="text-center">Usage</th>
            <th class="text-center">Water Bill (₱)</th>
            <th class="text-center">ESF (₱)</th>
            <th class="text-center">Total Due (₱)</th>
            <th class="text-center">Due Date</th>
        </tr>
    </thead>

    <tbody>

        @forelse($bills as $bill)

            <tr>
                <td>{{ $bill->meter->label ?? '-' }}</td>
                <td>{{ $bill->monthLabel() }}</td>
                <td class="text-center">{{ $bill->previous_reading !== null ? number_format($bill->previous_reading, 2) : '-' }}</td>
                <td class="text-center">{{ $bill->present_reading !== null ? number_format($bill->present_reading, 2) : '-' }}</td>
                <td class="text-center">{{ $bill->usage() !== null ? number_format($bill->usage(), 2) : '-' }}</td>
                <td class="text-center">{{ $bill->water_bill !== null ? number_format($bill->water_bill, 2) : '-' }}</td>
                <td class="text-center">{{ $bill->esf !== null ? number_format($bill->esf, 2) : '-' }}</td>
                <td class="text-center">{{ $bill->totalDue() !== null ? number_format($bill->totalDue(), 2) : '-' }}</td>
                <td class="text-center">{{ $bill->due_date?->format('m/d/Y') ?? '-' }}</td>
            </tr>

        @empty

            <tr>
                <td colspan="9" class="text-center">No water bills recorded for these filters.</td>
            </tr>

        @endforelse

    </tbody>

    <tfoot>
        <tr>
            <th colspan="5" class="text-center">Total</th>
            <th class="text-center">{{ number_format($bills->sum('water_bill'), 2) }}</th>
            <th class="text-center">{{ number_format($bills->sum('esf'), 2) }}</th>
            <th class="text-center">{{ number_format($bills->sum(fn($b) => $b->totalDue() ?? 0), 2) }}</th>
            <th></th>
        </tr>
    </tfoot>

</table>

</body>

</html>
