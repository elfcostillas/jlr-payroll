<?php

namespace App\Excel;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ThirteenthMonthConso implements ShouldAutoSize,WithColumnFormatting,FromView,WithEvents 
{
    //
    private $weekly;
    private $semi;
    private $months;

    public function registerEvents(): array
    {   
        return [
            AfterSheet::class    => function(AfterSheet $event) {

            }
        ];    
    }

    public function setValues($semi,$weekly,$months)
    {
        $this->weekly = $weekly;
        $this->semi = $semi;
        $this->months = $months;
    }

    public function view() : View
    {
        
        return view("app.payroll-transaction.thirteenth-month-weekly.conso-v2",[
                'semi' => $this->semi,
                'weekly' => $this->weekly ,
                'months' => $this->months ,
            ]);
    }

    public function columnFormats(): array
    {
        $cols = [];
        $limit = count($this->months) + 2;
        $col = 'B';

        for($x=1;$x<=$limit;$x++){
         
            $cols[$col]=NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2;
            $col++;
        }

        return $cols;
      
    }
}
