<?php

namespace App\Models\PayrollTransaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnpostedPayrollRegisterWeekly extends Model
{
    use HasFactory;

    protected $table = 'payrollregister_unposted_weekly';
    protected $primaryKey = 'line_id';
    public $timestamps = false;

    protected $fillable = [
        'biometric_id',
        'period_id',
        'days',
        'ot',
        'basic_pay',
        'earnings',
        'gross_pay',
        'deductions',
        'net_pay',
    ];

}
