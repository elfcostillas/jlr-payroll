<?php

namespace App\Mappers\Excel;

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
use PhpOffice\PhpSpreadsheet\Style\Color;

class PLStatementDivision implements ShouldAutoSize,WithColumnFormatting,FromView,WithEvents,WithColumnWidths 
{
    protected $data;
    protected $label;

    public function registerEvents(): array
    {   
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->setShowGridlines(false);

                $sheet->getRowDimension('5')->setRowHeight(30);
                $sheet->getRowDimension('6')->setRowHeight(30);
                $this->header($event);
                $this->body($event);

                // $event->sheet->mergeCells("A{$col}:C{$col}");
                // $event->sheet->getStyle('C5')->applyFromArray(
                //     [
                //         'font' => [
                //             'size' => '12',
                //             //'name' => 'Arial',
                //             'bold' => true,
                //         ],
                //         'alignment' => [
                //             'horizontal' => Alignment::HORIZONTAL_CENTER,
                //             'vertical' => Alignment::VERTICAL_CENTER,
                //         ],
                //         'borders' => [
                //             'allBorders' => [
                //                 'borderStyle' => Border::BORDER_THIN,
                //                 'color' => [ 'argb' => '000000' ]
                //             ]
                //         ]
                //     ]
                // );
                // $event->sheet->styleCells(
                //     'B2:G8',
                //     [
                //         'borders' => [
                //             'outline' => [
                //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                //                 'color' => ['argb' => 'FFFF0000'],
                //             ],
                //         ]
                //     ]
                // );
            }
        ];    
    }
    //
    public function view(): View
    {
        // return view('accounting.plstatement.reports', [
        // 	'data' => $this->data
        // ]);
        return view('accounting.plstatement.profit-loss-division-v2-ex',[
            'acct'=>$this->data['acct'],
            'pnl_tbl'=>$this->data['pnl_tbl'],
            'arr_year'=>$this->data['arr_year'],
            'year' => $this->data['year'],
            'ytd_tbl'=>$this->data['ytd_tbl'],
            'label' => $this->label,
            'dateType'=> $this->data['dateType']
        ]);
    }

    public function setValues($data,$label){
        
    	$this->data = $data;
        $this->label = $label; 
       
    }   

    public function columnFormats(): array
    {

        $letter = 'C';

        $array = array();
        $array['A'] = NumberFormat::FORMAT_NUMBER;
        for($i=1;$i<=64;$i++)
        {
            // echo $letter++;
            // echo '<br>';
            $array[$letter] = NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3;
            $letter++;
        }
        return $array;
    }

    public function columnWidths() : array {
       
        return [
                'A' => 18,
                'B' => 38,
                  'G'=> 2,
                  'L'=> 2,
                  'Q'=> 2,
                  'V'=> 2,
                  'AA'=> 2,
                  'AF'=> 2,
                  'AK'=> 2,
                  'AP'=> 2,
                  'AU'=> 2,
                  'AZ'=> 2,
                  'BE'=> 2,
                  'BJ'=> 2,
                  'BO'=> 2,
                ];
    }

    function header($event)
    {
       // $event->sheet->mergeCells("A{$col}:C{$col}");
       $event->sheet->getStyle('A6:BN6')->applyFromArray(
           [
            'font' => [
                'underline' => true
            ]
           ]
       );

       $event->sheet->getStyle('A6:F6')->applyFromArray(
        [
            'font' => [
                'size' => '10',
                'name' => 'Ebrima',
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            // 'fill' => [
            //     'fillType'  => Fill::FILL_SOLID,
            //     'color' => ['argb' => 'D1D1D1']
            // ],
            'borders' => [
                'top' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => ['argb' => 'c3c3c3']
                ],
                'bottom' => [
                    'borderStyle' => Border::BORDER_DOUBLE,
                    'color' => ['argb' => 'c3c3c3']
                ]

            ]
        ]);
        $event->sheet->getStyle('A5:BN5')->applyFromArray(
            [
                'font' => [
                    'size' => '14',
                    'name' => 'Ebrima',
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
    
            ]);

            $month = [
                'C6:F6',
                'H6:K6',
                'M6:P6',
                'R6:U6',
                'W6:Z6',
                'AB6:AJ6',
                'AL6:AO6',
                'AQ6:AT6',
                'AV6:AY6',
                'BA6:BD6',
                'BF6:BI6',
                'BK6:BN6'
            ];

            for($ii=0;$ii<=count($this->data['arr_year']);$ii++)
            {
                $event->sheet->getStyle($month[$ii])->applyFromArray(
                    [
                        'font' => [
                            'size' => '10',
                            'name' => 'Ebrima',
                            'bold' => true,
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'top' => [
                                'borderStyle' => Border::BORDER_THICK,
                                'color' => ['argb' => 'c3c3c3']
                            ],
                            'bottom' => [
                                'borderStyle' => Border::BORDER_DOUBLE,
                                'color' => ['argb' => 'c3c3c3']
                            ]
            
                        ]
                    ]);
            }

            // foreach($month as $range){
            //     $event->sheet->getStyle($range)->applyFromArray(
            //         [
            //             'font' => [
            //                 'size' => '10',
            //                 'name' => 'Ebrima',
            //                 'bold' => true,
            //             ],
            //             'alignment' => [
            //                 'horizontal' => Alignment::HORIZONTAL_CENTER,
            //                 'vertical' => Alignment::VERTICAL_CENTER,
            //             ],
            //             'borders' => [
            //                 'top' => [
            //                     'borderStyle' => Border::BORDER_THICK,
            //                     'color' => ['argb' => 'c3c3c3']
            //                 ],
            //                 'bottom' => [
            //                     'borderStyle' => Border::BORDER_DOUBLE,
            //                     'color' => ['argb' => 'c3c3c3']
            //                 ]
            
            //             ]
            //         ]);
            // }
            
    }

    function body($event)
    {
       
        $grp = [
            ['A','F'],
            ['H','K'],
            ['M','P'],
            ['R','U'],
            ['W','Z'],
            ['AB','AJ'],
            ['AL','AO'],
            ['AQ','AT'],
            ['AV','AY'],
            ['BA','BD'],
            ['BF','BI'],
            ['BK','BN']
        ];

        //dd($this->data);
        $row_index = 6;
        $acct = $this->data['acct'];

        foreach($acct as $indexAccount)
        {
            
            $row_index++;

            //dd($indexAccount->lvl3->count());
            foreach($indexAccount->lvl3 as $lvl3)
            {
               //dd($lvl3->lvl4->count());
                $row_index += $lvl3->lvl4->count()+1;

                // foreach($grp as $cols)
                // {
                //     //dd($cols);
                //     $range = $cols[0].$row_index.':'.$cols[1].$row_index;
                //     $event->sheet->getStyle($range)->applyFromArray(
                //         [
                //             'font' => [
                //                 'size' => '10',
                //                 'name' => 'Ebrima',
                //                 //'bold' => true,
                //             ],
                //             'borders' => [
                //                 'top' => [
                //                     'borderStyle' => Border::BORDER_MEDIUM,
                //                     'color' => ['argb' => 'c3c3c3']
                //                 ],
                //                 'bottom' => [
                //                     'borderStyle' => Border::BORDER_MEDIUM,
                //                     'color' => ['argb' => 'c3c3c3']
                //                 ]
                
                //             ]
                //         ]);
                // }

                for($ii=0;$ii<=count($this->data['arr_year']);$ii++)
                {
                    $range = $grp[$ii][0].$row_index.':'.$grp[$ii][1].$row_index;
                    $event->sheet->getStyle($range)->applyFromArray(
                        [
                            'font' => [
                                'size' => '10',
                                'name' => 'Ebrima',
                                'bold' => true,
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'vertical' => Alignment::VERTICAL_CENTER,
                            ],
                            'borders' => [
                                'top' => [
                                    'borderStyle' => Border::BORDER_THICK,
                                    'color' => ['argb' => 'c3c3c3']
                                ],
                                'bottom' => [
                                    'borderStyle' => Border::BORDER_DOUBLE,
                                    'color' => ['argb' => 'c3c3c3']
                                ]
                
                            ]
                        ]);
                }

                $row_index+=1;

            }

            // $range = $grp[0][0].$row_index.':'.$grp[0][1].$row_index;
            // $event->sheet->getStyle($range)->applyFromArray(
            //     [
            //         'font' => [
            //             'size' => '10',
            //             'name' => 'Ebrima',
            //             //'bold' => true,
            //         ],
            //         'borders' => [
            //             'top' => [
            //                 'borderStyle' => Border::BORDER_MEDIUM,
            //                 'color' => ['argb' => Color::COLOR_RED]
            //             ],
            //             'bottom' => [
            //                 'borderStyle' => Border::BORDER_MEDIUM,
            //                 'color' => ['argb' => Color::COLOR_RED]
            //             ]
        
            //         ]
            //     ]);

            //$row_index+=1;

            switch($indexAccount->GroupMask){
                case 4 : case 7 :
                    $row_index+=1;
                    for($ii=0;$ii<=count($this->data['arr_year']);$ii++)
                    {
                        $range = $grp[$ii][0].$row_index.':'.$grp[$ii][1].$row_index;
                        $event->sheet->getStyle($range)->applyFromArray(
                            [
                                'fill' => [
                                    'fillType'  => Fill::FILL_SOLID,
                                    'color' => ['argb' => 'FF87CEEB']
                                ],
                            ]);
                    }

                    $row_index+=1;
                    break;
                case 5 :
                    $row_index+=4;
                    break;
               
            }
        }

        $event->sheet->getStyle('A7:BN'.$row_index)->applyFromArray(
            [
                'font' => [
                    'size' => '10',
                    'name' => 'Ebrima',
                    //'bold' => true,
                ],
            ]);


    }
   
      
}