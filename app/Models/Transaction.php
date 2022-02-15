<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    const AUTH_MESSAGE_SUCCESS = 'Autorizado';

    protected $fillable =
    [
        'payer_id',
        'amount',
        'payee_id',
        'uuid',
        'is_authorized'
    ];
}
