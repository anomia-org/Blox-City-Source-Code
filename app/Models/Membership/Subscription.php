<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id', 'sub_profile_id', 'expected_bill', 'product', 'active'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function scopeUserId($q, $u) {
        return $q->where('user_id', $u);
    }

    public function scopeSubProfile($q, $s) {
        return $q->where('sub_profile_id', $s);
    }

    public function scopeActive($q) {
        return $q->where('active', 1);
    }
}
