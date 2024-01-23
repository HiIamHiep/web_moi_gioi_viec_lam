<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Http\Requests\Auth\RegisteringRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        $roles = UserRoleEnum::getRolesForRegister();

        return view('auth.register', [
            'roles' => $roles,
        ]);
    }

    public function callback($provider)
    {
        $data = Socialite::driver($provider)->user();

        $user = User::query()
            ->where('email', $data->getEmail())
            ->first();
        $checkExits = true;


        //Check xem đã có người dùng chưa
        if (is_null($user)) {
            $user = new User();
            $user->email = $data->getEmail();
            $checkExits = false;
        }

        $user->name = $data->getName();
        $user->avatar = $data->getAvatar();

        auth()->login($user, true); // Tạo ra session và nhớ users trong phiên đó

        if ($checkExits) {
            $role = getRoleByKey($user->role);

            return redirect()->route("$role.welcome");
        }

        return redirect()->route('register');

    }

    public function registering(RegisteringRequest $request)
    {
        $password = Hash::make($request->get('password'));
        $role = $request->get('role');

        if (auth()->check()) {
            User::where('id', auth()->user()->id)->update([
                'password' => $password,
                'role' => $role,
            ]);
        } else {
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => $password,
                'role' => $role,
            ]);

            auth()->login($user);

        }

        $role = getRoleByKey($role);

        return redirect()->route("$role.welcome");

    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();

        return redirect()->route('login');
    }
}
