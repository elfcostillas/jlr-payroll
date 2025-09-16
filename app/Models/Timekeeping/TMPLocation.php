<?php

namespace App\Models\Timekeeping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TMPLocation extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'weekly_tmp_locations';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'biometric_id',
        'loc_id',
        'period_id'

    ];
}

