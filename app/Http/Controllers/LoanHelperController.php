<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;

class LoanHelperController extends Controller
{
    public static function getLoansDataByUserId($user_id)
    {
        $loans = Loan::where('user_id', $user_id)->get();

        foreach ($loans as $key => $loan) {
            $data[$key]['id'] = $loan->id;
            $data[$key]['startDate'] = indonesian_date_format($loan->start_date);
            $data[$key]['dueDate'] = indonesian_date_format($loan->due_date);
            $data[$key]['loanDate'] = $loan->loan_date;
            $data[$key]['totalLoan'] = $loan->total_loan;
            $data[$key]['paymentCount'] = $loan->payment_counts;
            $data[$key]['status'] = get_loan_status($loan);
        }

        return $data;
    }

    public function status(Request $request, $id)
    {
        $loan = Loan::find($id);
        if ($request->status == 1) {
            //Status = 1 disetujui, approve jadi 1, dan status 2 (Belum Lunas)
            $loan->is_approve = 1;
            $loan->status = 2;
            $this->createBalance($loan);
        } else if ($request->status == 2) {
            //status == 2 ditolak
            $loan->is_approve = 0;
        } else if ($request->status == 3) {
            //status == 3 Lunas
            $loan->is_approve = 1;
            $loan->status = 1;
        }
        $loan->update();
        return response()->json(['status' => 200, 'message' => 'Berhasil mengubah status peminjaman', 'request' => $request->all()], 200);
    }
}
