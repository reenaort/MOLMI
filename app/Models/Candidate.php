<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'candidate_photo',
        'candidate_name',
        'contact_no',
        'email',
        'dob',
        'dep_id',
        'rank_id',
        'coc_no',
        'indos_no',
        'location',
        'passport_no',
        'type',
        'till_date',
        'passport_file'
    ];
}
