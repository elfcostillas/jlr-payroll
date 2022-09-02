<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultSchedule extends Model
{
    use HasFactory;

    protected $table = 'work_schedules_default';
    protected $primaryKey = 'line_id';
    public $timestamps = false;

    protected $fillable = [
        'dept_id',
        'schedule_id'
    ];
}
