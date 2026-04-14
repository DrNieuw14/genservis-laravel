<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use App\Events\NewNotificationEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;



class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
  

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // ✅ NORMAL USER ONLY
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'personnel',
            'status' => 'pending',
        ]);

        // 🔔 NOTIFY SUPERVISOR
        $supervisors = User::where('role', 'supervisor')->get();

        foreach ($supervisors as $admin) {
            $notif = Notification::create([
                'user_id' => $admin->id,
                'title' => 'New User Registration',
                'message' => $user->name . ' is waiting for approval.',
                'type' => 'user_registration', // ✅ ADD THIS LINE
                'is_read' => 0,
            ]);

            event(new NewNotificationEvent($notif));
        }

        return redirect('/login')->with('success', 'Account created. Wait for approval.');
    }
}
