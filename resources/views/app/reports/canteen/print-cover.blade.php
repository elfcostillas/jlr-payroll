<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            font-size: 10pt;
        }
    </style>

    <?php

        // dd($payroll_periods);

use Carbon\Carbon;

        $date_from = Carbon::createFromFormat('Y-m-d',$payroll_periods->date_from);
        $date_to = Carbon::createFromFormat('Y-m-d',$payroll_periods->date_to);

        $confi_total = array(
            'bpn' => 0,
            'bps' => 0,
            'qad' => 0,        
        );

        $ranknfile = array(
            'bpn' => 0,
            'bps' => 0,
            'qad' => 0,        
        );

        $confi_count = array(
            'bpn' => 0,
            'bps' => 0,
            'qad' => 0,        
        );



        $sg_total = array(
            'bpn' => 0,
            'bps' => 0,
            'qad' => 0,        
        );

        foreach($confi as $row){
            $confi_total['bpn'] += $row->bpn;
            $confi_total['bps'] += $row->bps;
            $confi_total['qad'] += $row->qad;

            if( $row->bpn > 0 &&  $row->bps > 0 &&  $row->qad > 0)
            {
                if(($row->bpn >= $row->bps) && ($row->bpn >= $row->qad))
                {
                    $confi_count['bpn'] += 1;
                } else if(($row->bps > $row->bpn) && ($row->bps >= $row->qad))
                {
                    $confi_count['bps'] += 1;

                }else{
                    $confi_count['qad'] += 1;
                }
            }
        }

        foreach($rank_n_file as $row){
            $ranknfile['bpn'] += $row->bpn;
            $ranknfile['bps'] += $row->bps;
            $ranknfile['qad'] += $row->qad;
        }

        foreach($sg as $row){
            $sg_total['bpn'] += $row->canteen_bpn;
            $sg_total['bps'] += $row->canteen_bps;
            $sg_total['qad'] += $row->canteen_agg;
        }

        $over_all_total = 0;
        $over_all_total += $confi_total['bpn'] + $confi_total['bps'] + $confi_total['qad'] +
                    $ranknfile['bpn'] + $ranknfile['bps'] + $ranknfile['qad'] + 
                    $sg_total['bpn'] + $sg_total['bps'] + $sg_total['qad'];

        function compute_percentage($n,$total)
        {
            $var = round($n/$total,4);

            return $var * 100;
        }



        /*
         "canteen_bpn" => "0.00"
    "canteen_bps" => "0.00"
    "canteen_agg" => "0.00"
    */
    ?>
</head>
<body>
    <table style="width:100%">
        <tr>
            <td style="text-align: center;font-weight:bold;">
                Canteen Summary 
                <br>
                {{ $date_from->format('M d, Y') }} -  {{ $date_to->format('M d, Y') }}

            </td>
        </tr>
    </table>
    <table border="1" style="margin-top:8px;border-collapse: collapse;width:620px;" >
        <tr>
            <td style="padding: 4px;width:17%;text-align: center;font-weight:bold;">&nbsp;</td>
            <td style="padding: 4px;width:17%;text-align: center;font-weight:bold;">North <br> (Suico) </td>
            <td style="padding: 4px;width:17%;text-align: center;font-weight:bold;">South <br> (Villarino) </td>
            <td style="padding: 4px;width:17%;text-align: center;font-weight:bold;">QAD </td>
            <td style="padding: 4px;width:17%;text-align: center;font-weight:bold;">Total </td>
            <!-- <td style="padding: 4px;width:17%;text-align: center;font-weight:bold;">(%)</td> -->
           
        </tr>
        <tr>
            <td style="padding: 4px;">Confi</td>
            <td style="padding: 4px;text-align:right;padding-right:4px;"> {{ number_format($confi_total['bpn'],2) }}</td>
            <td style="padding: 4px;text-align:right;padding-right:4px;"> {{ number_format($confi_total['bps'],2) }}</td>
            <td style="padding: 4px;text-align:right;padding-right:4px;"> {{ number_format($confi_total['qad'],2) }}</td>
            <td style="padding: 4px;text-align:right;padding-right:4px;"> {{ number_format($confi_total['bpn'] + $confi_total['bps'] + $confi_total['qad'],2) }}</td>
            <!-- <td style="padding: 4px;text-align:center;"> {{ compute_percentage($confi_total['bpn'] + $confi_total['bps'] + $confi_total['qad'],$over_all_total) }}% </td> -->
        </tr>
        <tr>
            <td style="padding: 4px;">Rank and File</td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($ranknfile['bpn'],2) }}</td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($ranknfile['bps'],2) }}</td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($ranknfile['qad'],2) }}</td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($ranknfile['bpn'] + $ranknfile['bps'] + $ranknfile['qad'],2) }}</td>
            <!-- <td style="text-align:center;"> {{ compute_percentage($ranknfile['bpn'] + $ranknfile['bps'] + $ranknfile['qad'],$over_all_total) }}% </td> -->
        </tr>
        <tr>
            <td style="padding: 4px;">Support Group</td>
            <td style="padding: 4px;text-align:right;padding-right:4px;"> {{ number_format($sg_total['bpn'],2) }}</td>
            <td style="padding: 4px;text-align:right;padding-right:4px;"> {{ number_format($sg_total['bps'],2) }}</td>
            <td style="padding: 4px;text-align:right;padding-right:4px;"> {{ number_format($sg_total['qad'],2) }}</td>
            <td style="padding: 4px;text-align:right;padding-right:4px;"> {{ number_format($sg_total['bpn'] + $sg_total['bps'] + $sg_total['qad'],2) }}</td>
            <!-- <td style="padding: 4px;text-align:center;"> {{ compute_percentage($sg_total['bpn'] + $sg_total['bps'] + $sg_total['qad'],$over_all_total) }}% </td> -->
       
        </tr>
        <tr>
            <td style="padding: 4px;">Total</td>
            <td style="padding: 4px;text-align:right;padding-right:4px;"> {{ number_format($confi_total['bpn'] + $ranknfile['bpn'] + $sg_total['bpn'],2) }} </td>
            <td style="padding: 4px;text-align:right;padding-right:4px;"> {{ number_format($confi_total['bps'] + $ranknfile['bps'] + $sg_total['bps'],2) }} </td>
            <td style="padding: 4px;text-align:right;padding-right:4px;"> {{ number_format($confi_total['qad'] + $ranknfile['qad'] + $sg_total['qad'],2) }} </td>
            <td style="padding: 4px;text-align:right;padding-right:4px;"> {{ number_format($over_all_total,2) }}</td>
            <!-- <td></td> -->
        </tr>
    </table>

    <!-- <table border="1" style="margin-top:8px;border-collapse: collapse;width:620px;" >
       
        <tr>
            <td style="padding: 4px;width:17%;text-align: center;font-weight:bold;">Last Pay <br> &nbsp;</td>
            <td style="padding: 4px;width:17%;text-align: center;font-weight:bold;">&nbsp;</td>
            <td style="padding: 4px;width:17%;text-align: center;font-weight:bold;">&nbsp;</td>
            <td style="padding: 4px;width:17%;text-align: center;font-weight:bold;">&nbsp;</td>
            <td style="padding: 4px;width:17%;text-align: center;font-weight:bold;">&nbsp;</td>
           
        </tr>
        <tr>
            <td style="padding: 4px;width:17%;text-align: center;font-weight:bold;">Total</td>
            <td style="padding: 4px;width:17%;text-align: center;font-weight:bold;">&nbsp;</td>
            <td style="padding: 4px;width:17%;text-align: center;font-weight:bold;">&nbsp;</td>
            <td style="padding: 4px;width:17%;text-align: center;font-weight:bold;">&nbsp;</td>
            <td style="padding: 4px;width:17%;text-align: center;font-weight:bold;">&nbsp;</td>
           
        </tr>
        
    </table> -->

      
</body>
</html>