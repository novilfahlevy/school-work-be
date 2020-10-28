<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Http\Controllers\BalanceHelperController;

class PaymentHelperController extends Controller
{
    public function __construct()
    {
        $this->balance = new BalanceHelperController;
    }
    public function loanPaymentDetails($loan_details)
    {
        foreach ($loan_details->payments as $key => $payment_detail) {
            $data[$key]['id'] = $payment_detail->id;
            $data[$key]['dueDate'] = indonesian_date_format($payment_detail->due_date);
            $data[$key]['paymentNumber'] = $payment_detail->payment_number;
            $data[$key]['paymentDate'] = !is_null($payment_detail->payment_date) ? indonesian_date_format($payment_detail->payment_date) : null;
            $data[$key]['status'] = get_payment_status($payment_detail);
            $data[$key]['employeeName'] = $loan_details->employees()->first()->name;
            $data[$key]['description'] = $payment_detail->description;
        }

        return $data;
    }

    public static function storePaymentBasedOnDataFromLoan($payments, $payment_counts, $loan_id)
    {
        for ($i = 0; $i <= $payment_counts - 1; $i++) {
            $data[$i] = [
                'totalPayment' => $payments[$i]['totalPayment'],
                'totalPaymentInterest' => $payments[$i]['totalPaymentInterest'],
                'totalPaymentWithInterest' => $payments[$i]['totalPayment'] + $payments[$i]['totalPaymentInterest'],
                'dueDate' => $payments[$i]['dueDate']
            ];

            $payment = new Payment;
            $payment->loan_id = $loan_id;
            $payment->due_date = $data[$i]['dueDate'];
            $payment->payment_number = $i + 1;
            $payment->status = 0;
            $payment->save();
        }
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
