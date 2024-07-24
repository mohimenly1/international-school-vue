<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'class_id'];

    public function class()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(TeacherType::class, 'section_subject', 'section_id', 'subject_id');
    }
}
