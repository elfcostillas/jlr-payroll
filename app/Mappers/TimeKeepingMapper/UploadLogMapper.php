<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UploadLogMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\UploadLog';
    
    protected $rules = [
    	
    ];


}
