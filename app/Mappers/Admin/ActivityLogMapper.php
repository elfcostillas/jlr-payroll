<?php

namespace App\Mappers\Admin;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ActivityLogMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Admin\ActivityLog';
    protected $rules = [
    	
    ];


}
