<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function delete($id)
    {
        $notification = Auth::user()->usaha->notifications()->findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }

    public function deleteAll()
    {
        Auth::user()->usaha->notifications()->delete();

        return redirect()->back()->with('success', 'All notifications deleted successfully.');
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 0dff5f6901ee860ee09d5f359bfe9388e543fa81
