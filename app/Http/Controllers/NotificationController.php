<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // 🔹 Fetch notifications for the logged-in user
    public function fetch()
    {
        $user = auth()->user();
        $role = $user->role ?? null;

        $notifications = Notification::where(function ($q) use ($user, $role) {
            $q->orWhereJsonContains('role_targets', 'all');
            $q->orWhere('user_id', $user->id);
            if ($role) {
                $q->orWhereJsonContains('role_targets', $role);
            }
        })
            ->where(function ($q) use ($user) {
                // Exclude notifications already read by this user
                $q->whereNull('read_by')
                    ->orWhereRaw('JSON_CONTAINS(read_by, ?)=0', [json_encode($user->id)]);
            })
             ->where('is_read', false) 
            ->latest()
            ->take(10)
            ->get();

        return response()->json($notifications);
    }

    // 🔹 Mark a single notification as read (per-user)
    public function markRead(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:notifications,id',
        ]);

        $userId = auth()->id();
        $notification = Notification::findOrFail($request->id);

        // If shared notification (role/all), store read_by list
        if (in_array('all', $notification->role_targets ?? []) || !empty($notification->role_targets)) {
            $readBy = $notification->read_by ?? [];
            if (!in_array($userId, $readBy)) {
                $readBy[] = $userId;
                $notification->read_by = $readBy;
                $notification->save();
            }
        } else {
            // If it's an individual notification
            $notification->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }

   public function index(Request $request)
{
    $user = auth()->user();
    $role = $user->role ?? null;
    $selectedDate = $request->date;

    $notifications = Notification::where(function ($q) use ($user, $role) {
        $q->orWhereJsonContains('role_targets', 'all');
        $q->orWhere('user_id', $user->id);
        if ($role) {
            $q->orWhereJsonContains('role_targets', $role);
        }
    });

    // 🔹 If date filter is applied
    if ($selectedDate) {
        $notifications->whereDate('created_at', $selectedDate);
    }

    $notifications = $notifications
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('notification.index', compact('notifications', 'selectedDate'));
}

}
