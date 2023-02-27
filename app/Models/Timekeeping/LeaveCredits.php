<?php

namespace App\Models\Timekeeping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveCredits extends Model
{
    use HasFactory;
    use HasFactory;

    protected $table = 'leave_credits';
    protected $primaryKey = 'line_id';
    public $timestamps = false;

    protected $fillable = [
        'fy_year',
        'biometric_id',
        'vacation_leave',
        'sick_leave',
        'summer_vacation_leave',
        'paternity_leave',
    ];
}
