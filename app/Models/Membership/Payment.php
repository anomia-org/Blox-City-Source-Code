<?php

namespace App\Models\Membership;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $real_email;

    public $fillable = [
        'user_id', 'gross_in_cents', 'email', 'receipt', 'product', 'billing_product_id'
    ];

    public function getGrossAttribute()
    {
        return $this->gross_in_cents / 100;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function billingProduct(): BelongsTo
    {
        return $this->belongsTo(BillingProduct::class);
    }

    public function scopeUserId($q, $u)
    {
        return $q->where('user_id', $u);
    }

    public function scopeReceipt($q, $r)
    {
        return $q->where('receipt', $r);
    }

    public function getEmailAttribute($value)
    {
        $this->real_email = $value;
        return preg_replace('/(?<=...).(?=.*@)/u', '*', $value);
    }

    public function getRealEmailAttribute()
    {
        $getEmail = $this->email;
        return $this->real_email;
    }
}
