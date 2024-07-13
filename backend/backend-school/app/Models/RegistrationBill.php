<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'due_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    const STATUSES = [
        'paid',
        'unpaid',
    ];
}
