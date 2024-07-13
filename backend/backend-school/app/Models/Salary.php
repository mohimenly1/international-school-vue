<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'base_salary',
        'hourly_rate',
        'total_salary',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
