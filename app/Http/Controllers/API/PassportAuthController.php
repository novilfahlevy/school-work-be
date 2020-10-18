<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PassportAuthController extends Controller
{
    /**
     * API Register
     *
     * @param  mixed $request
     * @return json
     */
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => now(),
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('token')->accessToken;

        return response()->json(['status' => 200, 'message' => 'Register berhasil!', 'token' => $token], 200);
    }

    /**
     * API Login
     *
     * @param  mixed $request
     * @return json
     */
    public function login(Request $request)
    {
        $login_credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!auth()->attempt($login_credentials)) {
            return response()->json(['status' => 401, 'message' => 'E-Mail atau Password salah!'], 401);
        }

        $user = Auth::user();
        $data['id'] = $user->id;
        $data['username'] = $user->name;
        $data['role'] = User::getUserRoleName(User::find($user->id));
        $data['token'] =  auth()->user()->createToken('token')->accessToken;

        return response()->json(['status' => 200, 'message' => 'Login berhasil!', 'data' => $data], 200);
    }
}
