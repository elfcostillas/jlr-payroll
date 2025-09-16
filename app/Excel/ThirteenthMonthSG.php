<?php

namespace App\Excel;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ThirteenthMonthSG implements ShouldAutoSize,WithColumnFormatting,FromView,WithEvents 
{
    //
    private $data;

    public function registerEvents(): array
    {   
        return [
            AfterSheet::class    => function(AfterSheet $event) {

            }
        ];    
    }

    public function setValues($data)
    {
        $this->data = $data;
    }

    public function view() : View
    {
        return view("app.payroll-transaction.thirteenth-month-weekly.export",['result' => $this->data['location'],'payroll_period' => $this->data['payroll_period'] ]);
    }

    public function columnFormats(): array
    {
        $cols = [];
        $limit = count($this->data['payroll_period']) + 2;
        $col = 'B';

        for($x=1;$x<=$limit;$x++){
         
            $cols[$col]=NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2;
            $col++;
        }

        return $cols;
      
    }
}
