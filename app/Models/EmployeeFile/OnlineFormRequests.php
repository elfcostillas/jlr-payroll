<?php

namespace App\Models\EmployeeFile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineFormRequests extends Model
{
    use HasFactory;

    protected $table = 'onlineform_users';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password'
    ];
}