<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\TimeKeepingMapper\FTPMapper;

class FTPController extends Controller
{
    //
    private $mapper;

    public function __construct(FTPMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.timekeeping.ftp.index');
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

    public function create(Request $request){

    }

    public function update(Request $request)
    {
        
    }




}
