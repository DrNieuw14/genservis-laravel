<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        DTR — {{ $personnel->fullname }} — {{ $monthLabel }}
    </title>

    <script>

        window.onload = function () {

            window.print();

        }

    </script>

    <style>

        /*
         * Sized/spaced to fit a full 31-day month on one A4 page — the
         * day-by-day table (up to 33 rows incl. headers) is the dominant
         * cost, so its rows and every other block are kept as compact as
         * legibly possible rather than using the more generous spacing a
         * shorter form could afford.
         */

        body{

            font-family: Arial, Helvetica, sans-serif;
            font-size:10px;
            color:#000;
            margin:8px;
            line-height:1.15;

        }

        table{

            width:100%;
            border-collapse:collapse;

        }

        th,
        td{

            border:1px solid #000;
            padding:2px 4px;

        }

        th{

            background:#e5e7eb;
            font-weight:bold;

        }

        .text-center{

            text-align:center;

        }

        .text-right{

            text-align:right;

        }

        .header-table td{

            border:none;
            font-size:10px;

        }

        .header-table h2{

            margin:2px;
            font-size:15px;

        }

        h1,h2,h3,h4{

            margin:2px;

        }

        .dtr-box{

            border:1px solid #000;
            padding:6px 10px;
            margin-top:6px;

        }

        .dtr-box h2{

            font-size:15px;

        }

        .form-no{

            text-align:left;
            font-size:9px;
            margin:0 0 3px;

        }

        .dtr-name{

            text-align:center;
            font-size:12px;
            font-weight:bold;
            margin-top:6px;
            border-bottom:1px solid #000;
            display:inline-block;
            padding:0 10px 1px;
            min-width:300px;

        }

        .dtr-name-wrap{

            text-align:center;

        }

        .dtr-name-caption{

            text-align:center;
            font-size:8px;
            margin-top:1px;

        }

        .meta-line{

            margin:6px 0 0;
            font-size:10px;

        }

        .meta-line .blank{

            display:inline-block;
            border-bottom:1px solid #000;
            min-width:220px;
            text-align:center;

        }

        .official-hours{

            margin-top:4px;
            width:100%;

        }

        .official-hours td{

            border:none;
            padding:1px 0;
            vertical-align:top;
            font-size:9px;

        }

        .official-hours .blank{

            display:inline-block;
            border-bottom:1px solid #000;
            min-width:140px;
            text-align:center;

        }

        .dtr-table{

            margin-top:4px;

        }

        .dtr-table th,
        .dtr-table td{

            text-align:center;
            padding:1px 4px;
            font-size:9px;

        }

        .weekend{

            background:#f3f4f6;

        }

        .certification{

            margin-top:6px;
            font-size:9px;

        }

        .signature-block{

            margin-top:16px;

        }

        .signature-line{

            width:240px;
            border-top:1px solid #000;
            text-align:center;
            padding-top:2px;
            font-weight:bold;
            font-size:10px;

        }

        .verified-block{

            margin-top:12px;
            font-size:9px;

        }

        .legend{

            margin-top:6px;
            font-size:8.5px;
            color:#333;

        }

        @page{

            size:A4 portrait;
            margin:10mm;

        }

    </style>

</head>

<body>

<table class="header-table">

<tr>

<td width="60">

<img
src="{{ asset('images/logo.png') }}"
width="55">

</td>

<td class="text-center">

<div>Republic of the Philippines</div>

<h2>CAVITE STATE UNIVERSITY</h2>

<div>CvSU Carmona Campus</div>

<div>Carmona, Cavite</div>

<div>(046) 487-6328</div>

<div><a href="https://www.cvsu.edu.ph" style="color:#000;text-decoration:none;">www.cvsu.edu.ph</a></div>

</td>

<td width="60">

<img
src="{{ asset('images/bagong-pilipinas.png') }}"
width="55">

</td>

</tr>

</table>

<hr style="margin:4px 0;">

<div class="dtr-box">

<p class="form-no">CIVIL SERVICE FORM No. 48</p>

<h2 class="text-center">DAILY TIME RECORD</h2>

<div class="dtr-name-wrap">
    <div class="dtr-name">{{ strtoupper($personnel->surnameFirstName()) }}</div>
    <div class="dtr-name-caption">(Name)</div>
</div>

<div style="margin-top:8px;">
    <div class="text-center" style="font-weight:bold;border-bottom:1px solid #000;padding-bottom:1px;font-size:11px;">
        {{ $monthRangeLabel }}
    </div>
    <div style="font-style:italic;font-size:9px;margin-top:1px;">
        for the month of
    </div>
</div>

<table class="official-hours" style="margin-top:6px;">
    <tr>
        <td width="55%">
            <div style="font-style:italic;font-size:9px;">Official hours for arrival and departure</div>
            <div style="margin-top:6px;border-bottom:1px solid #000;">&nbsp;{{ $officialHours ?? '' }}</div>
        </td>
        <td width="45%">
            <div style="font-style:italic;font-size:9px;">Regular days</div>
            <div style="margin-top:2px;border-bottom:1px solid #000;">&nbsp;</div>
            <div style="font-style:italic;font-size:9px;margin-top:3px;">Saturdays</div>
            <div style="margin-top:2px;border-bottom:1px solid #000;">&nbsp;</div>
        </td>
    </tr>
</table>

<table class="dtr-table">

    <thead>
        <tr>
            <th rowspan="2" style="width:8%;">Day</th>
            <th colspan="2">A.M.</th>
            <th colspan="2">P.M.</th>
            <th colspan="2">Undertime</th>
        </tr>
        <tr>
            <th>Arrival</th>
            <th>Departure</th>
            <th>Arrival</th>
            <th>Departure</th>
            <th>Hours</th>
            <th>Minutes</th>
        </tr>
    </thead>

    <tbody>

    @foreach($days as $day)

        @php
            $entry = $day['entry'];
            $undertime = $day['undertimeMinutes'];
        @endphp

        <tr class="{{ $day['date']->isWeekend() ? 'weekend' : '' }}">
            <td>{{ $day['date']->format('j') }}</td>

            @if($day['rowLabel'])
                <td colspan="6" style="text-align:left;font-weight:bold;">{{ $day['rowLabel'] }}</td>
            @else
                <td>{{ $entry?->time_in?->format('g:i A') ?? '' }}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{ $entry?->time_out?->format('g:i A') ?? '' }}</td>
                <td>{{ $undertime !== null && $undertime > 0 ? intdiv($undertime, 60) : '' }}</td>
                <td>{{ $undertime !== null && $undertime > 0 ? $undertime % 60 : '' }}</td>
            @endif
        </tr>

    @endforeach

    <tr>
        <td colspan="5">&nbsp;</td>
        <td><strong>{{ number_format(intdiv($totalUndertimeMinutes, 60), 2) }}</strong></td>
        <td><strong>{{ number_format($totalUndertimeMinutes % 60, 2) }}</strong></td>
    </tr>

    </tbody>

</table>

<table style="margin-top:0;border-top:none;">
    <tr>
        <td style="border-top:none;border-left:none;border-right:none;text-align:right;padding:2px 4px;font-size:9px;">
            Total &nbsp; <strong>{{ number_format($totalUndertimeMinutes / 60, 2) }}</strong>
        </td>
    </tr>
</table>

</div>

<p class="certification">
    I certify on my honor that the above is a true and correct report of the hours of work performed, record of which was made daily at the time of arrival and departure.
</p>

<div class="signature-block">
    <div class="signature-line">{{ strtoupper($personnel->surnameFirstName()) }}</div>
</div>

<div class="verified-block">
    Verified as to the prescribed office hours.

    <div class="signature-block">
        <div class="signature-line">
            @if($submission->markChecker)
                {{ strtoupper($submission->markChecker->fullname ?? $submission->markChecker->name) }}
            @endif
        </div>
    </div>
</div>

<div class="verified-block">
    Approved.

    <div class="signature-block">
        <div class="signature-line">
            @if($submission->hrApprover)
                {{ strtoupper($submission->hrApprover->fullname ?? $submission->hrApprover->name) }}
            @endif
        </div>
        <div style="text-align:left;">Campus Administrator</div>
    </div>
</div>

</body>

</html>
