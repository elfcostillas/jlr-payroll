<?php

namespace App\Models\Timekeeping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FTP extends Model
{
    use HasFactory;

    protected $table = 'ftp';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'biometric_id',
        'ftp_date',
        'ftp_time',
        'ftp_state',
        'ftp_remarks',
        'encoded_by',
        'encoded_on',
        'hr_received',
        'hr_received_by',
        'hr_received_on'
    ];


}
