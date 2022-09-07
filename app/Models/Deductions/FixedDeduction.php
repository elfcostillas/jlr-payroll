<?php

namespace App\Models\Deductions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedDeduction extends Model
{
    use HasFactory;

    protected $table = 'deduction_fixed';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'period_id',
        'biometric_id',
        'deduction_type',
        'remarks',
        'amount',
        'is_stopped',
        'encoded_by',
        'encoded_on'
    ];
}
