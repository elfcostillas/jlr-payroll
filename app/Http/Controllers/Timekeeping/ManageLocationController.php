<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\TimeKeepingMapper\ManageLocationMapper;
use Barryvdh\DomPDF\Facade\Pdf;

class ManageLocationController extends Controller
{
    //
    private $mapper;

    function __construct(ManageLocationMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.timekeeping.manage-location.index');
    }

    public function list()
    {
        $result = $this->mapper->listPeriod();

        return response()->json($result);
    }

    public function employeeList(Request $request)
    {

        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
            'search' => $request->input('search'),
        ];

        // $result = $this->mapper->list($filter);
        // dd($request->period_id);
        $result = $this->mapper->employeeList($request->period_id,$filter);

        return response()->json($result);
    }

    public function update(Request $request)
    {
        $arr = [
            'id' => $request->id ,
            'biometric_id' => $request->biometric_id ,
            'period_id' => $request->period_id ,
            'loc_id' => $request->loc_id,
            
        ];

        $result = $this->mapper->updateValid($arr);

        return response()->json($result);
    }

    public function print(Request $request)
    {
        $data = $this->mapper->ListEmployeeByLocation($request->period_id);

        $pdf = PDF::loadView('app.timekeeping.manage-location.print',[
            'data' => $data
            ])
            ->setPaper('letter','portrait');

      
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(510, 800, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
       
        return $pdf->stream('EmployeeListByLocation.pdf'); 
    }
}
