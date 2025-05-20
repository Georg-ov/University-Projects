<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    use HasFactory;

    protected $table = 'credit_cards';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
        'user_id',
        'card_number',
        'cardholder_name',
        'expiration_month',
        'expiration_year',
        'cvv',
    ];

    /**
     * Get the user that owns the credit card.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $hidden = [
        'card_number',
        'cvv',
    ];

    protected $casts = [
        'expiration_month' => 'integer',
        'expiration_year' => 'integer',
    ];
}