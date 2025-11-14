<?php

namespace App\Excel;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class DeductedGovtLoan implements ShouldAutoSize,WithColumnFormatting,FromView,WithEvents 
{
    private $label;
    private $array;
    private $deducted_loans;

    public function registerEvents(): array
    {   
        return [
            AfterSheet::class    => function(AfterSheet $event) {

            }
        ];    
    }

    public function view() : View
    {
        return view('app.reports.deducted-loans.export',[
            'label' => $this->label,
            'array' =>  $this->array,
            'data' => $this->deducted_loans
        ]);
    }

    public function setValues($label,$array,$deducted_loans){
        $this->label = $label;
        $this->array = $array;
        $this->deducted_loans = $deducted_loans;
    }   

    public function columnFormats(): array
    {
        return [];
    }
}
