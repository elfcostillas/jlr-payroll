<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanType extends Model
{
    use HasFactory;

    protected $table = 'loan_types';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'loan_code',
        'description',
        'sched',
    ];
}
