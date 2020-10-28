<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Http\Controllers\BalanceHelperController;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class LoanHelperController extends Controller
{
    private $balance;

    public function __construct()
    {
        $this->balance = new BalanceHelperController;
    }

    public static function getLoansDataByUserId($user_id)
    {
        $loans = Loan::where('user_id', $user_id)->get();

        foreach ($loans as $key => $loan) {
            $data[$key]['id'] = $loan->id;
            $data[$key]['startDate'] = indonesian_date_format($loan->start_date);
            $data[$key]['dueDate'] = indonesian_date_format($loan->due_date);
            $data[$key]['paidDate'] = $loan->paid_date === null ? null : indonesian_date_format($loan->paid_date);
            $data[$key]['totalLoan'] = $loan->total_loan;
            $data[$key]['paymentCount'] = $loan->payment_counts;
            $data[$key]['status'] = get_loan_status($loan);
        }

        return $data ?? null;
    }

    public function status(Request $request, $id)
    {
        $loan = Loan::find($id);
        if ($request->status == 1) {
            //Status = 1 disetujui, approve jadi 1, dan status 2 (Belum Lunas)
            $loan->is_approve = 1;
            $loan->status = 2;
            $this->balance->createBalance($loan, -$loan->total_loan, 1);
        } else if ($request->status == 2) {
            //status == 2 ditolak
            $loan->is_approve = 0;
        } else if ($request->status == 3) {
            //status == 3 Lunas
            $loan->is_approve = 1;
            $loan->paid_date = date("Y-m-d");
            $loan->status = 1;
        }
        $loan->update();
        return response()->json(['status' => 200, 'message' => 'Berhasil mengubah status peminjaman'], 200);
    }

    public function print($id)
    {
        $loan = Loan::findOrFail($id);

        $start_date = Carbon::parse($loan->start_date);
        $end_date = Carbon::parse($loan->due_date);
        $diff = $start_date->diffForHumans($end_date, CarbonInterface::DIFF_ABSOLUTE);

        return view('loans.print', compact('loan', 'diff'));
    }
}
