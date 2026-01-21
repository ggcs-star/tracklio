<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function latest()
    {
        return Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(fn ($n) => $this->format($n));
    }

    public function all()
    {
        return Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($n) => $this->format($n));
    }

    private function format($n)
    {
        return [
            'id' => (string) $n->_id, // 🔥 ONLY id (no _id in frontend)
            'type' => $n->type,
            'message' => $n->message,
            'is_read' => (bool) $n->is_read,
            'created_at' => $n->created_at,
        ];
    }

    public function unreadCount()
    {
        return [
            'count' => Notification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->count()
        ];
    }

    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())
            ->update(['is_read' => true]);
    }

    public function toggleRead($id)
    {
        $note = Notification::where('_id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($note) {
            $note->update(['is_read' => !$note->is_read]);
        }
    }

    public function clearAll()
    {
        Notification::where('user_id', auth()->id())->delete();
    }
}

