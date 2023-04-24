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

class UnpostedPayrollRegisterWeekly implements ShouldAutoSize,WithColumnFormatting,FromView,WithEvents 
{
  
    private $collections;
    private $label;
    // private $headers;
    // private $deductions;
    // private $gov;
    // private $compensation;
    // private $label;
    // private $payperiod_label;

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
        return view('app.payroll-transaction.payroll-register-weekly.payroll-register-ex', [
            'data' => $this->collections,
            'label' => $this->label,
            // 'headers' => $this->headers , 
            // 'labels' => $this->label,
            // 'deductionLabel' => $this->deductions,
            // 'govLoan' => $this->gov,
            // 'compensation' => $this->compensation,
            // 'payperiod_label' => $this->payperiod_label
        ]);
    }

    public function setValues($collections,$label){
    	$this->collections = $collections;
        $this->label = $label;
        // $this->headers = $headers;
        // $this->deductions = $deductions;
        // $this->gov = $gov;
        // $this->compensation = $compensation;
        // $this->label = $label;
        // $this->payperiod_label = $payperiod_label;
    }   

    public function columnFormats(): array
    {
        $cols = [];

        // $col = 'D';
        // for($x=4;$x<100;$x++){
            
         
        //     $cols[$col]=NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2;
        //     $col++;
        // }
    
        return $cols;
        // return [
        //     //'A' => NumberFormat::FORMAT_NUMBER,
        //     'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
		// 	'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
		// 	'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
		// 	'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
		// 	'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
		// 	'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
		// 	'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        //     'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        //     'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
            
        // ];
    }
}