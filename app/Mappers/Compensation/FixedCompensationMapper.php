<?php

namespace App\Mappers\Compensation;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FixedCompensationMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Admin\UserRights';
    protected $rules = [
    	
    ];

}