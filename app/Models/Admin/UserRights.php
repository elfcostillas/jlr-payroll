<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRights extends Model
{
    use HasFactory;

    protected $table = 'user_rights';

    protected $primaryKey = 'line_id';

    protected $fillable = [
      'user_id',
      'sub_menu_id'
    ];

    public $timestamps = false;
}
