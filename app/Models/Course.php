<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_name',
        'course_code',
        'course_by',
        'course_logo',
        'training_center',
        'duration',
        'course_type',
        'course_followed_by',
        'course_repeated',
        'course_intervals',
        'online_priority',
        'offline_priority',
        'elearning_priority',
        'course_comments',
        'categories',
        'subcategories',
        'vessels',
        'departments',
        'ranks',
        'rank_priorities',
        'status'
    ];
}