<?php

namespace App\Models\Timekeeping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualDTRDetail extends Model
{
    use HasFactory;

    protected $table = 'manual_dtr_details';
    protected $primaryKey = 'line_id';
    public $timestamps = false;

    protected $fillable = [
        'header_id',
        'biometric_id',
        'dtr_date',
        'time_in',
        'time_out',
        'time_in2',
        'time_out2',
        'overtime_in',
        'overtime_out',
        'overtime_hrs',
        'reg_hrs',
        'reg_day',
        'rd_hrs',
        'rd_ot',
        'sh_hrs',
        'sh_ot',
        'lh_hrs',
        'lh_ot',
        'remarks',
    ];
}
