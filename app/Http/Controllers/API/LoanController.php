<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Loan::listOfLoans();

        return response()->json(['status' => 200, 'message' => 'Berhasil mengambil data pinjaman', 'loans' => $data], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        $loan->user_id = $request->userId;
        $loan->employee_id = auth()->id();
        $loan->status = 0;
        $loan->save();

        Payment::storePaymentBasedOnDataFromLoan($request->dueDate, $loan->id, $request->paymentCounts);

        return response()->json(['status' => 201, 'message' => 'Berhasil menambah pinjaman'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Loan::detailsOfLoan($id);

        return response()->json(['status' => 200, 'message' => 'Berhasil mengambil data pinjaman', 'loans' => $data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }
}
