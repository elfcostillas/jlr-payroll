<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherIncomeWeekly extends Model
{
    use HasFactory;

    protected $table = 'compensation_other_weekly';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'description',
        'amount'
    ];
}
