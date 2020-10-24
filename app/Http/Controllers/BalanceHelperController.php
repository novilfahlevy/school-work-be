<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BalanceHelperController extends Controller
{
    public function createBalance($loan)
    {
        $balance = Balance::orderBy("id", "desc")->first();
        $current_balance = $balance->balance - $loan->total_loan;
        $loan->balance()->create([
            'balance' => $current_balance,
            'changed_at' => date('Y-m-d')
        ]);
    }
}
