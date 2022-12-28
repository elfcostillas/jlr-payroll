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

class UnpostedPayrollRegister implements ShouldAutoSize,WithColumnFormatting,FromView,WithEvents 
{
  
    private $collections;
    private $noPay;
    private $headers;
    private $deductions;
    private $gov;
    private $compensation;
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
        return view('app.payroll-transaction.payroll-register.payroll-register-ex', [
            'data' => $this->collections,
            'no_pay' => $this->noPay,
            'headers' => $this->headers , 
            'labels' => $this->label,
            'deductionLabel' => $this->deductions,
            'govLoan' => $this->gov,
            'compensation' => $this->compensation
        ]);
    }

    public function setValues($collections,$noPay,$headers,$deductions,$gov,$compensation,$label){
    	$this->collections = $collections;
        $this->noPay = $noPay;
        $this->headers = $headers;
        $this->deductions = $deductions;
        $this->gov = $gov;
        $this->compensation = $compensation;
        $this->label = $label;
    }   

    public function columnFormats(): array
    {
        $cols = [];

        $col = 'C';
        for($x=3;$x<100;$x++){
            $col++;
         
            $cols[$col]=NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2;
        }
    
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