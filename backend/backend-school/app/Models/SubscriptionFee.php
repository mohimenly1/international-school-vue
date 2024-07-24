<?php

// app/Models/SubscriptionFee.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionFee extends Model
{
    use HasFactory;

    protected $fillable = ['category', 'sub_category', 'amount'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subscription_fee')
                    ->withPivot('amount')
                    ->withTimestamps();
    }
}
