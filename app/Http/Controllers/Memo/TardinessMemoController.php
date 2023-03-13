<?php

namespace App\Http\Controllers\Memo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\Memo\TardinessMemoMapper;
use Barryvdh\DomPDF\Facade\Pdf;

class TardinessMemoController extends Controller
{
    //
    private $mapper;

    public function __construct(TardinessMemoMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.memo.tardiness-memo.index');
    }

    public function list(Request $request)
    {
        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
            'search' => $request->input('search'),
        ];

        $result = $this->mapper->list($filter);

        return response()->json($result);
    }

    public function save(Request $request)
    {
        $data = json_decode($request->data);

        $data_arr = (array) $data;

        if($data_arr['id']=="" || $data_arr['id']==0  ){
            $result = $this->mapper->insertValid($data_arr);
        }else{
            $result = $this->mapper->updateValid($data_arr);
        }
            
        return response()->json($result);
    }

    public function readMemo(Request $request)
    {
        $result = $this->mapper->readMemo($request->id);

        return response()->json($result);
    }

    public function getNames(Request $request)
    {
        $result = $this->mapper->getNames();

        return response()->json($result);
    }

    public function print(Request $request)
    {

        // $period_id = $request->period_id;
        // $result = $this->mapper->getEmployeeForPrint($period_id,'semi');
        $memo = $this->mapper->find($request->id);
        
        $start = date('Y-m-d',strtotime($memo->memo_year.'-'.$memo->memo_month.'-01'));
        
        $filter = array(
            'from' => $start,
            'to' => date('Y-m-t',strtotime($start)),
        );

        $details = $this->mapper->getLates($memo->biometric_id,$filter);
        $pdf = PDF::loadView('app.memo.tardiness-memo.print',['data' => $memo,'details' => $details])->setPaper('letter','portrait');

        //$pdf->output();
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        
        //$canvas = $dom_pdf ->get_canvas();
        //$canvas->page_text(510, 800, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        //return $pdf->download('JLR-DTR-Print.pdf'); 
        return $pdf->stream('TardinessMemo.pdf'); 

    }

    public function getYear()
    {
        $result = $this->mapper->getYear();

        return response()->json($result);
    }
}
