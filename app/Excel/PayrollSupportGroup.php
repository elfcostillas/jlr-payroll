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

class PayrollSupportGroup implements ShouldAutoSize,WithColumnFormatting,FromView,WithEvents 
{
  
    private $data;
    private $label;

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
        return view('app.reports.payroll-support-group.export', [
            'data' => $this->data,
            'label' => $this->label,
        ]);
    }

    public function setValues($data,$label){
    	$this->data = $data;
        $this->label = $label;
       
    }   

    public function columnFormats(): array
    {
        // return [
        //     'C' => NumberFormat::FORMAT_NUMBER_00
        // ];

        $cols = [];
        $cols['D'] = NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2;
        $cols['E'] = NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2;
        $cols['F'] = NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2;
        $cols['G'] = NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2;

        return $cols;


    }
}