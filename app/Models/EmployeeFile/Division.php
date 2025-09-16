<?php

namespace App\Models\EmployeeFile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $table = 'divisions';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'div_code',
        'div_name'
    ];
}
