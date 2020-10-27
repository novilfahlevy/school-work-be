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
            $data[$key]['loanDate'] = $loan->loan_date;
            $data[$key]['totalLoan'] = $loan->total_loan;
            $data[$key]['paymentCount'] = $loan->payment_counts;
            $data[$key]['status'] = get_loan_status($loan);
        }

        return $data ?? null;
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
