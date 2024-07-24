<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'department_id','name', 'teacher_type_id', 'national_number', 'phone_number',
        'phone_number_two', 'address', 'photos', 'years_of_experience', 'is_teacher', 'pin'
    ];



    public function logs()
    {
        return $this->hasMany(LogCheckInProcess::class);
    }

    public function days()
    {
        return $this->hasMany(Day::class);
    }

    public function teacherType()
    {
        return $this->belongsTo(TeacherType::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function salary()
    {
        return $this->hasOne(Salary::class);
    }
    public function calculateMonthlyAbsences(Employee $employee)
    {
        $currentMonth = Carbon::now()->month;
        $logs = LogCheckInProcess::where('employee_id', $employee->id)
            ->whereMonth('check_in_time', $currentMonth)
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->check_in_time)->format('Y-m-d');
            });

        $daysInMonth = Carbon::now()->daysInMonth;

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = Carbon::now()->format('Y-m-') . str_pad($i, 2, '0', STR_PAD_LEFT);
            if (!isset($logs[$date])) {
                Day::create([
                    'employee_id' => $employee->id,
                    'date' => $date,
                    'absence' => true,
                ]);
            }
        }
    }
}