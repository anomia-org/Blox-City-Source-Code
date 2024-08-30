<?php

namespace App\Models\Membership;

use App\Models\Membership\MembershipValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

use App\Models\User;


class Membership extends Model
{
    use HasFactory;

    protected $membershipItems = [
        1 => [48], // bronze items
        2 => [49],  // silver items
        3 => [50, 51], // gold items
    ];

    public $fillable = [
        'user_id', 'membership', 'date', 'length', 'active'
    ];

    protected static function booted()
    {
        static::created(function ($membership) {
            $membership->grantMembershipItems();
            $membership->grantOneTimeBonus();
        });
    }

    public function grantOneTimeBonus()
    {
        $hasHadMembership = Membership::where('id', '!=', $this->id)->userId($this->user_id)->exists();
        if (!$hasHadMembership)
            $this->user()->increment('cash', 100);
    }

    public function grantMembershipItems()
    {
        if (App::environment(['local', 'testing']))
            return;

        $itemsToGrant = $this->membershipItems[$this->membership];
        foreach ($itemsToGrant as $item) {
            // grant item
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function values(): BelongsTo
    {
        return $this->belongsTo(MembershipValue::class, 'membership', 'id');
    }

    public function scopeActive($q)
    {
        return $q->where('active', 1);
    }

    public function scopeUserId($q, $u)
    {
        return $q->where('user_id', $u);
    }
}