<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Job Request Form
    </title>

    <script>

        window.onload = function () {

            window.print();

        }

    </script>

    <style>

        body{

            font-family: Arial, Helvetica, sans-serif;
            font-size:13px;
            color:#000;
            margin:30px;

        }

        table{

            width:100%;
            border-collapse:collapse;

        }

        .form-table td{

            border:1px solid #000;
            padding:8px;
            vertical-align:top;

        }

        .label{

            font-weight:bold;
            width:180px;
            background:#f3f4f6;

        }

        .header-table td{

            border:none;

        }

        .text-center{

            text-align:center;

        }

        h1,h2,h3,h4{

            margin:3px;

        }

        .form-code{

            text-align:right;
            font-size:11px;
            font-weight:bold;

        }

        .signature-table td{

            border:none;
            width:50%;
            text-align:center;
            vertical-align:bottom;

        }

        .signature-name{

            height:16px;

        }

        .signature-line{

            border-top:1px solid #000;
            margin-top:8px;
            padding-top:4px;

        }

        .annex{

            page-break-before:always;

        }

        .photo-grid{

            display:flex;
            flex-wrap:wrap;
            gap:16px;
            margin-top:10px;

        }

        .photo-card{

            width:47%;
            text-align:center;
            margin-bottom:16px;
            page-break-inside:avoid;

        }

        .photo-card img{

            width:100%;
            max-height:420px;
            object-fit:contain;
            border:1px solid #000;
            background:#f5f5f5;

        }

        .photo-caption{

            font-size:12px;
            font-weight:bold;
            margin-top:5px;

        }

        .photo-uploader{

            font-size:11px;
            color:#555;

        }

        @page{

            size:A4 portrait;
            margin:15mm;

        }

    </style>

</head>

<body>

<div class="form-code">
    PPLS-QF-02
</div>

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
    JOB REQUEST FORM
</h2>

<table class="form-table" style="margin-top:15px;">

<tr>
    <td class="label">Reference No.</td>
    <td>{{ $jobRequest->reference_no }}</td>
    <td class="label">Date</td>
    <td>{{ $jobRequest->created_at->format('d F Y') }}</td>
</tr>

<tr>
    <td class="label">Requesting Party</td>
    <td colspan="3">{{ $jobRequest->requesting_party }}</td>
</tr>

<tr>
    <td class="label">Office/Unit/Project</td>
    <td colspan="3">{{ $jobRequest->office_unit_project }}</td>
</tr>

<tr>
    <td class="label">Nature of Request</td>
    <td colspan="3">{{ $jobRequest->nature_of_request }}</td>
</tr>

<tr>
    <td class="label">Work Summary</td>
    <td colspan="3">{{ $jobRequest->work_summary }}</td>
</tr>

<tr>
    <td class="label">Work Category</td>
    <td>{{ $jobRequest->work_category ?? '-' }}</td>
    <td class="label">Target Date</td>
    <td>{{ $jobRequest->target_date?->format('d F Y') ?? '-' }}</td>
</tr>

<tr>
    <td class="label">Assigned Personnel</td>
    <td colspan="3">
        @forelse($jobRequest->assignedPersonnel as $index => $person)
            {{ $index + 1 }}. {{ strtoupper($person->fullname) }}@if(!$loop->last)<br>@endif
        @empty
            -
        @endforelse
    </td>
</tr>

<tr>
    <td class="label">Remarks</td>
    <td colspan="3">{{ $jobRequest->remarks ?? '-' }}</td>
</tr>

</table>

<table class="signature-table" style="margin-top:60px;">

<tr>

<td>
    <div class="signature-name"><strong>{{ strtoupper($jobRequest->approver->fullname ?? $jobRequest->approver->name ?? '') }}</strong></div>
    <div class="signature-line">
        Approved By — {{ $jobRequest->approverRoleLabel() }}
    </div>
</td>

<td>
    <div class="signature-name">&nbsp;</div>
    <div class="signature-line">
        Noted By — Requesting Party
    </div>
</td>

</tr>

</table>

@if($jobRequest->photos->isNotEmpty())

    <div class="annex">

        <h2 class="text-center">ANNEX — PHOTO EVIDENCE OF WORK</h2>
        <p class="text-center">{{ $jobRequest->reference_no }} — {{ $jobRequest->nature_of_request }}</p>

        <div class="photo-grid">

            @foreach($jobRequest->photos as $photo)

                <div class="photo-card">
                    <img src="{{ $photo->url }}" alt="Evidence photo">
                    <div class="photo-caption">{{ $photo->created_at->format('M d, Y g:i A') }}</div>
                    <div class="photo-uploader">{{ $photo->uploader->fullname ?? $photo->uploader->name ?? '-' }}</div>
                </div>

            @endforeach

        </div>

    </div>

@endif

</body>

</html>
