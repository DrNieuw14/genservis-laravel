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

            padding:8px;
            vertical-align:top;

        }

        .label{

            font-weight:bold;
            width:180px;
            background:#f3f4f6;

        }

        .header-table{

            width:auto;
            margin:0 auto;

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

        .form-footer{

            position:fixed;
            bottom:10mm;
            right:15mm;
            font-size:10px;

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

<td class="text-center" style="font-size:11px;">

<div>Republic of the Philippines</div>

<h2 style="font-size:14px;">CAVITE STATE UNIVERSITY</h2>

<div>CvSU Carmona Campus</div>

<div>Carmona, Cavite</div>

<div>(046) 487-6328</div>

<div><a href="https://www.cvsu.edu.ph" style="color:#000;text-decoration:none;">www.cvsu.edu.ph</a></div>

</td>

</tr>

</table>

<hr>

<h2 class="text-center">
    JOB REQUEST FORM
</h2>

<table class="form-table" style="margin-top:15px;">

<tr>
    <td class="label">Date</td>
    <td colspan="3">{{ $jobRequest->created_at->format('d F Y') }}</td>
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

</table>

<div style="text-align:right;margin:10px 0 20px;">
    <p style="font-style:italic;margin:0 0 30px;">Approved by:</p>
    <div style="display:inline-block;text-align:center;min-width:220px;">
        <div style="border-top:1px solid #000;padding-top:4px;">
            {{ strtoupper($jobRequest->approver->fullname ?? $jobRequest->approver->name ?? '') }}
        </div>
        <div style="font-size:11px;">Campus PPS</div>
    </div>
</div>

<table class="form-table">

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

<div style="text-align:right;margin:60px 0 20px;">
    <p style="font-style:italic;margin:0 0 30px;">Noted by:</p>
    <div style="display:inline-block;text-align:center;min-width:220px;">
        <div style="border-top:1px solid #000;padding-top:4px;">
            &nbsp;
        </div>
        <div style="font-size:11px;">Requesting Party</div>
    </div>
</div>

@if($jobRequest->photos->isNotEmpty())

    <div class="annex">

        <h2 class="text-center">ANNEX — PHOTO EVIDENCE</h2>
        <p class="text-center">{{ $jobRequest->reference_no }} — {{ $jobRequest->nature_of_request }}</p>

        @foreach(\App\Models\JobRequestPhoto::TYPES as $type => $label)

            @php $typePhotos = $jobRequest->photos->where('type', $type); @endphp

            @if($typePhotos->isNotEmpty())

                <h3 style="margin-top:16px;">{{ $label }}</h3>

                <div class="photo-grid">

                    @foreach($typePhotos as $photo)

                        <div class="photo-card">
                            <img src="{{ $photo->url }}" alt="Evidence photo">
                            <div class="photo-caption">{{ $photo->created_at->format('M d, Y g:i A') }}</div>
                            <div class="photo-uploader">{{ $photo->uploader->fullname ?? $photo->uploader->name ?? '-' }}</div>
                        </div>

                    @endforeach

                </div>

            @endif

        @endforeach

    </div>

@endif

<div class="form-footer">
    V01-2018-06-05
</div>

</body>

</html>
