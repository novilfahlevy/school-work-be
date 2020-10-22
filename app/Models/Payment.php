<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public function balance()
    {
        return $this->morphOne('App\Models\Balance', 'balanceable');
    }
    public function users()
    {
        return $this->hasOne('App\Models\User', 'id');
    }

    public function employees()
    {
        return $this->hasOne('App\Models\User', 'id');
    }

    public function loan()
    {
        return $this->belongsTo('App\Models\Loan');
    }

    /**
     * Wrapping the all payments data
     *
     * @return array
     */
    public static function listOfPayments()
    {
        $payments = Payment::latest('created_at')->get();

        foreach ($payments as $key => $payment) {
            $data[$key]['id'] = $payment->id;
            $data[$key]['loanId'] = $payment->loan_id;
            $data[$key]['userName'] = $payment->users()->first()->name;
            $data[$key]['userPhoneNumber'] = $payment->users()->first()->phone_number;
            $data[$key]['dueDate'] = indonesian_date_format($payment->due_date);
            $data[$key]['paymentNumber'] = $payment->payment_number;
            $data[$key]['paymentDate'] = !is_null($payment->payment_date) ? indonesian_date_format($payment->payment_date) : null;
            $data[$key]['status'] = get_payment_status($payment);
        }

        return $data;
    }

    public static function detailsOfPayment($id)
    {
        $payment_details = Payment::findOrFail($id);

        $data['id'] = $payment_details->id;
        $data['loanId'] = $payment_details->loan_id;
        $data['userName'] = $payment_details->users()->first()->name;
        $data['userPhoneNumber'] = $payment_details->users()->first()->phone_number;
        $data['dueDate'] = indonesian_date_format($payment_details->due_date);
        $data['paymentNumber'] = $payment_details->payment_number;
        $data['paymentDate'] = !is_null($payment_details->payment_date) ? indonesian_date_format($payment_details->payment_date) : null;
        $data['status'] = get_payment_status($payment_details);
        $data['description'] = $payment_details->description;
        $data['employeeName'] = $payment_details->employees()->first()->name;
        $data['userId'] = $payment_details->users()->first()->id;

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
}
