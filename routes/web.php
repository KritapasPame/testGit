<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Models\User;
use Carbon\Carbon;
use App\Service\AuthRedis;

Route::get('/login', function () {
    $check = new AuthRedis();
    if($check->getUserInfo()){
        return redirect('/dashboard');
    }
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $check = new Authredis();
    if($check->login($request->email, $request->password)){
        return redirect('/dashboard');
    }

    return back()->withErrors(['email' => 'Invalid credentials']);
});


Route::get('/dashboard', function () {
    $check = new AuthRedis();
    if($check->getUserInfo()){
        return view('welcome', ['user' => Auth::user()]);
    }

    return redirect('/login');
})->middleware('auth');
Route::get('/', function () {
    $check = new AuthRedis();
    if($check->getUserInfo()){
        return redirect('/dashboard');
    }
    return redirect('/login');
})->middleware('auth');

Route::get('/test-redis', function () {
    Cache::put('test_key', 'Hello Redis', now()->addMinutes(10));
    return 'Stored test_key in Redis.';
});
Route::post('/logout', function (Request $request) {
    $check = new AuthRedis();
    $check->getUserInfo();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth');

Route::post('/update-last-activity', function () {
    $user = Auth::user();

    if ($user) {
        // Update the last activity time in Redis
        Redis::set("user_last_activity:{$user->id}", Carbon::now()->toDateTimeString());
    }

    return response()->json(['status' => 'success']);
});
