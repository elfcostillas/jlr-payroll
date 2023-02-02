<?php

namespace App\Mappers\Reports;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeaveReportsMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Settings\Location';
    protected $rules = [
    	
    ];

    public function getLeavesfromRange($from,$to)
    {
        $query = "SELECT leave_request_header.biometric_id,employee_name,remarks,leave_type,DATE_FORMAT(leave_date,'%m/%d/%Y') AS mask_leave_date,leave_request_detail.* FROM leave_request_header 
        INNER JOIN leave_request_detail ON leave_request_header.id = leave_request_detail.header_id
        INNER JOIN employee_names_vw ON leave_request_header.biometric_id = employee_names_vw.biometric_id
        WHERE is_canceled = 'N' AND received_by IS NOT NULL
        AND leave_date between '".$from."' and '".$to."'
        ORDER BY leave_date ASC,employee_name;";

        $result = DB::select($query);

        return $result;    
    } 
}