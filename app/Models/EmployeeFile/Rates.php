<?php

namespace App\Models\EmployeeFile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rates extends Model
{
    use HasFactory;

    protected $table = 'employee_rates';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'emp_id',
        'rates',
        'date_added'
    ];
}
