<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'default_commission_rate',
        'payout_bank_name',
        'payout_account_name',
        'payout_account_iban',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
