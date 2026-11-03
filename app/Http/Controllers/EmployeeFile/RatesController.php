<?php

namespace App\Http\Controllers\EmployeeFile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\EmployeeFileMapper\RatesMapper;

class RatesController extends Controller
{
    //
    private $mapper;

    public function __construct(RatesMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function getRates(Request $request)
    {
        $result = $this->mapper->get_rates($request->id);

        return response()->json($result);
    }

    public function createRates(Request $request)
    {
        $request->merge([
            'added_by' => auth()->user()->id,
        ]);
        
        $result = $this->mapper->insertValid($request->all());
        
        return response()->json($result); 

    }
}
