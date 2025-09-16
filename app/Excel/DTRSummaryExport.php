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

class DTRSummaryExport implements ShouldAutoSize,WithColumnFormatting,FromView,WithEvents 
{
  
    private $data;

    public function registerEvents(): array
    {   
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $this->body($event);
            }
        ];    
    }
    //
    public function view(): View
    {
		//dd($this->label['asOf']);
        // return view('app.payroll-transaction.bank-transmittal.transmittal', [
        //     'data' => $this->data,
           
        // ]);

        return view('app.timekeeping.dtr-summary.web',['employees' => $this->data ]);
    }

    public function setValues($data){
    	$this->data = $data;
       
    }   

    public function columnFormats(): array
    {
        return [];
    }

    function body($event)
    {
        $row_ctr = 2;
        $curMonth = null;
        $prev = '';

        // foreach($this->data as $row)
        // {
        //     if($row->holiday_type=='SH'){
        //         $range = 'A'.$row_ctr.':L'.$row_ctr;
        //         $event->sheet->getStyle($range)->applyFromArray(
        //             [
        //                 'fill' => [
        //                     'fillType'  => Fill::FILL_SOLID,
        //                     'color' => ['rgb' => 'FFFF00']
        //                 ],
        //             ]);
        //     }

        //     if($row->holiday_type=='LH'){
        //         $range = 'A'.$row_ctr.':L'.$row_ctr;
        //         $event->sheet->getStyle($range)->applyFromArray(
        //             [
        //                 'fill' => [
        //                     'fillType'  => Fill::FILL_SOLID,
        //                     'color' => ['rgb' => '00FF00']
        //                 ],
        //             ]);
        //     }

        //     if($row->holiday_type=='DBL'){
        //         $range = 'A'.$row_ctr.':L'.$row_ctr;
        //         $event->sheet->getStyle($range)->applyFromArray(
        //             [
        //                 'fill' => [
        //                     'fillType'  => Fill::FILL_SOLID,
        //                     'color' => ['rgb' => '0000FF']
        //                 ],
        //             ]);
        //     }

        //     if($prev=='' ){
        //         $prev = $row->biometric_id;
        //     }else {
        //         if($prev!=$row->biometric_id){
        //             $row_ctr++;
        //             $prev = $row->biometric_id;
        //         }
        //     }

        //     $row_ctr++;
        // }
    }
}