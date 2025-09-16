<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\SettingsMapper\SSSTableMapper;

class SSSTableController extends Controller
{
    //
    private $mapper;

    public function __construct(SSSTableMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.settings.sss-table.index');
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

    public function save(Request $request)
    {
        $data = $request->models;
        foreach($data as $row)
        {
            if($row['line_id']==''||$row['line_id']==null){
                $result = $this->mapper->insertValid($row);
            }else{
                $result = $this->mapper->updateValid($row);
            }
           
            if(is_object($result)){
                return response()->json($result)->setStatusCode(500, 'Error');
            }
        } 
        return response()->json(true);
    }

    // public function update(Request $request)
    // {

    // }
}
