<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'amount',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBalance()
    {
        return $this->amount ?: 0;
    }

    public function addBalance($amount)
    {
        $this->amount += $amount;
        $this->save();
    }

    public function subtractBalance($amount)
    {
        $this->amount -= $amount;
        $this->save();
    }
}
