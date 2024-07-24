<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherType extends Model
{
    use HasFactory;
    protected $table = 'teachers_type';
    protected $fillable = [
        'type',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'section_subject', 'subject_id', 'section_id');
    }

}
