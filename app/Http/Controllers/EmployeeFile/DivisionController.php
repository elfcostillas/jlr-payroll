<?php

namespace App\Http\Controllers\EmployeeFile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\EmployeeFileMapper\DivisionMapper;

class DivisionController extends Controller
{
    //
    protected $mapper;

    public function __construct(DivisionMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.employee-file.division-department.index');
    }

    public function list(Request $request)
    {
        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
        ];

        $result = $this->mapper->list($filter);

        return response()->json($result);
    }

    public function create(Request $request)
    {
        $result = $this->mapper->insertValid($request->all());

        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }

    public function update(Request $request)
    {
        $result = $this->mapper->updateValid($request->all());

        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }

    public function getDivisions(Request $request)
    {
        $result = $this->mapper->getDivisions();
        return response()->json($result);
    }


}
