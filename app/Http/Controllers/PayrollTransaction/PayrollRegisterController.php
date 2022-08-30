<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\PayrollTransaction\UnpostedPayrollRegisterMapper;
use App\Mappers\PayrollTransaction\PostedPayrollRegisterMapper;


class PayrollRegisterController extends Controller
{
    //
    private $unposted;
    private $posted;

    public function __construct(UnpostedPayrollRegisterMapper $unposted,PostedPayrollRegisterMapper $posted)
    {
        $this->unposted = $unposted;
        $this->posted = $posted;
    }

    public function index()
    {
        return view('app.payroll-transaction.payroll-register.index');
    }

    public function getUnpostedPeriod()
    {
        $result = $this->unposted->unpostedPeriodList('semi');

        return response()->json($result);
    }
}
