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
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class SemiMonthlyDTR implements ShouldAutoSize,WithColumnFormatting,FromView,WithEvents,WithColumnWidths
{

      
    private $data;

    public function registerEvents(): array
    {   
        return [
            AfterSheet::class    => function(AfterSheet $event) {
           
            }
        ];    
    }

    public function setValues($data){
    	$this->data = $data;
       
    }  

    public function view(): View
    {
		//dd($this->label['asOf']);
        // return view('app.payroll-transaction.bank-transmittal.transmittal', [
        //     'data' => $this->data,
           
        // ]);
        return view('app.timekeeping.manage-dtr.export-template',['data' => $this->data]);
      

        // return view('app.timekeeping.manage-dtr-weekly.excel',['data' => $this->data ]);
    }

    public function columnFormats(): array
    {
        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 14,        
            'C' => 10,        
            'D' => 10,        
            'E' => 10,        
            'F' => 10,        
        ];
    }

}