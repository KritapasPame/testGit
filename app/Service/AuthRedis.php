<?php

namespace App\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

use App\Models\User;
use Carbon\Carbon;

class AuthRedis {
    public function login($email, $password) {
        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);

            // Store session ID in Redis for the user
            $sessionId = session()->getId();

            // Store the timestamp of the user's last activity (e.g., now)
            Redis::set("user_last_activity:{$user->id}", Carbon::now()->toDateTimeString());

            // Set session expiration for 2 hours (no user activity = expires)
            Cache::put("user_session:{$user->id}", $sessionId, now()->addDays(30));
            Cache::put("user_info:{$user->id}", $user, now()->addDays(30));

            return true;
        }

        return false;
    }

    public function logout(){
        $user = Auth::user();

        if ($user) {
            // Remove session ID from Redis for the user
            // Redis::del("user_last_activity:{$user->id}");
            Redis::srem('active_users', $user->id);
            Cache::forget("user_session:{$user->id}");
            Cache::forget("user_info:{$user->id}");

            Auth::logout();
        }
    }

    public function getUserInfo() {
        $user = Auth::user();
        if ($user) {
            // Retrieve the last activity timestamp from Redis
            $lastActivity = Redis::get("user_last_activity:{$user->id}");

            if ($lastActivity) {
                // Check if the last activity was more than 2 hours ago
                $lastActivityTime = Carbon::parse($lastActivity);
                $now = Carbon::now();

                if ($lastActivityTime->diffInMinutes($now) >= 43200) {
                    Cache::forget("user_session:{$user->id}");
                    Cache::forget("user_info:{$user->id}");
                    Auth::logout();

                    return redirect('/login')->withErrors([
                        'message' => 'Session expired due to 30 days of inactivity. Please log in again.'
                    ]);
                }
            }

            // Update the session (extend expiration time)
            Redis::set("user_last_activity:{$user->id}", Carbon::now()->toDateTimeString());
            return true;
        }
        return false;
    }
}