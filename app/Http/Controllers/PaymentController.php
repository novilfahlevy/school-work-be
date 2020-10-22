<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Balance;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Payment::listOfPayments();

        return response()->json(['status' => 200, 'message' => 'Berhasil mengambil data angsuran', 'payments' => $data], 200);
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
        $data = Payment::detailsOfPayment($id);

        return response()->json(['status' => 200, 'message' => 'Berhasil mengambil detail angsuran', 'payment' => $data], 200);
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
            $balance = Balance::orderBy("id", "desc")->first();
            $current_balance = $balance->balance + $payment->loan->total_payment_with_interest;
            $payment->balance()->create([
                'balance' => $current_balance,
                'changed_at' => date('Y-m-d')
            ]);
        }
        $payment->status = $request->status;
        $payment->description = $request->desc;
        $payment->update();
        return response()->json(['status' => 200, 'message' => 'Berhasil mengubah status angsuran'], 200);
    }
}
