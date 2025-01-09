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
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class Contribution implements ShouldAutoSize,WithColumnFormatting,FromView,WithEvents 
{
    //
    private $data;
    private $label;
    private $type;

    public function registerEvents(): array
    {   
        return [
            AfterSheet::class    => function(AfterSheet $event) {
           
            }
        ];    
    }

    public function setValues($data,$label,$type){
    	$this->data = $data;
    	$this->label = $label;
        $this->type = $type;
    }  

    public function view(): View
    {
        return view('app.reports.sg-contribution.export-by-type',['locations' => $this->data, 'label' => $this->label,'type' => $this->type ]);
    }

    public function columnWidths(): array
    {
        return [
           
        ];
    }

    public function columnFormats(): array
    {
        $cols = [
           
            'F'=> NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'G'=> NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'H'=> NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'i'=> NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'J'=> NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'K'=> NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
           
        ];
    
        return $cols;
     
    }
}
