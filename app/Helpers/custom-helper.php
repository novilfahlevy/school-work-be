<?php

if (!function_exists('indonesian_date_format')) {
    /**
     * Change database date to Indonesian date format
     *
     * @param  mixed $date
     * @return date
     */
    function indonesian_date_format($date)
    {
        return date('d-m-Y', strtotime($date));
    }
}

if (!function_exists('get_gender_name')) {
    /**
     * Get gender name
     *
     * @param  mixed $user
     * @return string
     */
    function get_gender_name($user)
    {
        return $user->gender === 0 ? 'Laki-laki' : 'Perempuan';
    }
}

if (!function_exists('get_payment_statuses')) {
    /**
     * Get payment statuses
     *
     * @param  mixed $payment
     * @return string
     */
    function get_payment_status($payment)
    {
        if ($payment->status === 0 && date('Y-m-d') > $payment->due_date) {
            $status = 'Belum Lunas Terlambat';
        } else if ($payment->status === 1 && date('Y-m-d') <  $payment->payment_date) {
            $status = 'Lunas Terlambat';
        } else if ($payment->status === 1) {
            $status = 'Lunas';
        } else if ($payment->status === 0) {
            $status = 'Belum Lunas';
        }
        return $status;
    }
}

if (!function_exists('get_loan_status')) {
    /**
     * Get loan status
     *
     * @param  mixed $loan
     * @return string
     */
    function get_loan_status($loan)
    {
        // status 0 = belum divalidasi
        //status 2 = belum lunas
        //status 1 = lunas
        if ($loan->status === 0 && $loan->is_approve === NULL) {
            $status = 'Belum Divalidasi';
        }

        if ($loan->status === 1 && $loan->is_approve === 1) {
            $status = 'Lunas';
        }

        if ($loan->status === 2 && $loan->is_approve === 1) {
            $status = 'Belum Lunas';
        }

        if ($loan->is_approve === 0) {
            $status = 'Ditolak';
        }

        return $status;
    }
}
