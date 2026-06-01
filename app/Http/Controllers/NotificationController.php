<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where(
            'user_id',
            auth()->id()
        )
        ->orderByDesc('created_at')
        ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function fetch()
    {
        $user = Auth::user();

        $notifications = Notification::where(
                'user_id',
                $user->id
            )
            ->orderByDesc('created_at')
            ->take(10)
            ->get()

            ->map(fn($n) => [
            'id'         => $n->id,
            'title'      => $n->title,
            'message'    => $n->message,
            'type'       => $n->type,
            'url'        => $n->url,
            'is_read'    => $n->is_read,
            'created_at' => $n->created_at,
            'time'       => $n->created_at->diffForHumans(),
        ]);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Notification::where(
            'user_id',
            $user->id
        )
        ->where('is_read', 0)
        ->count(),
        ]);
    }



    public function markAsRead($id)
    {
        $notif = Notification::findOrFail($id);

        if ($notif->user_id != auth()->id()) {
            abort(403);
        }

        $notif->update([
            'is_read' => 1
        ]);

        return redirect($notif->url ?? route('dashboard'));
    }

        
    public function markAllRead()
    {
        Notification::where(
            'user_id',
            auth()->id()
        )->update([
            'is_read' => 1
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}