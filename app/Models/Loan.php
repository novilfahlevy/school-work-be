<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Loan extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function balance()
    {
        return $this->morphOne('App\Models\Balance', 'balanceable');
    }

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

    public static function softDeletesLoan($id)
    {
        Loan::find($id)->delete();
    }
}
