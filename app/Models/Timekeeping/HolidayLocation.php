<?php

namespace App\Models\Timekeeping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayLocation extends Model
{
    use HasFactory;

    protected $table = 'holiday_location';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'holiday_id',
        'location_id'
    ];
}
