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

use Maatwebsite\Excel\Concerns\WithDrawings;

class UnpostedPayrollRegister implements ShouldAutoSize,WithColumnFormatting,FromView,WithEvents,WithDrawings
{
  
    private $collections;
    private $noPay;
    private $headers;
    private $deductions;
    private $gov;
    private $compensation;
    private $label;
    private $payperiod_label;
    private $colHeaders;

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
            'compensation' => $this->compensation,
            'payperiod_label' => $this->payperiod_label,
            'colHeaders' => $this->colHeaders
        ]);
    }

    public function setValues($collections,$noPay,$headers,$deductions,$gov,$compensation,$label,$payperiod_label,$colHeaders){
    	$this->collections = $collections;
        $this->noPay = $noPay;
        $this->headers = $headers;
        $this->deductions = $deductions;
        $this->gov = $gov;
        $this->compensation = $compensation;
        $this->label = $label;
        $this->payperiod_label = $payperiod_label;
        $this->colHeaders = $colHeaders;
    }   

    public function columnFormats(): array
    {
        $cols = [];

        $col = 'D';
        for($x=4;$x<150;$x++){
            
         
            $cols[$col]=NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2;
            $col++;
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

    public function drawings()
    {
        $drawing = new Drawing();
        // $drawing->setName('Logo');
        // $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/images/header-logo.jpg'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
}