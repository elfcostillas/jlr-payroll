<?php

namespace App\Models\Compensation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherCompensationHeader extends Model
{
    use HasFactory;

    protected $table = 'compensation_other_headers';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'period_id',
        'compensation_type',
        'remarks',
        'encoded_by',
        'encoded_on',
        'doc_status'
    ];
}
