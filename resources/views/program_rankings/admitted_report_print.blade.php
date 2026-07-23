<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Admitted Students Report — {{ $year->label }}
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

<h2 class="text-center">LIST OF ADMITTED STUDENTS</h2>
<p class="text-center">{{ $year->label }}</p>

@foreach($groups as $group)

<div class="program-group">

    <div class="program-title">
        {{ $group['code'] }} — {{ $group['program'] }}
        ({{ $group['admitted']->count() }} admitted, quota {{ $group['quota'] }})
    </div>

    <table>
        <thead>
            <tr>
                <th width="8%">Rank</th>
                <th width="12%">Code</th>
                <th width="40%">Name</th>
                <th width="30%">Address</th>
                <th width="10%">Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($group['admitted'] as $i => $r)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $r->code ?? '-' }}</td>
                <td>{{ $r->examinee_name }}</td>
                <td>{{ $r->address ?? '-' }}</td>
                <td class="text-center">{{ $r->grade }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endforeach

</body>

</html>
