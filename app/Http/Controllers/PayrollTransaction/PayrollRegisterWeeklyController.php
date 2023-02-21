<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\EmployeeFileMapper\EmployeeMapper;

class PayrollRegisterWeeklyController extends Controller
{
    //

    private $employee;

    public function __construct(EmployeeMapper $employee)
    {
        $this->employee = $employee;
    }

    public function index()
    {
        return view('app.payroll-transaction.payroll-register-weekly.index');
    }

    public function getUnpostedPeriod()
    {
        $user = Auth::user();
        if($user->biometric_id!="" && $user->biometric_id!=0 && $user->biometric_id != null){
            $position = $this->employee->getPosition($user->biometric_id);

            $result = $this->unposted->unpostedPeriodList('semi',$position);

            return response()->json($result);
        }
    }

    public function compute(Request $request)
    {

    }
}
