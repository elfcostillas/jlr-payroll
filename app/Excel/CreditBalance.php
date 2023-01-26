<?php

namespace App\Excel;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CreditBalance implements ShouldAutoSize,WithColumnFormatting,FromView,WithEvents 
{
  
    private $data;

    public function registerEvents(): array
    {   
        return [
            AfterSheet::class    => function(AfterSheet $event) {

            }
        ];    
    }
    //
    public function view(): View
    {
		//dd($this->label['asOf']);
        // return view('app.payroll-transaction.bank-transmittal.transmittal', [
        //     'data' => $this->data,
           
        // ]);

        return view('app.timekeeping.leave-credits.year-balance',['data'=>$this->data,'year'=>$this->year,'start'=>$this->start,'end'=>$this->end]);
    }

    public function setValues($data,$year,$start,$end){
    	$this->data = $data;
    	$this->year = $year;
    	$this->start = $start;
    	$this->end = $end;
       
    }   

    public function columnFormats(): array
    {
        return [];
    }
}