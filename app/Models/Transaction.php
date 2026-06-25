<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'user_id',
        'invoice_number',
        'buyer_name',
        'buyer_phone',
        'recipient_name',
        'greeting_card_text',
        'greeting_card_fee',
        'shipping_address',
        'total_amount',
        'status',
        'type',
        'source'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
