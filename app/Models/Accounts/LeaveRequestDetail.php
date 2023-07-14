<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequestDetail extends Model
{
    use HasFactory;

    protected $table = 'leave_request_detail';
    protected $primaryKey = 'line_id';
    public $timestamps = false;

    protected $fillable = [
        'header_id',
        'leave_date',
        'is_canceled',
        'time_from',
        'time_to',
        'days',
        'with_pay',
        'without_pay',
        'remarks'
    ];

}
