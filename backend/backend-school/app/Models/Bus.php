<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'model', 'capacity'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
