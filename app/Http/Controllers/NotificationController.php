<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'success']);
        }

        return back()->with('success', 'Notification marquée comme lue.');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    public function destroy($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->delete();

        return back()->with('success', 'Notification supprimée.');
    }

    public function settings()
    {
        $user = auth()->user();
        $settings = $user->notification_settings;

        return view('notifications.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'task_assignments' => 'boolean',
            'task_deadlines' => 'boolean',
            'project_updates' => 'boolean'
        ]);

        auth()->user()->update([
            'notification_settings' => $validated
        ]);

        return back()->with('success', 'Préférences de notification mises à jour.');
    }
}
