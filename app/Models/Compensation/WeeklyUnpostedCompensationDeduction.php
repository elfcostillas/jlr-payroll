<?php

namespace App\Models\Compensation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyUnpostedCompensationDeduction extends Model
{
    use HasFactory;

    protected $table = 'unposted_weekly_compensation';
    protected $primaryKey = 'line_id';
    public $timestamps = false;

    protected $fillable = [
        'line_id',
        'period_id',
        'earnings',
        'deductions',
        'biometric_id',
        'retro_pay'
    ];
}
