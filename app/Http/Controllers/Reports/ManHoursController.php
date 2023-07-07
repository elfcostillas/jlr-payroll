<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Mappers\Reports\ManHoursMapper;
use Barryvdh\DomPDF\Facade\Pdf;

class ManHoursController extends Controller
{
    //
    private $mapper;

    public function __construct(ManHoursMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.reports.man-hours.index');
    }

    public function generateReport(Request $request)
    {
        $from = Carbon::createFromFormat('Y-m-d',$request->from);
        $to = Carbon::createFromFormat('Y-m-d',$request->to);

        $h1 = ($request->hr1==null || $request->hr1 == 'null') ? 0 : $request->hr1;
        $h2 = ($request->hr2==null || $request->hr2 == 'null') ? 0 : $request->hr2;

        $result = $this->mapper->getData($from->format('Y-m-d'),$to->format('Y-m-d'),$request->filtered,$h1,$h2);

        $label = $from->format('m/d/Y') . ' - ' . $to->format('m/d/Y');

        return view('app.reports.man-hours.report',['data' => $result,'label'=>$label]);
        
    }

    public function viewPDF(Request $request)
    {
        $from = Carbon::createFromFormat('Y-m-d',$request->from);
        $to = Carbon::createFromFormat('Y-m-d',$request->to);
        
        $h1 = ($request->hr1==null || $request->hr1 == 'null') ? 0 : $request->hr1;
        $h2 = ($request->hr2==null || $request->hr2 == 'null') ? 0 : $request->hr2;

        $result = $this->mapper->getData($from->format('Y-m-d'),$to->format('Y-m-d'),$request->filtered,$h1,$h2);
        
        $label = $from->format('m/d/Y') . ' - ' . $to->format('m/d/Y');

         
        $pdf = PDF::loadView('app.reports.man-hours.print',['data' => $result,'label'=>$label])->setPaper('A4','portrait');

        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(510, 800, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
       
        return $pdf->stream('JLR-DTR-Print.pdf'); 
    }

    function generateReportOT(Request $request)
    {
        $from = Carbon::createFromFormat('Y-m-d',$request->from);
        $to = Carbon::createFromFormat('Y-m-d',$request->to);

       

        $result = $this->mapper->getDataOT($from->format('Y-m-d'),$to->format('Y-m-d'),$request->filtered,$request->hr1,$request->hr2);

        $label = $from->format('m/d/Y') . ' - ' . $to->format('m/d/Y');

        return view('app.reports.man-hours.report-ot',['data' => $result,'label'=>$label]);
        
    }

    function viewPDFOT(Request $request)
    {
        $from = Carbon::createFromFormat('Y-m-d',$request->from);
        $to = Carbon::createFromFormat('Y-m-d',$request->to);

        $result = $this->mapper->getDataOT($from->format('Y-m-d'),$to->format('Y-m-d'),$request->filtered,$request->hr1,$request->hr2);
        
        $label = $from->format('m/d/Y') . ' - ' . $to->format('m/d/Y');

         
        $pdf = PDF::loadView('app.reports.man-hours.print-ot',['data' => $result,'label'=>$label])->setPaper('A4','portrait');

        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(510, 800, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
       
        return $pdf->stream('JLR-DTR-Print.pdf'); 
    }

}
