<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function fetch()
    {
        $user = Auth::user();

        $notifications = $user->notifications()
            ->orderByDesc('created_at')
            ->take(10)
            ->get()
            ->map(fn($n) => [
                'id'      => $n->id,
                'message' => $n->data['message'] ?? 'New notification',
                'url'     => $n->data['url'] ?? '#',
                'read'    => ! is_null($n->read_at),
                'time'    => $n->created_at->diffForHumans(),
            ]);

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $user->unreadNotifications()->count(),
        ]);
    }

    public function markAsRead(string $id): RedirectResponse
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        $url = $notification->data['url'] ?? route('dashboard');
        return redirect($url);
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}