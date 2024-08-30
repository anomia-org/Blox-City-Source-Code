<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Model;

class StripeCustomer extends Model
{
    public $primaryKey = 'user_id';
    public $timestamps = false;

    public $fillable = [
        'stripe_id'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
