<?php

namespace App\Models\Timekeeping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualDTRHeader extends Model
{
    use HasFactory;

    protected $table = 'manual_dtr';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'biometric_id',
        'remarks',
        'encoded_by',
        'encoded_on',
        // 'date_from',
        // 'date_to',
        'period_id'
    ];
}
