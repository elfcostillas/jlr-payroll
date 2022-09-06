<?php

namespace App\Models\Deductions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OneTimeDeductionDetail extends Model
{
    use HasFactory;

    protected $table = 'deduction_onetime_details';
    protected $primaryKey = 'line_id';
    public $timestamps = false;

    protected $fillable = [
        'header_id',
        'biometric_id',
        'amount',
    ];
    
}
