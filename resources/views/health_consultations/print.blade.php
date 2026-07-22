<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        {{ $consultation->case_no }} — Consultation Form
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

        .meta-line{

            margin:3px 0;

        }

        .field-label{

            font-size:9px;
            color:#555;

        }

        .checklist span{

            display:inline-block;
            margin-right:10px;

        }

        .soap-box{

            min-height:60px;

        }

        .signature-block{

            margin-top:40px;

        }

        .signature-name{

            font-weight:bold;
            border-top:1px solid #000;
            padding-top:4px;
            display:inline-block;
            min-width:250px;

        }

        .page-break{

            page-break-before:always;

        }

        @page{

            size:A4 portrait;
            margin:15mm;

        }

    </style>

</head>

<body>

<!-- ══════════════ PAGE 1 ══════════════ -->

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

<h2 class="text-center">CONSULTATION FORM</h2>
<p class="text-center meta-line">Case No.: <strong>{{ $consultation->case_no }}</strong></p>

<table style="margin-top:10px;">
<tr>
<td width="25%"><div class="field-label">Date</div>{{ $consultation->consultation_date->format('M d, Y') }}</td>
<td width="25%"><div class="field-label">Time In</div>{{ $consultation->time_in ?: '-' }}</td>
<td width="25%"><div class="field-label">Time Out</div>{{ $consultation->time_out ?: '-' }}</td>
<td width="25%"><div class="field-label">Case No.</div>{{ $consultation->case_no }}</td>
</tr>
</table>

<table style="margin-top:-1px;">
<tr>
<td width="40%"><div class="field-label">Name</div>{{ $consultation->patient_name }}</td>
<td width="15%"><div class="field-label">Age</div>{{ $consultation->patient_age ?? '-' }}</td>
<td width="15%"><div class="field-label">Sex</div>{{ $consultation->patient_sex ?: '-' }}</td>
<td width="30%"><div class="field-label">C.S.</div>{{ $consultation->patient_civil_status ?: '-' }}</td>
</tr>
<tr>
<td colspan="2"><div class="field-label">Address</div>{{ $consultation->patient_address ?: '-' }}</td>
<td colspan="2"><div class="field-label">Birthday</div>{{ $consultation->patient_birthday?->format('M d, Y') ?: '-' }}</td>
</tr>
<tr>
<td colspan="4"><div class="field-label">Chief Complaint</div>{{ $consultation->chief_complaint ?: '-' }}</td>
</tr>
</table>

<table style="margin-top:-1px;">
<tr>
<td width="50%">
    <div class="field-label">Contact person in case of emergency</div>
    Name: {{ $consultation->emergency_contact_name ?: '-' }}<br>
    Relationship: {{ $consultation->emergency_contact_relationship ?: '-' }}<br>
    Contact No.: {{ $consultation->emergency_contact_no ?: '-' }}
</td>
<td width="50%">
    <div class="field-label">Previous Consultation</div>
    Date: {{ $consultation->previous_consultation_date?->format('M d, Y') ?: '-' }}<br>
    Diagnosis: {{ $consultation->previous_diagnosis ?: '-' }}<br>
    Medications: {{ $consultation->previous_medications ?: '-' }}<br>
    Attending Physician: {{ $consultation->previous_attending_physician ?: '-' }}
</td>
</tr>
</table>

<table style="margin-top:-1px;">
<tr><th colspan="4">Assessment Section</th></tr>
<tr>
<td width="50%">
    <div class="field-label">Mode of Arrival</div>{{ $consultation->mode_of_arrival ?: '-' }}<br><br>

    <div class="field-label">Patient with injuries?</div>
    {{ $consultation->has_injuries ? 'Yes' : 'None' }}

    @if($consultation->has_injuries)
        <div class="checklist" style="margin-top:4px;">
            @foreach(\App\Models\HealthConsultation::INJURY_TYPES as $key => $label)
                <span>{{ in_array($key, $consultation->injury_types ?? []) ? '☑' : '☐' }} {{ $label }}</span>
            @endforeach
            @if($consultation->injury_other_text)
                <span>☑ Other: {{ $consultation->injury_other_text }}</span>
            @endif
        </div>
        <div style="margin-top:4px;">
            NOI: {{ $consultation->noi ?: '-' }} &nbsp; POI: {{ $consultation->poi ?: '-' }}<br>
            DOI: {{ $consultation->doi?->format('M d, Y') ?: '-' }} &nbsp; TOI: {{ $consultation->toi ?: '-' }}
        </div>
    @endif
</td>
<td width="50%">
    <div class="field-label">Vital Signs</div>
    BP: {{ $consultation->vital_bp ?: '-' }} &nbsp;
    Temp: {{ $consultation->vital_temp ?: '-' }} &nbsp;
    PR: {{ $consultation->vital_pr ?: '-' }} &nbsp;
    RR: {{ $consultation->vital_rr ?: '-' }}

    <div class="field-label" style="margin-top:8px;">Glasgow Coma Scale</div>
    Eye: {{ $consultation->gcs_eye ?? '-' }} &nbsp;
    Verbal: {{ $consultation->gcs_verbal ?? '-' }} &nbsp;
    Motor: {{ $consultation->gcs_motor ?? '-' }}<br>
    <strong>Total Score: {{ $consultation->gcsTotal() ?? '-' }}{{ $consultation->gcsTotal() ? ' / 15' : '' }}</strong>
</td>
</tr>
</table>

<table style="margin-top:-1px;">
<tr>
<td width="34%">
    <div class="field-label">Allergies</div>
    @foreach(\App\Models\HealthConsultation::ALLERGIES as $key => $label)
        {{ in_array($key, $consultation->allergies ?? []) ? '☑' : '☐' }} {{ $label }}<br>
    @endforeach
    @if($consultation->allergy_other_text) Others: {{ $consultation->allergy_other_text }} @endif
</td>
<td width="33%">
    <div class="field-label">Family History</div>
    @foreach(\App\Models\HealthConsultation::FAMILY_HISTORY as $key => $label)
        {{ in_array($key, $consultation->family_history ?? []) ? '☑' : '☐' }} {{ $label }}<br>
    @endforeach
    @if($consultation->family_history_other_text) Others: {{ $consultation->family_history_other_text }} @endif
</td>
<td width="33%">
    <div class="field-label">Medical History</div>
    @foreach(\App\Models\HealthConsultation::MEDICAL_HISTORY as $key => $label)
        {{ in_array($key, $consultation->medical_history ?? []) ? '☑' : '☐' }} {{ $label }}<br>
    @endforeach
    @if($consultation->medical_history_other_text) Others: {{ $consultation->medical_history_other_text }} @endif
</td>
</tr>
</table>

<!-- ══════════════ PAGE 2 (back page) ══════════════ -->

<div class="page-break"></div>

<h2 class="text-center">CONSULTATION FORM (continued)</h2>
<p class="text-center meta-line">Case No.: <strong>{{ $consultation->case_no }}</strong> — {{ $consultation->patient_name }}</p>

<table style="margin-top:10px;">
<tr><td><div class="field-label">Diagnosis</div>{{ $consultation->diagnosis ?: '-' }}</td></tr>
</table>

<table style="margin-top:-1px;">
<tr><th width="50%">Doctor's Order</th><th width="50%">Interventions</th></tr>
<tr>
<td class="soap-box">{{ $consultation->doctors_order ?: '-' }}</td>
<td class="soap-box">{{ $consultation->interventions ?: '-' }}</td>
</tr>
</table>

<table style="margin-top:-1px;">
<tr><td class="soap-box"><strong>S&gt;</strong> {{ $consultation->soap_subjective ?: '-' }}</td></tr>
<tr><td class="soap-box"><strong>O&gt;</strong> {{ $consultation->soap_objective ?: '-' }}</td></tr>
<tr><td class="soap-box"><strong>A&gt;</strong> {{ $consultation->soap_assessment ?: '-' }}</td></tr>
<tr><td class="soap-box"><strong>P&gt;</strong> {{ $consultation->soap_plan ?: '-' }}</td></tr>
</table>

@if($consultation->medicines->isNotEmpty())

<table style="margin-top:10px;">
<tr><th colspan="3">Medicines Dispensed</th></tr>
<tr><th width="50%">Medicine</th><th width="25%">Quantity</th><th width="25%">Notes</th></tr>
@foreach($consultation->medicines as $log)
<tr>
<td>{{ $log->medicine_name }}</td>
<td>{{ $log->quantity }} {{ $log->unit }}</td>
<td>{{ $log->notes ?: '-' }}</td>
</tr>
@endforeach
</table>

@endif

<div class="signature-block">
    <div class="signature-name">{{ strtoupper($consultation->attending_physician ?? '') }}</div>
    <div>Attending Physician</div>
</div>

</body>

</html>
