<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class courseenrollmentexpense extends Model
{
    protected $table = 'course_enrollment_expenses';
    use HasFactory;
    protected $fillable = [
        'can_id',
        'course_id',
        'status',
        'expenditure_amount',
        'refund_amount'
    ];
}
