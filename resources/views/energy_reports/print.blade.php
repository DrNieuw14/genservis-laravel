<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Monthly Energy Conservation Report — {{ $report->monthLabel() }}
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

        h1,h2,h3,h4{

            margin:3px;

        }

        .section-title{

            font-weight:bold;
            margin-top:20px;
            margin-bottom:6px;

        }

        .meta-line{

            margin:2px 0;

        }

        .checklist li{

            margin:2px 0;

        }

        .signature-block{

            margin-top:50px;
            display:flex;
            justify-content:space-between;

        }

        .signature-col{

            width:45%;

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
    MONTHLY ENERGY CONSERVATION REPORT
</h2>

<p class="meta-line"><strong>Reporting Month:</strong> {{ $report->monthLabel() }}</p>
<p class="meta-line"><strong>Campus:</strong> {{ $report->campus }}</p>
<p class="meta-line"><strong>Energy Conservation Coordinator:</strong> {{ strtoupper(auth()->user()->fullname ?? auth()->user()->name) }}</p>
<p class="meta-line"><strong>Campus Administrator:</strong> {{ $report->reviewed_by_name ?: '_____________________' }}</p>

<div class="section-title">I. ENERGY CONSUMPTION MONITORING</div>

<table>
    <thead>
        <tr>
            <th>Particulars</th>
            <th>Previous Month</th>
            <th>Current Month</th>
            <th>Difference</th>
            <th>% Change</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Electricity Bill (₱)</td>
            <td class="text-center">{{ $report->previous_month_bill !== null ? number_format($report->previous_month_bill, 2) : '' }}</td>
            <td class="text-center">{{ $report->current_month_bill !== null ? number_format($report->current_month_bill, 2) : '' }}</td>
            <td class="text-center">{{ $report->billDifference() !== null ? number_format($report->billDifference(), 2) : '' }}</td>
            <td class="text-center">{{ $report->billPercentChange() !== null ? $report->billPercentChange() . '%' : '' }}</td>
        </tr>
        <tr>
            <td>Electricity Consumption (kWh)</td>
            <td class="text-center">{{ $report->previous_month_consumption !== null ? number_format($report->previous_month_consumption, 2) : '' }}</td>
            <td class="text-center">{{ $report->current_month_consumption !== null ? number_format($report->current_month_consumption, 2) : '' }}</td>
            <td class="text-center">{{ $report->consumptionDifference() !== null ? number_format($report->consumptionDifference(), 2) : '' }}</td>
            <td class="text-center">{{ $report->consumptionPercentChange() !== null ? $report->consumptionPercentChange() . '%' : '' }}</td>
        </tr>
    </tbody>
</table>

<p class="meta-line" style="margin-top:8px;"><strong>Remarks/Analysis:</strong> {{ $report->remarks_analysis ?: '-' }}</p>

<div class="section-title">II. ENERGY CONSERVATION MEASURES IMPLEMENTED</div>

<ul class="checklist" style="margin:0;padding-left:18px;">
    @foreach(\App\Models\EnergyConservationReport::MEASURES as $key => $label)
        <li>{{ in_array($key, $report->measures_implemented ?? []) ? '☑' : '☐' }} {{ $label }}</li>
    @endforeach
    <li>{{ $report->other_measures ? '☑' : '☐' }} Other Measures: {{ $report->other_measures ?: '__________' }}</li>
</ul>

<div class="section-title">III. ENERGY CONSERVATION ACTIVITIES CONDUCTED</div>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Activity</th>
            <th>Participants</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @forelse($report->activities as $activity)
            <tr>
                <td class="text-center">{{ $activity->activity_date->format('M d, Y') }}</td>
                <td>{{ $activity->activity }}</td>
                <td>{{ $activity->participants ?? '-' }}</td>
                <td>{{ $activity->remarks ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center">-</td></tr>
        @endforelse
    </tbody>
</table>

<div class="section-title">IV. ISSUES, CONCERNS, AND RECOMMENDATIONS</div>

<table>
    <thead>
        <tr>
            <th>Issue/Concern</th>
            <th>Action Taken</th>
            <th>Recommendation</th>
        </tr>
    </thead>
    <tbody>
        @forelse($report->issues as $issue)
            <tr>
                <td>{{ $issue->issue_concern }}</td>
                <td>{{ $issue->action_taken ?? '-' }}</td>
                <td>{{ $issue->recommendation ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="3" class="text-center">-</td></tr>
        @endforelse
    </tbody>
</table>

<div class="section-title">V. SUMMARY OF ACCOMPLISHMENTS</div>
<p class="meta-line">{{ $report->summary_of_accomplishments ?: '-' }}</p>

<div class="section-title">VI. ATTACHMENTS</div>
<p class="meta-line">
    ☑ Copy of Monthly Electric Bill: {{ $report->attachments->where('type', 'electric_bill')->isNotEmpty() ? 'Attached' : 'Not attached' }}<br>
    ☑ Photo Documentation: {{ $report->attachments->where('type', 'photo')->isNotEmpty() ? $report->attachments->where('type', 'photo')->count() . ' photo(s) attached' : 'None' }}<br>
    ☑ Other Supporting Documents: {{ $report->attachments->where('type', 'other')->isNotEmpty() ? $report->attachments->where('type', 'other')->count() . ' file(s) attached' : 'None' }}
</p>

<div class="signature-block">

    <div class="signature-col">
        <span class="signature-name">{{ strtoupper(auth()->user()->fullname ?? auth()->user()->name) }}</span><br>
        Energy Conservation Coordinator
    </div>

    <div class="signature-col">
        <span class="signature-name">{{ $report->reviewed_by_name ? strtoupper($report->reviewed_by_name) : '' }}</span><br>
        Campus Administrator
    </div>

</div>

@if($report->attachments->where('type', 'photo')->isNotEmpty())

    <div class="annex">

        <h2 class="text-center">ANNEX — PHOTO DOCUMENTATION</h2>
        <p class="text-center">{{ $report->monthLabel() }} — {{ $report->campus }}</p>

        <div class="photo-grid">

            @foreach($report->attachments->where('type', 'photo') as $photo)

                <div class="photo-card">
                    <img src="{{ $photo->url }}" alt="Photo documentation">
                    <div class="photo-caption">{{ $photo->created_at->format('M d, Y') }} — {{ $photo->uploader->fullname ?? $photo->uploader->name ?? '-' }}</div>
                </div>

            @endforeach

        </div>

    </div>

@endif

</body>

</html>
