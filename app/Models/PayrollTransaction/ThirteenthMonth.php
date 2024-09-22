<?php

namespace App\Models\PayrollTransaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThirteenthMonth extends Model
{
    use HasFactory;

    protected $table = 'thirteenth_month';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'pyear',
        'biometric_id',
        'net_pay',
        
    ];

}
