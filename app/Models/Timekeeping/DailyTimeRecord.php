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
        'ndays',
        'restday_hrs',
        'restday_ot',
        'restday_nd',
        'reghol_pay',
        'reghol_hrs',
        'reghol_ot',
        'reghol_rd',
        'reghol_nd',
        'sphol_pay',
        'sphol_hrs',
        'sphol_ot',
        'sphol_rd',
        'sphol_nd',
        'dblhol_pay',
        'dblhol_hrs',
        'dblhol_ot',
        'dblhol_rd',
        'dblhol_nd',
        'dblhol_rdot',
        'sphol_rdot',
        'reghol_rdot',

    ];
}
