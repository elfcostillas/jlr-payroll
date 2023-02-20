<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\TimeKeepingMapper\ManualDTRHeaderMapper;
use App\Mappers\TimeKeepingMapper\ManualDTRDetailMapper;
use Illuminate\Support\Facades\Auth;
use Carbon\CarbonPeriod;
use Barryvdh\DomPDF\Facade\Pdf;

class ManualDTRController extends Controller
{
    //
    private $header;
    private $detail;

    public function __construct(ManualDTRHeaderMapper $header,ManualDTRDetailMapper $detail)
    {
        $this->header = $header;
        $this->detail = $detail;
    }

    public function index()
    {
        return view('app.timekeeping.manual-dtr.index');
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

        $result = $this->header->list($filter);

        return response()->json($result);
    }

    public function getEmployees(Request $request)
    {
        $result = $this->header->emplist();
        return response()->json($result);
    }

    public function save(Request $request)
    {   
        $user = Auth::user();

        $data = json_decode($request->data);
        $blank = [];

        $data_arr = (array) $data;

        // array_push($data_arr,[
        //     'encoded_by' => $user->id,
        //     'encoded_on' => now()
        // ]);

        if($data_arr['id']==null)
        {
            $data_arr['encoded_by'] = $user->id;
            $data_arr['encoded_on'] = now();
    
            // $first = $this->header->validateDate($data_arr['date_from'],$data_arr['biometric_id']);
            // if($first){
            //     return response()->json(['Error' => 'Date from already used.'])->setStatusCode(500, 'Error');
            // }
    
            // $second = $this->header->validateDate($data_arr['date_to'],$data_arr['biometric_id']);
            // if($second){
            //     return response()->json(['Error' => 'Date to already used.'])->setStatusCode(500, 'Error');
            // }
    
            $result = $this->header->insertValid($data_arr);
    
            if(is_object($result)){
                return response()->json($result)->setStatusCode(500, 'Error');
            }
        }else {
            if($data_arr['encoded_by']!=null && $data_arr['encoded_by'] != $user->id  ){
                return response()->json(['Error' => 'Can\'t edit DTR encoded by other user.'])->setStatusCode(500, 'Error');
               
            }
            
            $result = $this->header->updateValid($data_arr);
        }
        
        $range = $this->header->getPeriodByID($data_arr['period_id']);
        //dd($range['date_from'],$range['date_to']);

        //$dates = new CarbonPeriod($data_arr['date_from'],'1 day',$data_arr['date_to']);
        $dates = new CarbonPeriod($range['date_from'],'1 day',$range['date_to']);

        foreach($dates as $date){
            array_push($blank,[
                'header_id' => $result,
                'biometric_id' => $data_arr['biometric_id'],
                'dtr_date' => $date,
            ]);
        }

        $this->detail->insertBatch($blank);

        return response()->json($result);
    }

    public function details(Request $request)
    {
        $result = $this->detail->listDetail($request->id);

        return response()->json($result);
    }

    public function detailUpdate(Request $request)
    {
        $user = Auth::user();
        $header = $this->header->header($request->header_id);

        if($header->encoded_by != $user->id  ){
            return response()->json(['Error' => 'Can\'t edit DTR encoded by other user.'])->setStatusCode(500, 'Error');
           
        }
        $result = $this->detail->updateValid($request->all());
        return response()->json($result);
    }

    public function header(Request $request)
    {
        $result = $this->header->header($request->id);

        return response()->json($result);
    }

    public function print(Request $request){
        $header = $this->header->printHeader($request->id);
        $detail = $this->detail->listDetail($request->id);

        $pdf = PDF::loadView('app.timekeeping.manual-dtr.print',['header' => $header,'detail' => $detail])->setPaper('letter','landscape');

        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(700, 570, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->stream('JLR-DTR-Print.pdf'); 
       
    }

    public function weeklyPeriod(Request $request)
    {
        $result = $this->header->openWeeklyPeriod();

        return response()->json($result);
    }
}
