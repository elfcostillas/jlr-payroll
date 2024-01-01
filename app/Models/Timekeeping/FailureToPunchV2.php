<?php

namespace App\Models\Timekeeping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailureToPunchV2 extends Model
{
    use HasFactory;


    protected $table = 'ftp_hr';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'biometric_id',
        'ftp_date',
        'ftp_type',
        'ftp_reason',
        'time_in',
        'time_out',
        'ot_in',
        'ot_out',
        'ftp_status',
        
        'created_by',
        'created_on',
        
        
    ];

}
