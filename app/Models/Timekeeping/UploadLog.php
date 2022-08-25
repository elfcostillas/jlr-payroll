<?php

namespace App\Models\Timekeeping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadLog extends Model
{
    use HasFactory;

    protected $table = 'edtr_raw';
    protected $primaryKey = 'line_id';
    public $timestamps = false;

    protected $fillable = [
        'punch_date',
        'punch_time',
        'biometric_id'
    ];
}
