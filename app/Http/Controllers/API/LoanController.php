<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiHelperController;
use App\Http\Controllers\BalanceHelperController;
use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Balance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PaymentHelperController;

class LoanController extends Controller
{
    private $payment;
    private $balance;
    private $api;

    public function __construct()
    {
        $this->payment = new PaymentHelperController;
        $this->balance = new BalanceHelperController;
        $this->api = new ApiHelperController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = Loan::latest('created_at')->get();

        foreach ($loans as $key => $loan) {
            $data[$key] = [
                'id' => $loan->id,
                'userId' => $loan->user_id,
                'userName' => $loan->users()->first()->name,
                'startDate' => indonesian_date_format($loan->start_date),
                'dueDate' => indonesian_date_format($loan->due_date),
                'totalLoan' => $loan->total_loan,
                'status' => get_loan_status($loan),
                'employeeName' => $loan->employees()->first()->name
            ];
        }

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->success_message,
            'loans' => $data
        ];

        return response()->json($responses, $this->api->success_code);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $balance = Balance::orderBy("id", "desc")->first();
        if ($user->roles->first()->id === 1) {
            if ($balance < $request->total_loan) {
                return response()->json(['status' => 400, 'message' => 'Saldo tidak mencukupi'], 400);
            }
        }
        $loan = new Loan;
        $loan->due_date = $request->dueDate;
        $loan->loan_interest = $request->loanInterest;
        $loan->payment_counts = $request->paymentCounts;
        $loan->start_date = $request->startDate;
        $loan->total_loan = $request->totalLoan;
        $loan->total_loan_with_interest = $request->totalLoanWithInterest;
        $loan->total_payment = $request->totalPayment;
        $loan->total_payment_interest = $request->totalPaymentInterest;
        $loan->total_payment_with_interest = $request->totalPayment + $request->totalPaymentInterest;
        $payments = $request->payments;
        $loan->user_id = $request->userId;
        $loan->employee_id = auth()->id();
        $loan->is_approve = $user->roles->first()->id !== 1 ? null : 1; //sudah divalidasi
        $loan->status = $user->roles->first()->id !== 1 ? 0 : 2; //status 2= belum lunas 0 = proses
        $loan->save();
        if ($user->roles->first()->id === 1) $this->balance->createBalance($loan, -$loan->total_loan, 1);
        $this->payment->storePaymentBasedOnDataFromLoan($payments, $request->paymentCounts, $loan->id);

        $responses = [
            'status' => $this->api->created_code,
            'message' => $this->api->created_message
        ];

        return response()->json($responses, $this->api->created_code);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loan = Loan::findOrFail($id);

        $data = [
            'id' => $loan->id,
            'userId' => $loan->users()->first()->id,
            'userName' => $loan->users()->first()->name,
            'userPhoneNumber' => $loan->users->phone_number,
            'startDate' => indonesian_date_format($loan->start_date),
            'dueDate' => indonesian_date_format($loan->due_date),
            'paidDate' => !is_null($loan->paid_date) ? indonesian_date_format($loan->paid_date) : null,
            'totalLoan' => $loan->total_loan,
            'paymentCount' => $loan->payment_counts,
            'loanInterest' => $loan->loan_interest,
            'loanWithInterest' => $loan->total_loan_with_interest,
            'totalPaymentInterest' => $loan->total_payment_interest,
            'totalPayment' => $loan->total_payment,
            'totalPaymentWithInterest' => $loan->total_payment_with_interest,
            'status' => get_loan_status($loan),
            'employeeName' => $loan->employees()->first()->name,
            'employeeId' => $loan->employees()->first()->id,
            'payments' => $this->payment->loanPaymentDetails($loan)
        ];

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->created_message,
            'loan' => $data
        ];

        return response()->json($responses, $this->api->success_code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loan = Loan::find($id);

        if ($loan->status === 2) {
            return response()->json(['status' => 403, 'message' => 'Peminjaman belum lunas!'], 403);
        }

        $loan->delete();

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->deleted_message
        ];

        return response()->json($responses, $this->api->success_code);
    }
}
