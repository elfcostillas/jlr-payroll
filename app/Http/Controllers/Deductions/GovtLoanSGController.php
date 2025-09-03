<?php

namespace App\Http\Controllers\Deductions;

use App\Http\Controllers\Controller;
use App\Mappers\Deductions\GovtLoanSGMapper;
use Illuminate\Http\Request;

class GovtLoanSGController extends Controller
{
    //

    private $mapper;

    public function __construct(GovtLoanSGMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.deductions.govt-loans-sg.index');
    }

    public function getEmployees(Request $request)
    {
        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
        ];

        $result = $this->mapper->employeelist($filter);

        return response()->json($result);
    }

    public function getPayrollPeriod()
    {
        $result = $this->mapper->getPayrollPeriod();
        return response()->json($result);
    }
    
    public function getTypes()
    {
        $result = $this->mapper->getTypes();
        return response()->json($result);
    }

    public function  save()
    {

    }

}
