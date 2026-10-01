<?php

namespace App\Excel;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ManageDTRSummaryExport implements ShouldAutoSize,WithColumnFormatting,FromView,WithEvents 
{
    private $data;

    public function registerEvents(): array
    {   
        return [
            AfterSheet::class    => function(AfterSheet $event) {

            }
        ];    
    }

    public function view(): View
    {
		//dd($this->label['asOf']);
        return view('app.timekeeping.manage-dtr-summary.export', [
            'result' => $this->data,
           
        ]);
    }

    public function setValues($data){
    	$this->data = $data;
       
    }   

    public function columnFormats(): array
    {

        $cols = [];

        return $cols;

    }
}
