<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginingRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class LoginController extends Controller
{

    public function logining(LoginingRequest $request)
    {
        DB::enableQueryLog();

        $email = $request->get('email');
        $password = $request->get('password');


        $user = User::query()
            ->where('email', $email)
            ->first();

        if(!$user) {
            redirect()->back();
        }

        if(Hash::check($password, $user->password)) {
            auth()->login($user, true); // Tạo ra session và nhớ users trong phiên đó
        }

        $role = getRoleByKey($user->role);

        return redirect()->route("$role.welcome");

    }
}
