<?php

namespace App\Models\Compensation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherCompensationDetail extends Model
{
    use HasFactory;

    protected $table = 'compensation_other_details';
    protected $primaryKey = 'line_id';
    public $timestamps = false;

    protected $fillable = [
        'header_id',
        'biometric_id',
        'total_amount',
        'remarks'
    ];

}
