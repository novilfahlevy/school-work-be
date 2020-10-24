<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

    protected $fillable = ['balance', 'user_id', 'type', 'changed_at'];

    public function balanceable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function getBalanceableNameAttribute()
    {
        if ($this->balanceable_type === "App\Models\Loan") {
            return "Peminjaman";
        } else if ($this->balanceable_type === "App\Models\Payment") {
            return "Angsuran";
        } else {
            return "Setoran";
        }
    }
    public function getTypeAttribute($value)
    {
        if ($value === 1) {
            return "Membuat";
        } else if ($value === 2) {
            return "Mengubah";
        } else {
            return "Menghapus";
        }
    }
}
