<?php

namespace App\Mappers\PayrollTransaction;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class UnpostedPayrollRegisterWeeklyMapper extends AbstractMapper {
    
    protected $modelClassName = 'App\Models\PayrollTransaction\UnpostedPayrollRegisterWeekly';
    protected $rules = [
    	
    ];
}