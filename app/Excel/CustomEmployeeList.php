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

class CustomEmployeeList implements ShouldAutoSize,WithColumnFormatting,FromView,WithEvents 
{

    private $header;
    private $data;

    public function registerEvents(): array
    {   
        return [
            AfterSheet::class    => function(AfterSheet $event) {

            }
        ];    
    }

    public function setValues($header,$data)
    {
        $this->header = $header;
        $this->data = $data;
    }

    public function view() : View
    {
        // return view();
        return view('app.reports.employee-reports.custom-report',['headers' => $this->header  , 'data' => $this->data]);
    }

    public function columnFormats(): array
    {
        $cols = [];
        $c = 'A';
        foreach($this->header as $col)
        {   
           
            $c++;
            if($col->data_type=='decimal'){
                $cols[$c] = NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2;
              
            }
           
        }
        return $cols;
    }
}