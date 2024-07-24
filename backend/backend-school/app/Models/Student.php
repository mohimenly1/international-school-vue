<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'parent_id',
        'class_id',
        'bus_id',
        'date_of_birth',
        'address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(ParentInfo::class, 'parent_id');
    }

    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }
    public function subscriptionFee()
    {
        return $this->belongsTo(SubscriptionFee::class);
    }

    public function subscriptionFees()
    {
        return $this->belongsToMany(SubscriptionFee::class, 'student_subscription_fee')
                    ->withPivot('amount')
                    ->withTimestamps();
    }
}
