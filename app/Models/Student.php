<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable =[
        'first_name',
        'middle_name',
        'last_name',
        'student_id',
        'address_1',
        'address_2',
    ];


}
