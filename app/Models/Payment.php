<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function balance()
    {
        return $this->morphMany('App\Models\Balance', 'balanceable');
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
}
