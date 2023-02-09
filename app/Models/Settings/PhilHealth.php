<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhilHealth extends Model
{
    use HasFactory;

    protected $table = 'philhealth';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'rate',
       
    ];
}
