<?php

namespace App\Http\Controllers\Deductions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\Deductions\InstallmentDeductionSGMapper;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InstallmentDeductionSGController extends Controller
{
    //
        private $mapper;

    public function __construct(InstallmentDeductionSGMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.deductions.installment-deductions-sg.index');
    }

    public function save(Request $request) {

        $data = json_decode($request->data);

        $data_arr = (array) $data;

        if($data_arr['id']==null || $data_arr['id']=='null'){
            $data_arr['encoded_by'] = Auth::user()->id;
            $data_arr['encoded_on'] = now();
           
            $result = $this->mapper->insertValid($data_arr);
        }else{
            $result = $this->mapper->updateValid($data_arr);
        }

        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }

    public function list(Request $request)
    {
        $biometric_id = $request->biometric_id;
        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
        ];

        $result = $this->mapper->list($biometric_id,$filter);

        return response()->json($result);
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

    public function getDeductSched()
    {
        $result = $this->mapper->getDeductSched();

        return response()->json($result);
    }

    public function readHeader(Request $request)
    {
        $result = $this->mapper->header($request->id);

        return response()->json($result);
    }

    public function getPayrollPeriod(Request $request)
    {
        $result = $this->mapper->getPayrollPeriod();
        return response()->json($result);

    }

    public function getTypes(Request $request)
    {
        $result = $this->mapper->getTypes();
        return response()->json($result);

    }

    public function ammortization(Request $request)
    {
        // dd($request->bio_id);
        $id = $request->id;

        $result = $this->mapper->get_ammortization($id);
        return response()->json($result);
    }
    
    public function print(Request $request)
    {
        $id = $request->id;

        $header = $this->mapper->detailedHeader($id);
        $details = $this->mapper->get_ammortization($id);


        $pdf = PDF::loadView('app.deductions.installment-deductions-sg.print',['header' => $header,'details' => $details ])->setPaper('letter','portrait');
        
        $pdf->output();

        $dom_pdf = $pdf->getDomPDF();
    
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(510, 762, "Page {PAGE_NUM} of {PAGE_COUNT} ", null, 10, array(0, 0, 0));

        return $pdf->stream('InstallmentLedger.pdf'); 
    }
}

/*

    "remarks" => "Lost Hard Hat"
    "id" => "11"
    "employee_name" => "Ochong, Jorlan  Jr. Fuerte"
    "description" => "Hard Hat"
    "total_amount" => "225.00"
*/
