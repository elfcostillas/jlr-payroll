<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canteen Deductions</title>

    <style>
        * {
            font-size : 9pt;
        }
        td {
            padding : 2px 4px;
        }

        .b {
            font-weight:bold;
        }

        .c {
            text-align:center;
        }

        .r {
            text-align:right;
        }

        @page { margin: 40px 40px 60px 40px; }

        main {
            page-break-inside: auto;
        }

        body {
            margin-top: 28px;
            

        } 

    </style>
</head>
<body>
    <header>

    </header>
    <?php
        function formatNum($n)
        {
            if($n>0){
                return number_format($n,2);
            }else{
                return '';
            }
        }

        $total_bpn = 0;
        $total_bps = 0;
        $total_agg = 0;
        $total_all = 0;

        $bpn_count = 0;
        $bpn_total = 0;

        $bps_count = 0;
        $bps_total = 0;
       
        $qad_count = 0;
        $qad_total = 0;

        $highest_BPN = [
            'name' => '',
            'amount' => 0
        ];

        $highest_BPS = [
            'name' => '',
            'amount' => 0
        ];

        $highest_QAD = [
            'name' => '',
            'amount' => 0
        ];

    ?>
    <main>
        <div style="margin-bottom : 12px;font-size:12pt" class="b">Canteen Deduction ({{$label}})</div>
        <table border=1 style="border-collapse:collapse">
            <thead>
                <tr>
                    <td class="b">Employee Name</td>
                    <td class="b c"style="width:84px;">BPN (Canteen)</td>
                    <td class="b c"style="width:84px;">BPS (Canteen)</td>
                    <td class="b c"style="width:84px;">AGG (Canteen)</td>
                    <td class="b c"style="width:84px;">TOTAL</td>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td > {{ $row->employee_name }}</td>
                        <td class="r"> {{ formatNum($row->canteen_bpn) }}</td>
                        <td class="r"> {{ formatNum($row->canteen_bps) }}</td>
                        <td class="r"> {{ formatNum($row->canteen_agg) }}</td>
                        <td class="r">  {{ formatNum($row->canteen) }}</td>
                    </tr>

                    <?php
                        $total_bpn += $row->canteen_bpn;
                        $total_bps += $row->canteen_bps;
                        $total_agg += $row->canteen_agg;
                        $total_all += $row->canteen;
                   
                        if($row->canteen_bpn > 0) { 
                            $bpn_count++; 
                            $bpn_total += $row->canteen_bpn;
                        }

                        if($row->canteen_bps > 0) { 
                            $bps_count++; 
                            $bps_total += $row->canteen_bps;
                        }

                        if($row->canteen_agg > 0) { 
                            $qad_count++; 
                            $qad_total += $row->canteen_agg;
                        }

                        if($row->canteen_bpn > $highest_BPN['amount'])
                        {
                            $highest_BPN['name'] = $row->employee_name;
                            $highest_BPN['amount'] = $row->canteen_bpn;
                        }

                        if($row->canteen_bps > $highest_BPS['amount'])
                        {
                            $highest_BPS['name'] = $row->employee_name;
                            $highest_BPS['amount'] = $row->canteen_bps;
                        }

                        if($row->canteen_agg > $highest_QAD['amount'])
                        {
                            $highest_QAD['name'] = $row->employee_name;
                            $highest_QAD['amount'] = $row->canteen_agg;
                        }

                ?>
                @endforeach
                <tr>
                    <td class="b">TOTALS :</td>
                    <td class="b r"style="width:84px;"> {{ formatNum($total_bpn) }}</td>
                    <td class="b r"style="width:84px;"> {{ formatNum($total_bps) }}</td>
                    <td class="b r"style="width:84px;"> {{ formatNum($total_agg) }}</td>
                    <td class="b r"style="width:84px;"> {{ formatNum($total_all) }}</td>
                </tr>
            </tbody>
        </table>

        <table border="1" style="margin-top : 12px;border-collapse:collapse;width : 440px">
            <tr>
                <td rowspan="2"></td>
                <td style="font-weight: bold;text-align: center;">BPN Head Count</td>
                <td style="font-weight: bold;text-align: center;">BPS Head Count</td>
                <td style="font-weight: bold;text-align: center;">QAD Head Count</td>
            </tr>
            <tr>
               
                <td style="text-align: center;"> {{ $bpn_count }} </td>
                <td style="text-align: center;"> {{ $bps_count }} </td>
                <td style="text-align: center;"> {{ $qad_count }} </td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Average :</td>
                <td style="text-align: center;">P {{ ($bpn_count>0) ? number_format($bpn_total/$bpn_count,2) :0 }} </td>
                <td style="text-align: center;">P {{ ($bps_count>0) ? number_format($bps_total/$bps_count,2) : 0 }} </td>
                <td style="text-align: center;">P {{ ($qad_count>0 )? number_format($qad_total/$qad_count,2) : 0 }} </td>
            </tr>
        </table>

        <table border="1" style="margin-top : 12px;border-collapse:collapse;width : 440px">
            <tr>
                <td style="width:33%;font-weight: bold;text-align: center;">BPN</td>
                <td style="width:33%;font-weight: bold;text-align: center;">BPS</td>
                <td style="width:33%;font-weight: bold;text-align: center;">QAD</td>
            </tr>
            <tr>
                <td style="text-align: center;"> {{ $highest_BPN['name'] }}</td>
                <td style="text-align: center;"> {{ $highest_BPS['name'] }}</td>
                <td style="text-align: center;"> {{ $highest_QAD['name'] }}</td>
            </tr>
            <tr>
                <td style="text-align: center;"> {{ number_format($highest_BPN['amount'],2) }}</td>
                <td style="text-align: center;"> {{ number_format($highest_BPS['amount'],2) }}</td>
                <td style="text-align: center;"> {{ number_format($highest_QAD['amount'],2) }}</td>
            </tr>
        </table>
        
    </main>
</body>
</html>