<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'exam_section_subject')->withPivot('subject_id', 'exam_date');
    }
}
