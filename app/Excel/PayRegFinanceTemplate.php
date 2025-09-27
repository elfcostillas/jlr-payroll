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

class PayRegFinanceTemplate implements ShouldAutoSize,WithColumnFormatting,FromView,WithEvents 
{

    protected $data;
    protected $label;
    protected $label2;
    protected $payroll;

    public function registerEvents(): array
    {   
        return [
            AfterSheet::class    => function(AfterSheet $event) {

            }
        ];    
    }

    public function view() : View
    {
        
        return view('app.payroll-transaction.payroll-register-confi.payroll-register-finance',[
            'data' => $this->data,
            'label' => $this->label,
            'label2' => $this->label2,
            'payroll' => $this->payroll
        ]);
    }

    public function setValues($data,$label,$label2,$payroll){
    	$this->data = $data;
    	$this->label = $label;
    	$this->label2 = $label2;
    	$this->payroll = $payroll;
       
    } 

    public function columnFormats(): array
    {
      

        $cols = [];
        // $cols['B'] = NumberFormat::FORMAT_NUMBER_00;
        $limit = 75;
        $col = 'E';

        for($x=1;$x<=$limit;$x++){
         
            $cols[$col]=NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2;
            $col++;
        }
        return $cols;




    }
}
