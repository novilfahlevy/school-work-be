<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;

class DepositHelperController extends Controller
{
    public function getDepositDataByUserId($user_id)
    {
        $deposits = Deposit::where('user_id', $user_id)->get();
        $data = [];
        foreach ($deposits as $key => $deposit) {
            $data[$key]['id'] = $deposit->id;
            $data[$key]['depositDate'] = indonesian_date_format($deposit->deposit_date);
            $data[$key]['totalDeposit'] = $deposit->total_deposit;
            $data[$key]['status'] = Deposit::getDepositStatuses($deposit);
        }

        return $data;
    }

    public function getDepositStatuses($deposit)
    {
        if ($deposit->status === 0) {
            $status = 'Belum Divalidasi';
        }

        if ($deposit->status === 1) {
            $status = 'Disetujui';
        }

        if ($deposit->status === 2) {
            $status = 'Ditolak';
        }

        return $status;
    }

    public function status(Request $request, $id)
    {
        $deposit = Deposit::find($id);
        $deposit->status = $request->status;
        $deposit->save();
        $request->status === 1 && $this->balance->createBalance($deposit, $deposit->total_deposit, 2);
        return response()->json(['status' => 200, 'message' => 'Berhasil mengubah status setoran'], 200);
    }
}
