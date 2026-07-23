<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Final List of Admission — {{ $year->label }}
    </title>

    <script>

        window.onload = function () {

            window.print();

        }

    </script>

    <style>

        body{

            font-family: Arial, Helvetica, sans-serif;
            font-size:11px;
            color:#000;
            margin:30px;

        }

        table{

            width:100%;
            border-collapse:collapse;
            margin-bottom:20px;

        }

        th,
        td{

            border:1px solid #000;
            padding:5px;
            vertical-align:top;

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

        .program-title{

            font-weight:bold;
            font-size:13px;
            margin-top:18px;
            margin-bottom:4px;
            page-break-after:avoid;

        }

        thead{

            display:table-header-group;

        }

        tr{

            page-break-inside:avoid;

        }

        .signature-block{

            margin-top:50px;
            display:flex;
            justify-content:space-between;

        }

        .signature-col{

            width:45%;
            text-align:center;

        }

        .signature-name{

            font-weight:bold;
            border-top:1px solid #000;
            padding-top:4px;

        }

        @page{

            size:A4 portrait;
            margin:15mm;

        }

    </style>

</head>

<body>

<table class="header-table">

<tr>

<td width="90">
<img src="{{ asset('images/logo.png') }}" width="80">
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
<img src="{{ asset('images/bagong-pilipinas.png') }}" width="80">
</td>

</tr>

</table>

<hr>

<h2 class="text-center">FINAL LIST OF ADMISSION</h2>
<p class="text-center">{{ $year->label }}</p>

@if($groups->isEmpty())

    <p class="text-center">No students have been finalized yet.</p>

@endif

@foreach($groups as $group)

@php $totalFinalized = $group['direct']->count() + $group['viaReapplication']->count(); @endphp

<div class="program-group">

    <div class="program-title">
        {{ $group['code'] }} — {{ $group['program'] }}
        ({{ $totalFinalized }} finalized)
    </div>

    <table>
        <thead>
            <tr>
                <th width="6%">No.</th>
                <th width="14%">Control No.</th>
                <th width="40%">Name</th>
                <th width="30%">Address</th>
                <th width="10%">Grade</th>
            </tr>
        </thead>
        <tbody>
            @forelse($group['direct'] as $i => $entry)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $entry->applicant->control_number ?? '-' }}</td>
                <td>{{ $entry->applicant->fullName() ?? '-' }}</td>
                <td>{{ $entry->applicant->fullAddress() ?? '-' }}</td>
                <td class="text-center">{{ $entry->examGrade ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">None.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

@if($group['viaReapplication']->isNotEmpty())

<div class="program-group">

    <div class="program-title">
        {{ $group['code'] }} — {{ $group['program'] }} (via Reapplication)
        ({{ $group['viaReapplication']->count() }} finalized)
    </div>

    <table>
        <thead>
            <tr>
                <th width="6%">No.</th>
                <th width="14%">Control No.</th>
                <th width="40%">Name</th>
                <th width="30%">Address</th>
                <th width="10%">Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($group['viaReapplication'] as $i => $entry)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $entry->applicant->control_number ?? '-' }}</td>
                <td>{{ $entry->applicant->fullName() ?? '-' }}</td>
                <td>{{ $entry->applicant->fullAddress() ?? '-' }}</td>
                <td class="text-center">{{ $entry->examGrade ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endif

@endforeach

<div class="signature-block">

    <div class="signature-col">
        <div class="signature-name">&nbsp;</div>
        <div>Prepared by (Admission and Testing Services)</div>
    </div>

    <div class="signature-col">
        <div class="signature-name">&nbsp;</div>
        <div>Noted by</div>
    </div>

</div>

</body>

</html>
