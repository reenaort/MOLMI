<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vessels extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['cat_id', 'subcat_id', 'vessel_name', 'status'];
}
