<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            font-size: 9pt;
        }
    </style>

    <?php

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

        $sg_total = array(
            'bpn' => 0,
            'bps' => 0,
            'qad' => 0,        
        );

        foreach($confi as $row){
            $confi_total['bpn'] += $row->bpn;
            $confi_total['bps'] += $row->bps;
            $confi_total['qad'] += $row->qad;
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
    <table border="1" style="border-collapse: collapse;width:540px" >
        <tr>
            <td style="width:17%;text-align: center;font-weight:bold;">&nbsp;</td>
            <td style="width:17%;text-align: center;font-weight:bold;">North <br> (Suico) </td>
            <td style="width:17%;text-align: center;font-weight:bold;">South <br> (Villarino) </td>
            <td style="width:17%;text-align: center;font-weight:bold;">QAD </td>
            <td style="width:17%;text-align: center;font-weight:bold;">Total </td>
            <td style="width:17%;text-align: center;font-weight:bold;">(%)</td>
           
        </tr>
        <tr>
            <td>Confi</td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($confi_total['bpn'],2) }}</td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($confi_total['bps'],2) }}</td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($confi_total['qad'],2) }}</td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($confi_total['bpn'] + $confi_total['bps'] + $confi_total['qad'],2) }}</td>
            <td style="text-align:center;"> {{ compute_percentage($confi_total['bpn'] + $confi_total['bps'] + $confi_total['qad'],$over_all_total) }}% </td>
        </tr>
        <tr>
            <td>Rank and File</td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($ranknfile['bpn'],2) }}</td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($ranknfile['bps'],2) }}</td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($ranknfile['qad'],2) }}</td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($ranknfile['bpn'] + $ranknfile['bps'] + $ranknfile['qad'],2) }}</td>
            <td style="text-align:center;"> {{ compute_percentage($ranknfile['bpn'] + $ranknfile['bps'] + $ranknfile['qad'],$over_all_total) }}% </td>
        </tr>
        <tr>
            <td>Support Group</td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($sg_total['bpn'],2) }}</td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($sg_total['bps'],2) }}</td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($sg_total['qad'],2) }}</td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($sg_total['bpn'] + $sg_total['bps'] + $sg_total['qad'],2) }}</td>
            <td style="text-align:center;"> {{ compute_percentage($sg_total['bpn'] + $sg_total['bps'] + $sg_total['qad'],$over_all_total) }}% </td>
       
        </tr>
        <tr>
            <td>Total</td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($confi_total['bpn'] + $ranknfile['bpn'] + $sg_total['bpn'],2) }} </td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($confi_total['bps'] + $ranknfile['bps'] + $sg_total['bps'],2) }} </td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($confi_total['qad'] + $ranknfile['qad'] + $sg_total['qad'],2) }} </td>
            <td style="text-align:right;padding-right:4px;"> {{ number_format($over_all_total,2) }}</td>
            <td></td>
        </tr>
    </table>
</body>
</html>