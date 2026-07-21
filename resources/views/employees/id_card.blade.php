<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        {{ $employee->fullname }} — ID Card
    </title>

    <script>

        window.onload = function () {

            window.print();

        }

    </script>

    <style>

        body{

            font-family: Arial, Helvetica, sans-serif;
            color:#000;
            margin:0;
            display:flex;
            justify-content:center;
            align-items:flex-start;
            padding-top:40px;

        }

        .card{

            width:340px;
            border:2px solid #14532d;
            border-radius:12px;
            padding:24px;
            text-align:center;

        }

        .card h3{

            margin:2px 0;
            font-size:13px;

        }

        .card h2{

            margin:2px 0;
            font-size:15px;
            color:#14532d;

        }

        .letterhead{

            display:flex;
            align-items:center;
            gap:8px;

        }

        .letterhead img{

            width:44px;
            height:44px;
            flex-shrink:0;

        }

        .letterhead .letterhead-text{

            flex:1;

        }

        .letterhead .letterhead-text p{

            margin:1px 0;
            font-size:10px;

        }

        .divider{

            border-top:1px solid #ccc;
            margin:14px 0;

        }

        .name{

            font-size:18px;
            font-weight:bold;
            margin:10px 0 2px;

        }

        .employee-id{

            font-size:13px;
            color:#555;
            margin-bottom:14px;

        }

        .field{

            text-align:left;
            font-size:12px;
            margin-bottom:8px;

        }

        .field-label{

            color:#777;

        }

        .field-value{

            font-weight:bold;

        }

        #idCardQr{

            margin-top:14px;

        }

        @page{

            size:A4 portrait;
            margin:15mm;

        }

    </style>

</head>

<body>

<div class="card">

    <div class="letterhead">

        <img src="{{ asset('images/logo.png') }}" alt="CvSU Logo">

        <div class="letterhead-text">
            <p>Republic of the Philippines</p>
            <h2 style="margin:1px 0;font-size:13px;">CAVITE STATE UNIVERSITY</h2>
            <p>CvSU Carmona Campus</p>
            <p>Carmona, Cavite</p>
        </div>

        <img src="{{ asset('images/bagong-pilipinas.png') }}" alt="Bagong Pilipinas">

    </div>

    <div class="divider"></div>

    <div class="name">{{ $employee->fullname }}</div>
    <div class="employee-id">{{ $employee->employee_id }}</div>

    <div class="field">
        <div class="field-label">Department</div>
        <div class="field-value">{{ $employee->departmentRecord?->department_name ?? '-' }}</div>
    </div>

    <div class="field">
        <div class="field-label">Position</div>
        <div class="field-value">{{ $employee->positionRecord?->position_name ?? '-' }}</div>
    </div>

    <div class="field">
        <div class="field-label">Employment Status</div>
        <div class="field-value">{{ $employee->employmentType?->name ?? '-' }}</div>
    </div>

    <img id="idCardQr" src="{{ $qrDataUri }}" alt="QR Code" width="160" height="160">

</div>

</body>

</html>
