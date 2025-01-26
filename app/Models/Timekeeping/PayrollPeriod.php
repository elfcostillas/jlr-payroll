<?php

namespace App\Models\Timekeeping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollPeriod extends Model
{
    use HasFactory;

    protected $table = 'payroll_period';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'date_from',
        'date_to',
        'date_release',
        'man_hours',
        'inProgress',
        'pyear',
        'cut_off',
    ];

    //protected $connection = 'sqlite';
}
