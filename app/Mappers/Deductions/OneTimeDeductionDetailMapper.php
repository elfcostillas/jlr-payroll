<?php

namespace App\Mappers\Deductions;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OneTimeDeductionDetailMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Deductions\OneTimeDeductionDetail';
    protected $rules = [
    	
    ];

    public function list($header_id)
    {
        $result = $this->model->select(DB::raw("deduction_onetime_details.*,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) AS empname"))
                    ->from('deduction_onetime_details')
                    ->join('employees','deduction_onetime_details.biometric_id','=','employees.biometric_id')
                    ->where('header_id',$header_id);

        return $result->get();
    }

   
    /*
    
SELECT deduction_onetime_details.*,CONCAT(IFNULL(lastname,''),IFNULL(firstname,''),IFNULL(suffixname,'')) AS empname FROM deduction_onetime_details 
INNER JOIN employees ON deduction_onetime_details.biometric_id = employees.biometric_id;
*/

}
