<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Utility Attendance Scan — GenServis</title>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <style>

        body{

            margin:0;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #0f766e, #1e3a8a);
            min-height:100vh;
            display:flex;
            flex-direction:column;
            align-items:center;
            color:#fff;

        }

        .header{

            text-align:center;
            padding:30px 20px 10px;

        }

        .header img{

            height:70px;

        }

        .header h1{

            margin:8px 0 2px;
            font-size:24px;

        }

        .header p{

            margin:0;
            opacity:0.85;
            font-size:14px;

        }

        .scan-box{

            background:#fff;
            border-radius:16px;
            padding:20px;
            margin-top:20px;
            width:340px;
            max-width:90vw;
            box-shadow:0 10px 30px rgba(0,0,0,0.3);

        }

        #reader{

            width:100%;
            border-radius:12px;
            overflow:hidden;

        }

        .status{

            margin-top:16px;
            padding:20px 16px;
            border-radius:10px;
            text-align:center;
            font-weight:bold;
            font-size:18px;
            min-height:24px;
            color:#333;
            background:#f3f4f6;

        }

        .status.success{

            background:#dcfce7;
            color:#166534;

        }

        .status.error{

            background:#fee2e2;
            color:#991b1b;

        }

        .back-link{

            margin-top:24px;
            color:#fff;
            opacity:0.85;
            text-decoration:none;
            font-size:14px;

        }

        .back-link:hover{

            text-decoration:underline;

        }

        .scan-photo{

            width:180px;
            height:180px;
            border-radius:50%;
            object-fit:cover;
            display:block;
            margin:0 auto 16px;
            border:5px solid #fff;
            box-shadow:0 4px 16px rgba(0,0,0,0.3);

        }

    </style>

</head>
<body>

    <div class="header">
        <img src="/images/logo.png" alt="CvSU Logo">
        <h1>Utility Attendance Scan</h1>
        <p>Cavite State University — Carmona Campus</p>
    </div>

    <div class="scan-box">

        <div id="reader"></div>

        <div id="status" class="status">
            Point your ID QR code at the camera.
        </div>

    </div>

    <a href="{{ route('home') }}" class="back-link">⬅ Back to Home</a>

    <script>

        const statusEl = document.getElementById('status');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        let isProcessing = false;
        let lastScannedText = null;
        let lastScanTime = 0;
        let idleResetTimer = null;

        const html5QrCode = new Html5Qrcode('reader');

        function clearIdleReset() {
            if (idleResetTimer) {
                clearTimeout(idleResetTimer);
                idleResetTimer = null;
            }
        }

        function scheduleIdleReset(delayMs) {
            clearIdleReset();
            idleResetTimer = setTimeout(() => {
                statusEl.innerHTML = 'Point your ID QR code at the camera.';
                statusEl.className = 'status';
            }, delayMs);
        }

        function setStatus(message, type) {
            clearIdleReset();
            statusEl.textContent = message;
            statusEl.className = 'status' + (type ? ' ' + type : '');
        }

        function submitScan(qrData, extraParams) {
            return fetch('{{ route('attendance-kiosk.scan') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(Object.assign({ qr_data: qrData }, extraParams || {})),
            }).then(response => response.json().then(data => ({ ok: response.ok, data })));
        }

        function photoHtml(data) {
            return data.photo_url
                ? '<img class="scan-photo" src="' + data.photo_url + '" alt="' + (data.name || '') + '">'
                : '';
        }

        function showResult(data) {

            clearIdleReset();

            const name = data.name ? data.name + ' — ' : '';
            statusEl.innerHTML = photoHtml(data) + '<div>' + name + data.message + '</div>';
            statusEl.className = 'status' + (data.success ? ' success' : ' error');

            // A check-out just happened — offer a short window to undo it
            // in case it was an accidental scan.
            if (data.success && data.action === 'check_out') {
                showUndoButton(data);
                return;
            }

            scheduleIdleReset(4000);
        }

        function showUndoButton(data) {

            statusEl.innerHTML = photoHtml(data)
                + '<div>' + data.name + ' — ' + data.message + '</div>'
                + '<button id="undoBtn" style="margin-top:10px;padding:8px 16px;border-radius:8px;border:none;background:#991b1b;color:#fff;font-weight:bold;cursor:pointer;">'
                + '↩ Undo Check-Out'
                + '</button>';
            statusEl.className = 'status success';

            document.getElementById('undoBtn').addEventListener('click', function () {

                clearIdleReset();
                setStatus('Undoing…');

                submitScan(lastScannedText, { undo_checkout: true }).then(({ data }) => {
                    showResult(data);
                });
            });

            // After ~8 seconds the check-out is treated as final and the
            // undo option disappears.
            scheduleIdleReset(8000);
        }

        function showConfirmation(data) {

            statusEl.innerHTML = photoHtml(data)
                + '<div>' + data.name + ' is currently checked in.</div>'
                + '<div style="margin-top:10px;display:flex;gap:8px;justify-content:center;">'
                + '<button id="confirmBtn" style="padding:8px 16px;border-radius:8px;border:none;background:#166534;color:#fff;font-weight:bold;cursor:pointer;">✅ Check Out</button>'
                + '<button id="cancelBtn" style="padding:8px 16px;border-radius:8px;border:none;background:#6b7280;color:#fff;font-weight:bold;cursor:pointer;">❌ Cancel</button>'
                + '</div>';
            statusEl.className = 'status';

            // Nothing has been changed on the server yet, so Cancel just
            // resets the screen — no request needed.
            document.getElementById('cancelBtn').addEventListener('click', function () {
                clearIdleReset();
                statusEl.innerHTML = 'Point your ID QR code at the camera.';
                statusEl.className = 'status';
            });

            document.getElementById('confirmBtn').addEventListener('click', function () {

                clearIdleReset();
                setStatus('Processing…');

                submitScan(lastScannedText, { confirm_checkout: true }).then(({ data }) => {
                    showResult(data);
                });
            });

            // No auto-reset while waiting on the user's decision.
            clearIdleReset();
        }

        function onScanSuccess(decodedText) {

            const now = Date.now();

            // Ignore repeat detections of the same code while it's still
            // in view, and ignore anything while a previous scan is being
            // processed — the camera keeps decoding every frame.
            if (isProcessing || (decodedText === lastScannedText && now - lastScanTime < 5000)) {
                return;
            }

            lastScannedText = decodedText;
            lastScanTime = now;
            isProcessing = true;

            setStatus('Processing…');

            submitScan(decodedText)
                .then(({ data }) => {

                    if (data.action === 'needs_confirmation') {
                        showConfirmation(data);
                        return;
                    }

                    showResult(data);
                })
                .catch(() => {
                    setStatus('Network error — please try scanning again.', 'error');
                    scheduleIdleReset(4000);
                })
                .finally(() => {
                    isProcessing = false;
                });
        }

        Html5Qrcode.getCameras()
            .then(cameras => {

                if (!cameras || !cameras.length) {
                    setStatus('No camera found on this device.', 'error');
                    return;
                }

                html5QrCode.start(
                    cameras[0].id,
                    { fps: 10, qrbox: 250 },
                    onScanSuccess,
                    () => {} // per-frame "no QR found" noise — nothing to show the user
                );
            })
            .catch(() => {
                setStatus('Camera access denied or unavailable. Please allow camera permission.', 'error');
            });

    </script>

</body>
</html>
