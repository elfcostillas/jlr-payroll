<?php

namespace App\Models\Deductions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentDeductionSG extends Model
{
    use HasFactory;

    protected $table = 'deduction_installments_sg';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'period_id',
        'biometric_id',
        'deduction_type',
        'remarks',
        'total_amount',
        'terms',
        'ammortization',
        'is_stopped',
        'deduction_sched',
        'encoded_by',
        'encoded_on'
    ];
}
