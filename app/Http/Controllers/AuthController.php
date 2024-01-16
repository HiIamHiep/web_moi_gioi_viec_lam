<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function callback($provider)
    {
        $data = Socialite::driver($provider)->user();

        $user = User::query()
            ->where('email', $data->getEmail())
            ->first();

        //Check xem đã có người dùng chưa
        if (is_null($user))
        {
           $user = new User();
           $user->email = $data->getEmail();
        }

        $user->name = $data->getName();
        $user->avatar = $data->getAvatar();
        $user->role = UserRoleEnum::ADMIN;
        $user->save();

        $role = getRoleByKey($user->role);

        auth()->login($user); // Tạo ra session và nhớ users trong phiên đó

        return redirect()->route("$role.welcome");

    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();

        return redirect()->route('login');
    }
}
