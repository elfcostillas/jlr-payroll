<?php

namespace App\Models\EmployeeFile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'dept_div_id',
        'dept_code',
        'dept_name'
    ];
}
