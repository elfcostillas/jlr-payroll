<?php

namespace App\Http\Controllers\Memo;

use App\Http\Controllers\Controller;
use App\Mappers\Memo\AWOLMapper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;


class AWOLMemoController extends Controller
{
    //
    private $mapper;

    public function __construct(AWOLMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.memo.awol-memo.index');
    }

    public function list(Request $request)
    {
        // dd($request->year,$request->month);
        // dd($request->year,$request->month);
        $year = $request->year;
        $month = $request->month;

        $url = "http://172.17.42.108/memos/awol/list/$year/$month";

        $response = Http::get($url);

        // return $response['data'];

        return response()->json([ 'data' => $response['data'], 'total                ' => $response['total'] ]);
    }

    public function print(Request $request)
    {
        // year}/{month}/{awol_grp_no}/{emp_id
        $user = Auth::user()->biometric_id;
        $year = $request->year;
        $month = $request->month;
        $awol_grp_no = $request->awol_grp_no;
        $emp_id = $request->emp_id;
        
        $url = "http://172.17.42.108/memos/awol/print/$year/$month/$awol_grp_no/$emp_id/$user";

        $response = Http::get($url);
        

        return Response::make($response, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . 'awolmemo' . '"', // 'inline' displays in browser
        ]);
        
    }
}
