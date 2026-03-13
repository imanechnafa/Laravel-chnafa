<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Liste des notifications
    public function index()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
                                     ->orderBy('created_at', 'desc')
                                     ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    // Marquer une notification comme lue
    public function markRead(Notification $notification)
    {
        $notification->markAsRead();
        return redirect()->back()->with('success', 'Notification marquée comme lue.');
    }

    // Marquer toutes les notifications comme lues
    public function markAllRead()
    {
        $user = Auth::user();
        Notification::where('user_id', $user->id)->update([
            'read' => true,
            'read_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    // Supprimer une notification
    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->back()->with('success', 'Notification supprimée.');
    }
}
