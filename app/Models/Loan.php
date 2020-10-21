<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Loan extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function employees()
    {
        return $this->hasOne('App\Models\User', 'id', 'employee_id');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment');
    }

    /**
     * Wrapping the loans data
     *
     * @return array
     */
    public static function listOfLoans()
    {
        $loans = Loan::latest('created_at')->get();

        foreach ($loans as $key => $loan) {
            $data[$key]['id'] = $loan->id;
            $data[$key]['userId'] = $loan->user_id;
            $data[$key]['userName'] = $loan->users()->first()->name;
            $data[$key]['dueDate'] = indonesian_date_format($loan->due_date);
            $data[$key]['totalLoan'] = $loan->total_loan;
            $data[$key]['status'] = get_loan_status($loan);
            $data[$key]['employeeName'] = $loan->employees()->first()->name;
        }

        return $data;
    }

    /**
     * Wrapping the loan details data
     *
     * @param  mixed $id
     * @return array
     */
    public static function detailsOfLoan($id)
    {
        $loan_details = Loan::findOrFail($id);

        $data['id'] = $loan_details->id;
        $data['userId'] = $loan_details->users()->first()->id;
        $data['userName'] = $loan_details->users()->first()->name;
        $data['userPhoneNumber'] = $loan_details->users->phone_number;
        $data['startDate'] = indonesian_date_format($loan_details->start_date);
        $data['dueDate'] = indonesian_date_format($loan_details->due_date);
        $data['paidDate'] = !is_null($loan_details->loan_date) ? indonesian_date_format($loan_details->loan_date) : null;
        $data['totalLoan'] = $loan_details->total_loan;
        $data['paymentCount'] = $loan_details->payment_counts;
        $data['loanInterest'] = $loan_details->loan_interest;
        $data['loanWithInterest'] = $loan_details->total_loan_with_interest;
        $data['totalPaymentInterest'] = $loan_details->total_payment_interest;
        $data['totalPayment'] = $loan_details->total_payment;
        $data['totalPaymentWithInterest'] = $loan_details->total_payment_with_interest;
        $data['status'] = get_loan_status($loan_details);
        $data['employeeName'] = $loan_details->employees()->first()->name;
        $data['employeeId'] = $loan_details->employees()->first()->id;
        $data['payments'] = Loan::loanPaymentDetails($loan_details);

        return $data;
    }

    /**
     * Get payment details from loan details
     *
     * @param  mixed $loan_details
     * @return array
     */
    public static function loanPaymentDetails($loan_details)
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

    /**
     * Get loans data by user id
     *
     * @param  mixed $user_id
     * @return array
     */
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
}
