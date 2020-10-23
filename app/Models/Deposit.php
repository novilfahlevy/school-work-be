<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    public function balance()
    {
        return $this->morphOne('App\Models\Balance', 'balanceable');
    }

    public function users()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    /**
     * Get details of deposit by id
     *
     * @param  mixed $id
     * @return void
     */
    public static function detailsOfDeposit($id)
    {
        $deposit_details = Deposit::findOrFail($id);

        $data['id'] = $deposit_details->id;
        $data['employeeName'] = $deposit_details->users()->first()->name;
        $data['totalDeposit'] = $deposit_details->total_deposit;
        $data['depositDate'] = indonesian_date_format($deposit_details->deposit_date);
        $data['status'] = Deposit::getDepositStatuses($deposit_details);

        return $data;
    }

    /**
     * Get deposit statuses
     *
     * @param  mixed $deposit
     * @return string
     */
    public static function getDepositStatuses($deposit)
    {
        if ($deposit->status === 0) {
            $status = 'Belum Divalidasi';
        }

        if ($deposit->status === 1) {
            $status = 'Disetujui';
        }

        if ($deposit->status === 2) {
            $status = 'Ditolak';
        }

        return $status;
    }
}
