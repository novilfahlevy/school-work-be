<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Balance;

class BalanceHelperController extends Controller
{
    public function createBalance($balanceable, $value, $type)
    {
        $user = Auth::user();
        $balance = Balance::orderBy("id", "desc")->first();
        $current_balance = $balance->balance + $value;
        $balanceable->balance()->create([
            'balance' => $current_balance,
            'user_id' => $user->id,
            'type' => $type,
            'changed_at' => date('Y-m-d H:i:s')
        ]);
    }
}
