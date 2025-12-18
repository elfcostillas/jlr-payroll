<?php

namespace App\Excel;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ThirteenthMonthConsoR extends ThirteenthMonthConso
{
    //
    public function view() : View
    {
       
        return view("app.payroll-transaction.thirteenth-month-weekly.conso-v3",[
                'semi' => $this->semi,
                'weekly' => $this->weekly ,
                'months' => $this->months ,
                'year' => $this->year
            ]);
    }
}
