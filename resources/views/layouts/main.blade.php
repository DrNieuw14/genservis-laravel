<!DOCTYPE html>
<html>
<head>
    <title>GenServis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand text-white">GenServis</span>

    <div class="ms-auto d-flex align-items-center">

        <!-- 🔔 NOTIFICATION -->
        <div class="dropdown me-3">
            <a class="nav-link position-relative text-white" href="#" data-bs-toggle="dropdown">
                🔔
                <span id="notif-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                    0
                </span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end" id="notif-list" style="width:300px;">
                <li class="dropdown-header">Notifications</li>
            </ul>
        </div>

        <!-- USER -->
        <div class="text-white">
            {{ auth()->user()->username }}
            |
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button class="btn btn-sm btn-danger">Logout</button>
            </form>
        </div>

    </div>
</nav>

<!-- CONTENT -->
<div class="container mt-4">
    @yield('content')
</div>


<!-- ✅ BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- 🔔 TOAST CONTAINER (HTML - OUTSIDE SCRIPT) -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="toast-container"></div>
</div>

<!-- 🔊 SOUND -->
<audio id="notif-sound" src="/sounds/notification.mp3" preload="auto"></audio>

@vite(['resources/js/app.js']) <!-- ✅ ADD THIS LINE -->

<script>
    window.userId = {{ auth()->user()->id }};
</script>

<!-- 🔥 FINAL SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    console.log("Echo object:", window.Echo);

    let lastNotificationIds = [];

    function loadNotifications() {
        let list = document.getElementById('notif-list');
        let count = document.getElementById('notif-count');
        let sound = document.getElementById('notif-sound');

        if (!list || !count || !sound) {
            console.log("DOM not ready ❌");
            return;
        }

        fetch('/notifications')
            .then(res => res.json())
            .then(data => {

                list.innerHTML = '<li class="dropdown-header">Notifications</li>';

                let unread = 0;
                let newNotifications = [];

                data.notifications.forEach(n => {
                    if (!n.is_read) unread++;

                    if (!lastNotificationIds.includes(n.id)) {
                        newNotifications.push(n);
                    }

                    let color = {
                        success: 'text-success',
                        warning: 'text-warning',
                        danger: 'text-danger',
                        info: 'text-primary'
                    }[n.type] || 'text-dark';

                    list.innerHTML += `
                        <li>
                            <a href="#" class="dropdown-item ${color}" onclick="markAsRead(${n.id})">
                                <strong>${n.title}</strong><br>
                                <small>${n.message}</small>
                            </a>
                        </li>
                    `;
                });

                count.innerText = unread;

                newNotifications.forEach(n => {
                    showToast(n);
                    sound.play().catch(() => {});
                });

                lastNotificationIds = data.notifications.map(n => n.id);
            });
    }

    function showToast(n) {
        let container = document.getElementById('toast-container');

        if (!container) return;

        let bg = {
            success: 'bg-success',
            warning: 'bg-warning',
            danger: 'bg-danger',
            info: 'bg-primary'
        }[n.type] || 'bg-dark';

        let toast = document.createElement('div');
        toast.className = `toast align-items-center text-white ${bg} border-0 show mb-2`;
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${n.title}</strong><br>
                    ${n.message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;

        container.appendChild(toast);

        setTimeout(() => toast.remove(), 5000);
    }

    function markAsRead(id) {
        fetch('/notifications/read/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => loadNotifications());
    }

    // INITIAL LOAD
    loadNotifications();

    // AUTO REFRESH EVERY 5 SECONDS
setInterval(loadNotifications, 5000);

});
</script>


</body>
</html>