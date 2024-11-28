<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingCenter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['center_name', 'center_location', 'center_type'];
}
