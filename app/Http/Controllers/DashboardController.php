<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Balance;
use App\Models\User;
use App\Models\Loan;
use App\Models\Payment;
use App\Models\Deposit;
use App\Models\LoanSubmission;
use Carbon\Carbon;
use App\Http\Controllers\ApiHelperController;
use App\Http\Controllers\LoanHelperController;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->api = new ApiHelperController;
        $this->loan = new LoanHelperController;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function toDashboardData($count, $lastDate)
    {
        return [
            "count" => $count,
            "lastDate" =>  Carbon::parse($lastDate)->format("d-m-Y H:i:s")
        ];
    }
    private function getLoansByMonthsData($year = null)
    {
        $year = $year === null ? Carbon::now()->format("Y") : $year;
        $line_data = [];
        for ($month = 1; $month <= 12; $month++) {
            $line_data[] = Loan::whereMonth('start_date', $month)->whereYear('start_date', $year)->count();
        }
        $pie_data = array_count_values(Loan::whereYear('start_date', $year)->get()->map(function ($loan) {
            return get_loan_status($loan);
        })->toArray());
        return [
            'lineData' => $line_data,
            'pieData' => [
                $pie_data["Lunas"] ?? 0,
                $pie_data["Belum Lunas"] ?? 0,
                $pie_data["Belum Divalidasi"] ?? 0,
                $pie_data["Ditolak"] ?? 0
            ]
        ];
    }
    public function index()
    {
        $balance = Balance::orderBy("id", "desc")->first();
        $deposits = Deposit::orderBy("id", "desc")->get();
        $loans = Loan::orderBy("id", "desc")->get();
        $users = User::whereHas('roles', function ($query) {
            $query->where('role_id', 3);
        })->orderBy('id', 'desc')->get();
        $paid_payments = Payment::where("status", 1)->orderBy("payment_date", "desc")->get();
        $late_payments = Payment::where("status", 1)->where("due_date", "<", date("Y-m-d"))->orderBy("id", "desc")->get();
        $data = [
            "balance" => $this->toDashboardData($balance->balance, $balance->changed_at),
            "deposit" => $this->toDashboardData($deposits->count(), $deposits->first()->created_at),
            "loan" => $this->toDashboardData($loans->count(), $loans->first()->created_at),
            "user" => $this->toDashboardData($users->count(), $users->first()->created_at),
            "paidPayment" => $this->toDashboardData($paid_payments->count(), $paid_payments->first()->payment_date),
            "latePayment" => $this->toDashboardData($late_payments->count(), $late_payments->first()->created_at),
        ];
        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->success_message,
            'infographics' => $data,
            'graphics' => $this->getLoansByMonthsData()
        ];
        return response()->json($responses, $this->api->success_code);
    }

    public function user()
    {
        $user = Auth::user();
        $payments = [];
        $loans = $this->loan->getLoansDataByUserId($user->id);
        $loan_submissions = LoanSubmission::where('user_id', $user->id)->get()->map(function ($submission) {
            return [
                'totalLoan' => $submission->total_loan,
                'startDate' => indonesian_date_format($submission->start_date),
                'createdDate' => indonesian_date_format($submission->created_at),
                'status' => get_loan_submission_approve_status($submission),
                'message' => $submission->message
            ];
        });
        foreach ($user->loans as $loan) {
            $near_due_payments = $loan->payments->whereBetween('due_date', [Carbon::now(), Carbon::now()->addDays(10)]);
            foreach ($near_due_payments as $payment) {
                $payments[] = [
                    'id' => $payment->id,
                    'dueDate' => indonesian_date_format($payment->due_date),
                    'totalPaymentInterest' => $payment->loan->total_payment_interest,
                    'totalPayment' => $payment->loan->total_payment,
                    'totalPaymentWithInterest' => $payment->loan->total_payment_with_interest,
                    'status' => get_payment_status($payment),
                    'loanId' => $payment->loan->id
                ];
            }
        }
        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->success_message,
            'payments' => $payments,
            'loans' => $loans,
            "loanSubmissions" => $loan_submissions
        ];
        return response()->json($responses, $this->api->success_code);
    }
}
