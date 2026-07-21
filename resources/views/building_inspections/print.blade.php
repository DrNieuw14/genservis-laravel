<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        {{ $inspection->reference_no }} — Building Inspection Checklist
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

        }

        th,
        td{

            border:1px solid #000;
            padding:6px;
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

        .form-code{

            text-align:right;
            font-size:10px;
            font-weight:bold;

        }

        .meta-line{

            margin:4px 0;

        }

        .obs-item{

            margin-bottom:2px;

        }

        .obs-flagged{

            font-weight:bold;

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

        @page{

            size:A4 portrait;
            margin:15mm;

        }

    </style>

</head>

<body>

<div class="form-code">PPLS-QF-03</div>

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
    BUILDING INSPECTION CHECKLIST
</h2>

<p class="meta-line"><strong>Reference No.:</strong> {{ $inspection->reference_no }}</p>
<p class="meta-line"><strong>Building Name:</strong> {{ $inspection->building_name }}</p>
<p class="meta-line"><strong>Building In-Charge:</strong> {{ $inspection->building_in_charge ?? '-' }}</p>
<p class="meta-line"><strong>Date of Inspection:</strong> {{ $inspection->inspection_date->format('F d, Y') }}</p>

<table class="section" style="margin-top:15px;">

<thead>
<tr>
    <th width="18%">Item</th>
    <th width="52%">Observations</th>
    <th width="30%">Remarks</th>
</tr>
</thead>

<tbody>

@foreach($inspection->items as $item)

<tr>
<td><strong>{{ $item->categoryLabel() }}</strong></td>
<td>
    @foreach($item->categoryItems() as $observation)
        <div class="obs-item {{ $item->isFlagged($observation['text']) ? 'obs-flagged' : '' }}">
            {{ $item->isFlagged($observation['text']) ? '☑' : '☐' }} {{ $observation['text'] }}
        </div>
    @endforeach
    @if($item->other_observations)
        <div class="obs-item obs-flagged">☑ Others: {{ $item->other_observations }}</div>
    @endif
</td>
<td>{{ $item->remarks ?: '-' }}</td>
</tr>

@endforeach

</tbody>

</table>

<div class="signature-block">

    <div class="signature-col">
        <div class="signature-name">{{ strtoupper($inspection->inspector->fullname ?? $inspection->inspector->name) }}</div>
        <div>Inspected by (PPS Staff)</div>
    </div>

    <div class="signature-col">
        <div class="signature-name">{{ strtoupper($inspection->noted_by ?? '') }}</div>
        <div>Noted by (PPS Director)</div>
    </div>

</div>

@php $hasPhotos = $inspection->items->contains(fn($i) => $i->photos->isNotEmpty()); @endphp

@if($hasPhotos)

    <div class="annex">

        <h2 class="text-center">ANNEX — PHOTO DOCUMENTATION</h2>
        <p class="text-center">{{ $inspection->reference_no }} — {{ $inspection->building_name }}</p>

        @foreach($inspection->items as $item)

            @if($item->photos->isNotEmpty())

                <div class="section-title" style="font-weight:bold;margin-top:20px;">{{ $item->categoryLabel() }}</div>

                <div class="photo-grid">

                    @foreach($item->photos as $photo)

                        <div class="photo-card">
                            <img src="{{ $photo->url }}" alt="{{ $item->categoryLabel() }}">
                            <div class="photo-caption">{{ $photo->created_at->format('M d, Y') }}</div>
                        </div>

                    @endforeach

                </div>

            @endif

        @endforeach

    </div>

@endif

</body>

</html>
