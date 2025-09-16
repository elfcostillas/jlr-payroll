<?php

namespace App\Models\Timekeeping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $table = 'holidays';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'holiday_date',
        'holiday_remarks',
        'holiday_type',
    ];

}
