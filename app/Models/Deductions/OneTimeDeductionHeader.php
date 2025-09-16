<?php

namespace App\Models\Deductions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OneTimeDeductionHeader extends Model
{
    use HasFactory;

    protected $table = 'deduction_onetime_headers';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'period_id',
        'deduction_type',
        'remarks',
        'encoded_by',
        'encoded_on',
        'doc_status'
    ];
}
