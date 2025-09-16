<?php

namespace App\Models\EmployeeFile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeWeekly extends Model
{
    use HasFactory;

    
    protected $table = 'employees_weekly';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'firstname',
        'lastname',
        'middlename',
        'suffixname',
        'biometric_id',
        'primary_addr',
        'secondary_addr',
        'remarks',
        'sss_no',
        'deduct_sss',
        'tin_no',
        'phic_no',
        'deduct_phic',
        'hdmf_no',
        'deduct_hdmf',
        'hdmf_contri',
        'civil_status',
        'gender',
        'birthdate',
        'employee_stat',
        'bank_acct',
        'basic_salary',
        'is_daily',
        'exit_status',
        'exit_date',
        'contact_no',
        'division_id',
        'dept_id',
        'location_id',
        'pay_type',
        'date_hired',
        'emp_level',
        'job_title_id',
        'daily_allowance',
        'monthly_allowance',
        'sched_mtwtf',
        'sched_sat'

    ];

}
