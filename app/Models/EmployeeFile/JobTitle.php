<?php

namespace App\Models\EmployeeFile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    use HasFactory;

    protected $table = 'job_titles';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'dept_id',
        'job_title_code',
        'job_title_name'
    ];
}