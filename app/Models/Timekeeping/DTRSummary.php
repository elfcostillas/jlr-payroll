<?php

namespace App\Models\Timekeeping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DTRSummary extends Model
{
    use HasFactory;

    
    protected $table = 'edtr_totals';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'biometric_id',
        'period_id',
        'late_eq',
        'under_time',
        'over_time',
        'night_diff',
        'night_diff_ot',
        'schedule_id',
        'ndays',
        'vl_wp',
        'vl_wop',
        'sl_wp',
        'sl_wop',
        'bl',
        'ot_in',
        'ot_out',
        'restday_hrs',
        'restday_nd',
        'restday_ot',
        'restday_nd',
        'restday_ndot',
        'reghol_pay',
        'reghol_hrs',
        'reghol_ot',
        'reghol_rd',
        'reghol_rdnd',
        'reghol_rdot',
        'reghol_nd',
        'reghol_ndot',
        'sphol_pay',
        'sphol_hrs',
        'sphol_ot',
        'sphol_rd',
        'sphol_rdnd',
        'sphol_rdot',
        'sphol_nd',
        'sphol_ndot',
        'dblhol_pay',
        'dblhol_hrs',
        'dblhol_ot',
        'dblhol_rd',
        'dblhol_rdnd',
        'dblhol_rdot',
        'dblhol_nd',
        'dblhol_ndot',
        'reghol_rdndot',
        'sphol_rdndot',
        'dblhol_rdndot',
        'dblsphol_pay',
        'dblsphol_hrs',
        'dblsphol_ot',
        'dblsphol_nd',
        'dblsphol_rd',
        'dblsphol_rdot',
        'dblsphol_ndot',
        'dblsphol_rdnd',
        'dblsphol_rdndot',
        'awol'

    ];
}
