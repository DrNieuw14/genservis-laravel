<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>
            </div>

             <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    
                <!-- Dashboard -->
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>

                <!-- Approve Users (Supervisor Only) -->
                @if(auth()->user()->role === 'supervisor')
                    <x-nav-link 
                        :href="route('admin.users.pending')" 
                        :active="request()->routeIs('admin.users.pending')"
                    >
                        Approve Users
                    </x-nav-link>
                @endif

            </div>

                <div class="hidden sm:flex sm:items-center gap-4">

    <!-- 🔔 Notification Bell -->
    <div class="relative" x-data="{ openNotif: false }">

        <button @click="openNotif = !openNotif" class="relative text-xl">
            🔔
            <span id="notif-count"
                class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-2 py-0.5 font-bold">
                0
            </span>
        </button>

        <!-- Dropdown -->
        <div x-show="openNotif"
            @click.outside="openNotif = false"
            class="absolute right-0 top-full mt-2 w-80 bg-white rounded-xl shadow-xl z-50">

            <div class="p-3 border-b font-bold flex justify-between">
                <span>Notifications</span>
                <button onclick="markAllRead()" class="text-xs text-blue-500">
                    Mark all
                </button>
            </div>

            <div id="notif-list" class="max-h-64 overflow-y-auto"></div>

            <div class="p-2 text-center border-t">
                <a href="/leave/admin" class="text-blue-500 text-sm">
                    View all
                </a>
            </div>
        </div>
    </div>

    <!-- 👤 USER -->
    <div>
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="flex items-center text-sm text-gray-600">
                    {{ Auth::user()->name }}
                    <svg class="ml-1 w-4 h-4" viewBox="0 0 20 20">
                        <path fill="currentColor"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4z"/>
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Logout
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>

</div>
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>


                    </div>
                </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">

                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>

                @if(auth()->user()->role === 'supervisor')
                    <x-responsive-nav-link 
                        :href="route('admin.users.pending')" 
                        :active="request()->routeIs('admin.users.pending')"
                    >
                        Approve Users
                    </x-responsive-nav-link>
                @endif

            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
        </div>
    </div>

    <script>
        async function loadNotifications() {
        const res = await fetch('/notifications');
        const data = await res.json();

        const list = document.getElementById('notif-list');
        const count = document.getElementById('notif-count');

        if (data.unread > 0) {
            count.innerText = data.unread;
            count.style.display = 'inline-block';
        } else {
            count.style.display = 'none';
        }



        // reset
        list.innerHTML = '';
        

        // empty state
        if (!data.notifications || data.notifications.length === 0) {
            list.innerHTML = `
                <p class="p-3 text-gray-500 text-sm text-center">
                    📭 No notifications yet
                </p>
            `;
            return;
        }

        // loop notifications
        data.notifications.slice(0, 5).forEach(notif => {
            list.innerHTML += `
                <div onclick="handleNotification(${notif.id}, '${notif.type}', this)"
                   class="p-3 border-b hover:bg-gray-100 transition cursor-pointer flex gap-3 items-start
                    ${notif.is_read ? 'bg-white' : 'bg-blue-50'}">

                    <!-- ICON -->
                    <div class="text-xl">
                        ${notif.type === 'leave' ? '📄' : 
                        notif.type === 'user_registration' ? '👤' : '🔔'}
                    </div>

                    <!-- CONTENT -->
                    <div class="flex-1">
                    
                    ${!notif.is_read ? '<span class="text-blue-500 text-xs">●</span>' : ''}

                        <div class="font-semibold text-sm text-gray-800">
                            ${notif.title}
                        </div>

                        <div class="text-xs text-gray-600">
                            ${notif.message}
                        </div>

                        <div class="text-[10px] text-gray-400 mt-1">
                            ${new Date(notif.created_at).toLocaleString()}
                        </div>
                    </div>
                </div>
            `;
        });
    }
            

        window.handleNotification = async function(id, type, element) {
            element.closest('[x-data]').__x.$data.openNotif = false;

            element.classList.add('opacity-50');

            await fetch(`/notifications/read/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });

            switch (type) {
                case 'user_registration':
                    window.location.href = '/admin/users/pending';
                    break;

                case 'leave':
                case 'leave_request':
                    window.location.href = "{{ route('leave.requests') }}";
                    break;

                default:
                    console.log('No route for type:', type);
            }
        }

        // Load on start
        loadNotifications();

        // Realtime
        Echo.private('notifications.' + "{{ Auth::user()->id }}")
        .listen('NewNotificationEvent', (e) => {
            console.log('REALTIME RECEIVED:', e);
            loadNotifications();
        });

        window.markAllRead = async function() {
            await fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            loadNotifications();
        }

        
    </script>
</nav>
