<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequestHeader extends Model
{
    use HasFactory;

    protected $table = 'leave_request_header';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'biometric_id', // employee bio
        'encoded_by', // user encoder
        'encoded_on', //encoded_on
        //'request_apprv',  //date time appoved
        'request_date', // date posted
        'leave_type', //leave type
        'with_pay', 
        'date_from',
        'date_to',
        'remarks',
        'acknowledge_status', // approved or denied status
        'acknowledge_time', // time approved or denied
        'acknowledge_by', // immediate_heade
        'received_by', // hr employee
        'received_time',
        'dept_id',
        'division_id',
        'job_title_id',
        'document_status',
    ];
}
