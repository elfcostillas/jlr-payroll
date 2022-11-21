<?php

namespace App\Models\PayrollTransaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnpostedPayrollRegister extends Model
{
    use HasFactory;

    protected $table = 'payrollregister_unposted_s';
    protected $primaryKey = 'line_id';
    public $timestamps = false;

    protected $fillable = [
        'biometric_id',
        'period_id',
        'basic_rate',
        'is_daily',
    ];


}


