<?php

namespace App\Models\Timekeeping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTimeRecord extends Model
{
    use HasFactory;

    protected $table = 'edtr';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'biometric_id',
        'dtr_date',
        'time_in',
        'time_out',
        'late',
        'late_eq',
        'under_time',
        'over_time',
        'night_diff',
        'schedule_id',
    ];
}
