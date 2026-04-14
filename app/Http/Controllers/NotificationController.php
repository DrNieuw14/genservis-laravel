<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::user()->id)
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = Notification::where('user_id', Auth::user()->id)
            ->where('is_read', 0)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread' => $unreadCount
        ]);
    }

    public function markAsRead($id)
    {
        $notif = Notification::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();

        if ($notif) {
            $notif->update(['is_read' => 1]);
        }

        return response()->json(['success' => true]);
    }
}