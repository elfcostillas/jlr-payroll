<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    
    protected $table = 'activity_log';

    protected $primaryKey = 'log_id';

    protected $fillable = [
        'log_timestamp',
        'log_module',
        'log_user',
        'log_action',
        'log_data',
        'record_id'
    ];

    public $timestamps = false;
}
