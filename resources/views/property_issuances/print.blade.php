<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        {{ $issuance->slip_no }} — {{ $issuance->isIcs() ? 'Inventory Custodian Slip' : 'Property Acknowledgement Receipt' }}
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

        .meta-table td{

            border:none;
            padding:2px 4px;

        }

        .signature-block{

            margin-top:50px;
            display:flex;
            justify-content:space-between;

        }

        .signature-col{

            width:45%;

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

            font-size:11px;
            color:#555;

        }

        .signature-name{

            font-weight:bold;
            border-top:1px solid #000;
            padding-top:4px;
            display:inline-block;
            min-width:280px;

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

@if($issuance->isIcs())

    <h2 class="text-center">INVENTORY CUSTODIAN SLIP</h2>
    <p class="text-center" style="margin-bottom:10px;"><strong>{{ $issuance->icsBracketLabel() }}</strong></p>

    <table class="meta-table" style="margin-bottom:10px;">
        <tr>
            <td width="50%"><strong>Entity Name:</strong> CAVITE STATE UNIVERSITY</td>
            <td width="50%"><strong>P.O. No.:</strong> {{ $issuance->po_number ?: '' }}</td>
        </tr>
        <tr>
            <td><strong>Fund Cluster:</strong> {{ $issuance->fund_cluster }}</td>
            <td><strong>ICS No.:</strong> {{ $issuance->slip_no }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Qty</th>
                <th>Unit</th>
                <th>Unit Cost (₱)</th>
                <th>Total Cost (₱)</th>
                <th>Description</th>
                <th>Date Acquired</th>
                <th>Inventory Item No.</th>
                <th>Estimated Useful Life</th>
            </tr>
        </thead>
        <tbody>
            @foreach($issuance->items as $item)
                <tr>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-center">{{ $item->unit ?: '-' }}</td>
                    <td class="text-center">{{ $item->unit_cost !== null ? number_format($item->unit_cost, 2) : '-' }}</td>
                    <td class="text-center">{{ number_format($item->totalCost(), 2) }}</td>
                    <td>{{ $item->property_name }}</td>
                    <td class="text-center">{{ $item->date_acquired?->format('m-d-y') ?? '-' }}</td>
                    <td class="text-center">{{ $item->property_number ?: '-' }}</td>
                    <td class="text-center">{{ $item->estimated_useful_life ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-block">

        <div class="signature-col">
            &nbsp;&nbsp;Received from:<br><br><br>
            <span class="signature-name">{{ strtoupper($issuance->issuerDisplayName()) }}</span><br>
            Signature Over Printed Name<br>
            {{ $issuance->issuerDisplayPosition() }}<br>
            Position/Office<br><br>
            ______________________________<br>
            Date
        </div>

        <div class="signature-col">
            Received by<br><br><br>
            <span class="signature-name">{{ strtoupper($issuance->recipient->fullname ?? '') }}</span><br>
            Signature Over Printed Name<br>
            {{ $issuance->recipient->positionRecord->position_name ?? '-' }}<br>
            Position/Office<br><br>
            ______________________________<br>
            Date
        </div>

    </div>

@else

    <h2 class="text-center">PROPERTY ACKNOWLEDGEMENT RECEIPT</h2>

    <table class="meta-table" style="margin-bottom:10px;margin-top:10px;">
        <tr>
            <td width="50%"><strong>Entity Name:</strong> CAVITE STATE UNIVERSITY</td>
            <td width="50%"><strong>PAR No.:</strong> {{ $issuance->slip_no }}</td>
        </tr>
        <tr>
            <td><strong>Fund Cluster:</strong> {{ $issuance->fund_cluster }}</td>
            <td></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Qty.</th>
                <th>Unit</th>
                <th>Description</th>
                <th>Property Number</th>
                <th>Date Acquired</th>
                <th>Unit Cost (₱)</th>
                <th>Total Amount (₱)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($issuance->items as $item)
                <tr>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-center">{{ $item->unit ?: '-' }}</td>
                    <td>{{ $item->property_name }}</td>
                    <td class="text-center">{{ $item->property_number ?: '-' }}</td>
                    <td class="text-center">{{ $item->date_acquired?->format('m-d-y') ?? '-' }}</td>
                    <td class="text-center">{{ $item->unit_cost !== null ? number_format($item->unit_cost, 2) : '-' }}</td>
                    <td class="text-center">{{ number_format($item->totalCost(), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-block">

        <div class="signature-col">
            &nbsp;&nbsp;Received by:<br><br><br>
            <span class="signature-name">{{ strtoupper($issuance->recipient->fullname ?? '') }}</span><br>
            Signature Over Printed Name<br>
            {{ $issuance->recipient->positionRecord->position_name ?? '-' }}<br>
            Position/Office<br><br>
            ______________________________<br>
            Date
        </div>

        <div class="signature-col">
            &nbsp;&nbsp;Issued by:<br><br><br>
            <span class="signature-name">{{ strtoupper($issuance->issuerDisplayName()) }}</span><br>
            Signature over Printed Name<br>
            {{ $issuance->issuerDisplayPosition() }}<br>
            Position/Office<br><br>
            ______________________________<br>
            Date
        </div>

    </div>

@endif

@if($issuance->remarks)
    <p style="margin-top:20px;"><strong>Remarks:</strong> {{ $issuance->remarks }}</p>
@endif

@if($issuance->photos->isNotEmpty())

    <div class="annex">

        <h2 class="text-center">ANNEX — EVIDENCE PHOTOS</h2>
        <p class="text-center">{{ $issuance->slip_no }}</p>

        @foreach($issuance->photos->groupBy(fn($p) => $p->item->property_name ?? 'General') as $groupLabel => $groupPhotos)

            <h3 style="margin-top:16px;">{{ $groupLabel }}</h3>

            <div class="photo-grid">

                @foreach($groupPhotos as $photo)

                    <div class="photo-card">
                        <img src="{{ $photo->url }}" alt="Evidence photo">
                        <div class="photo-caption">{{ $photo->created_at->format('M d, Y') }} — {{ $photo->uploader->fullname ?? $photo->uploader->name ?? '-' }}</div>
                    </div>

                @endforeach

            </div>

        @endforeach

    </div>

@endif

</body>

</html>
