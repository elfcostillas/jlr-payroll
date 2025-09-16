<?php

namespace App\Models\Memo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TardinessMemo extends Model
{
    use HasFactory;

    protected $table = 'tardiness_memo';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'biometric_id',
        'memo_to',
        'memo_from',
        'memo_date',
        'memo_subject',
        'memo_upper_body',
        'memo_lower_body',
        'prep_by_text',
        'prep_by_name',
        'prep_by_position',
        'noted_by_text',
        'noted_by_name',
        'noted_by_position',
        'noted_by_text_dept',
        'noted_by_name_dept',
        'noted_by_position_dept',
        'memo_month',
        'memo_year'
    ];


}
