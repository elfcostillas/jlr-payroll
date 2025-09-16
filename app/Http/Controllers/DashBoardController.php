<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mappers\EmployeeFileMapper\EmployeeMapper;
use App\Mappers\Admin\ActivityLogMapper;
use Illuminate\Support\Facades\Auth;

class DashBoardController extends Controller
{
    //
    private $mapper;

    public function __construct(EmployeeMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        $result = $this->mapper->employeeCount();
        //dd($result);
        return view('app.dashboard',['count'=>$result]);
    }
}
