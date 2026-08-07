<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $notifications = AppNotification::where('user_id', $user->id)
            ->latest()
            ->take(15)
            ->get()
            ->map(function ($n) {
                return [
                    'id'         => $n->id,
                    'type'       => $n->type,
                    'title'      => $n->title,
                    'body'       => $n->body,
                    'link'       => $n->link,
                    'read_at'    => $n->read_at,
                    'created_at' => $n->created_at->toISOString(),
                    'is_announcement' => false,
                ];
            });

        $roleAudience = $user->role === 'teacher' ? 'teachers' : 'students';

        $announcements = Announcement::where('is_active', true)
            ->where(function ($q) use ($roleAudience) {
                $q->where('audience', 'all')
                    ->orWhere('audience', $roleAudience);
            })
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($a) {
                return [
                    'id'              => 'ann_' . $a->id,
                    'type'            => 'announcement',
                    'title'           => $a->title,
                    'body'            => $a->body,
                    'ann_type'        => $a->type,
                    'link'            => null,
                    'read_at'         => null,
                    'created_at'      => $a->created_at->toISOString(),
                    'is_announcement' => true,
                ];
            });

        $all = $announcements->concat($notifications)->take(20);

        $unreadCount = AppNotification::where('user_id', $user->id)
            ->unread()
            ->count() + $announcements->count();

        return response()->json([
            'notifications' => $all->values(),
            'unread_count'  => $unreadCount,
        ]);
    }

    public function markRead(AppNotification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        AppNotification::where('user_id', Auth::id())->unread()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
