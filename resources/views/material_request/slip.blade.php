<!DOCTYPE html>
<html>
<head>

    <title>Material Request Slip</title>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>

    <style>

        body{
            font-family: Arial, sans-serif;
            padding:40px;
            color:#222;
        }

        .header{
            text-align:center;
            margin-bottom:30px;
        }

        .title{
            font-size:28px;
            font-weight:bold;
        }

        .subtitle{
            color:#555;
            margin-top:5px;
        }

        .section{
            margin-top:25px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:15px;
        }

        table th,
        table td{
            border:1px solid #ccc;
            padding:10px;
            text-align:left;
        }

        table th{
            background:#f5f5f5;
        }

        .status{
            display:inline-block;
            padding:6px 12px;
            border-radius:20px;
            color:white;
            font-size:14px;
        }

        .approved{
            background:#22c55e;
        }

        .pending{
            background:#facc15;
            color:black;
        }

        .rejected{
            background:#ef4444;
        }

        .signature{
            margin-top:80px;
            display:flex;
            justify-content:space-between;
        }

        .line{
            border-top:1px solid #000;
            width:250px;
            text-align:center;
            padding-top:5px;
        }

    </style>

</head>

<body>

    <!-- HEADER -->
    <div class="header">

        <div class="title">
            GENSERVIS MATERIAL REQUEST SLIP
        </div>

        <div class="subtitle">
            CvSU Carmona Campus
        </div>

    </div>

    <!-- REQUEST INFO -->
    <div class="section">

        <strong>Request No:</strong>
        MR-{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}

        <br><br>

        <strong>Date:</strong>
        {{ $request->created_at->format('F d, Y h:i A') }}

        <br><br>

        <strong>Requester:</strong>
        {{ $request->user->fullname ?? $request->user->username }}

        <br><br>

        <strong>Purpose:</strong>
        {{ $request->purpose }}

        <br><br>

        <strong>Status:</strong>

        <span class="status {{ $request->status }}">
            {{ strtoupper($request->status) }}
        </span>

    </div>

    <!-- MATERIALS -->
    <div class="section">

        <h3>Requested Materials</h3>

        <table>

            <thead>

                <tr>
                    <th>Material</th>
                    <th>Quantity</th>
                </tr>

            </thead>

            <tbody>

                @foreach($request->items as $item)

                    <tr>

                        <td>
                            {{ $item->material->name ?? '-' }}
                        </td>

                        <td>
                            {{ $item->quantity }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <!-- SIGNATURES -->
    <div class="signature">

        <div class="line">
            Requested By
        </div>

        <div class="line">
            Approved By
        </div>

    </div>

</body>
</html>