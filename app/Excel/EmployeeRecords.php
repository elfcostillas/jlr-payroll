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

class EmployeeRecords implements ShouldAutoSize,WithColumnFormatting,FromView,WithEvents 
{
    protected $data;
    protected $label;

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
        return view('app.reports.employee-reports.employee-list', [
        	'data' => $this->data, 'asOf' => null
        ]);
    }

    public function setValues($data,$label){
    	$this->data = $data;
        $this->label = $label;
    }   

    public function columnFormats(): array
    {
        $cols = [];

        $col = 'C';
        for($x=4;$x<100;$x++){
         
            $cols[$col]=NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2;
            $col++;
        }

        return $cols;
      
    }
}