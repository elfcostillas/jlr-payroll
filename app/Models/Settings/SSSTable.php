<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SSSTable extends Model
{
    use HasFactory;

    protected $table = 'hris_sss_table_2025';
    protected $primaryKey = 'line_id';
    public $timestamps = false;

    protected $fillable = [
        'range1',
        'range2',
        'salary_credit',
        'ec',
        'er_share',
        'ee_share',
        'total_share',
        'mpf',
        'total_msalarycredit',
        'mpf_er',
        'mpf_ee',
    ];
}
