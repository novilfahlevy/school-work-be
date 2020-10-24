<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function changePassword(Request $request)
    {
        $user = User::find(auth()->id());

        // Jika password yang user inputkan tidak sama dengan password yang sekarang (password akun yang sekarang)
        if (!Hash::check($request->oldPassword, $user->password)) {
            return response()->json(['status' => 400, 'message' => 'Password lama salah!'], 400);
        }

        // Jika inputan konfirmasi password yang baru tidak sama dengan inputan dari password yang baru
        if ($request->newPassword !== $request->confirmNewPassword) {
            return response()->json(['status' => 400, 'message' => 'Password konfirmasi salah!'], 400);
        }

        // jika validasi di atas terlewati, maka akan masuk ke proses ubah password
        $user->password = Hash::make($request->confirmNewPassword);
        $user->save();

        $responses = [
            'status' => 200,
            'message' => 'Password berhasil diubah!'
        ];

        return response()->json($responses, 200);
    }
}
