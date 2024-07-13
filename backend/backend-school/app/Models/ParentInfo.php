<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentInfo extends Model
{
    protected $table = 'parents';
    use HasFactory;


    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone_number_one',
        'phone_number_two',
        'city',
        'address',
        'id_image',
        'passport_image',
        'images_info'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }
}
