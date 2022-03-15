<?php

namespace Modules\People\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_name',
        'previous_balance',
        'paid_balance',
        'remaining_balance'

    ];

    protected static function newFactory()
    {
        return \Modules\People\Database\factories\CustomerPaymentFactory::new();
    }
}
