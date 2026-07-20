<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        {{ $estimate->reference_no }} — Project Detailed Estimate
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

        .section{

            margin-top:20px;

        }

        .section-title{

            font-weight:bold;
            margin-top:20px;
            margin-bottom:6px;

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

<div>Carmona Campus</div>

<div>Carmona City, Cavite</div>

</td>

<td width="90"></td>

</tr>

</table>

<hr>

<h2 class="text-center">
    PROJECT DETAILED ESTIMATE
</h2>

<p class="meta-line"><strong>Reference No.:</strong> {{ $estimate->reference_no }}</p>
<p class="meta-line"><strong>Project Name:</strong> {{ $estimate->project_name }}</p>
@if($estimate->location)
    <p class="meta-line"><strong>Location:</strong> {{ $estimate->location }}</p>
@endif
<p class="meta-line"><strong>Prepared By:</strong> {{ $estimate->preparer->fullname ?? $estimate->preparer->name }}</p>

<div class="section-title">1. Project Overview</div>

<table>
    <tr>
        <td width="150"><strong>Scope of Work</strong></td>
        <td>{{ $estimate->scope_of_work ?: '-' }}</td>
    </tr>
    <tr>
        <td><strong>Duration</strong></td>
        <td>{{ $estimate->duration ?: '-' }}</td>
    </tr>
    <tr>
        <td><strong>Assumptions</strong></td>
        <td>{{ $estimate->assumptions ?: '-' }}</td>
    </tr>
    <tr>
        <td><strong>Exclusions</strong></td>
        <td>{{ $estimate->exclusions ?: '-' }}</td>
    </tr>
</table>

<div class="section-title">2. Materials / Equipment Cost Estimate</div>

<table>

<thead>
<tr>
    <th>Item No.</th>
    <th>Description</th>
    <th>Unit</th>
    <th>Quantity</th>
    <th>Unit Cost (₱)</th>
    <th>Total Cost (₱)</th>
</tr>
</thead>

<tbody>

@forelse($estimate->items as $index => $item)
    <tr>
        <td class="text-center">{{ $index + 1 }}</td>
        <td>{{ $item->description }}</td>
        <td class="text-center">{{ $item->unit ?? '-' }}</td>
        <td class="text-center">{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}</td>
        <td class="text-right">{{ number_format($item->unit_cost, 2) }}</td>
        <td class="text-right">{{ number_format($item->totalCost(), 2) }}</td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center">No items on this estimate.</td>
    </tr>
@endforelse

<tr>
    <td colspan="5" class="text-right"><strong>Subtotal</strong></td>
    <td class="text-right"><strong>₱{{ number_format($estimate->grandTotal(), 2) }}</strong></td>
</tr>

</tbody>

</table>

<div class="section-title">3. Summary of Estimated Costs</div>

<table>
<thead>
<tr>
    <th>Category</th>
    <th>Amount (₱)</th>
</tr>
</thead>
<tbody>
<tr>
    <td>Materials / Equipment</td>
    <td class="text-right">{{ number_format($estimate->materialsTotal(), 2) }}</td>
</tr>
<tr>
    <td>Labor</td>
    <td class="text-right">{{ number_format($estimate->laborTotal(), 2) }}</td>
</tr>
<tr>
    <td><strong>Total Project Estimate</strong></td>
    <td class="text-right"><strong>₱{{ number_format($estimate->grandTotal(), 2) }}</strong></td>
</tr>
</tbody>
</table>

<div class="signature-block">
    Prepared:<br><br>
    <span class="signature-name">
        {{ strtoupper($estimate->preparer->fullname ?? $estimate->preparer->name) }}
    </span><br>
    {{ optional($estimate->preparer->systemRole)->name }}
</div>

@if($estimate->photos->isNotEmpty())

    <div class="annex">

        <h2 class="text-center">ANNEX — PHOTO DOCUMENTATION</h2>
        <p class="text-center">{{ $estimate->reference_no }} — {{ $estimate->project_name }}</p>

        @foreach(['receipt' => 'Receipts', 'work_done' => 'Work Done', 'other' => 'Other'] as $type => $groupLabel)

            @php $groupPhotos = $estimate->photos->where('type', $type); @endphp

            @if($groupPhotos->isNotEmpty())

                <div class="section-title">{{ $groupLabel }}</div>

                <div class="photo-grid">

                    @foreach($groupPhotos as $photo)

                        <div class="photo-card">
                            <img src="{{ $photo->url }}" alt="{{ $groupLabel }}">
                            <div class="photo-caption">{{ $photo->created_at->format('M d, Y') }}</div>
                            <div class="photo-uploader">{{ $photo->uploader->fullname ?? $photo->uploader->name ?? '-' }}</div>
                        </div>

                    @endforeach

                </div>

            @endif

        @endforeach

    </div>

@endif

</body>

</html>
