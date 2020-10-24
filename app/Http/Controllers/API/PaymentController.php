<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiHelperController;
use App\Http\Controllers\BalanceHelperController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Balance;

class PaymentController extends Controller
{
    private $api;

    public function __construct()
    {
        $this->api = new ApiHelperController;
        $this->balance = new BalanceHelperController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::orderBy('id', 'desc')->get();

        foreach ($payments as $key => $payment) {
            $data[$key] = [
                'id' => $payment->id,
                'loanId' => $payment->loan_id,
                'userName' => $payment->users()->first()->name,
                'userPhoneNumber' => $payment->users()->first()->phone_number,
                'dueDate' => indonesian_date_format($payment->due_date),
                'paymentNumber' => $payment->payment_number,
                'paymentDate' => !is_null($payment->payment_date) ? indonesian_date_format($payment->payment_date) : null,
                'status' => get_payment_status($payment)
            ];
        }

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->success_message,
            'payments' => $data
        ];

        return response()->json($responses, $this->api->success_code);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::find($id);

        $data = [
            'id' => $payment->id,
            'loanId' => $payment->loan_id,
            'userName' => $payment->users()->first()->name,
            'userPhoneNumber' => $payment->users()->first()->phone_number,
            'dueDate' => indonesian_date_format($payment->due_date),
            'paymentNumber' => $payment->payment_number,
            'paymentDate' => !is_null($payment->payment_date) ? indonesian_date_format($payment->payment_date) : null,
            'status' => get_payment_status($payment),
            'description' => $payment->description,
            'employeeName' => $payment->employees()->first()->name,
            'userId' => $payment->users()->first()->id
        ];

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->success_message,
            'payment' => $data
        ];

        return response()->json($responses, $this->api->success_code);
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

    public function status(Request $request, $id)
    {
        $payment = Payment::find($id);
        if ($payment->payment_date === null) {
            $payment->payment_date = date('Y-m-d');
            $this->balance->createBalance($payment, $payment->loan->total_payment_with_interest, 1);
        }
        $payment->status = $request->status;
        $payment->description = $request->desc;
        $payment->update();
        return response()->json(['status' => 200, 'message' => 'Berhasil mengubah status angsuran'], 200);
    }
}
