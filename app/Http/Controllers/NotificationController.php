<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead(Request $request)
    {
        // Get the authenticated user's notifications
        $user = Auth::user();
        
        $user->usaha->unreadNotifications->markAsRead();
        
        return response()->json(['success' => true]);
    }
}
