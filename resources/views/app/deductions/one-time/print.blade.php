<!DOCTYPE html>
<html>
<head>
<title>Title of the document</title>
<style>
    @page {
        /* size: auto;   auto is the initial value */
        margin: 45pt 60pt 45pt 60pt; /* this affects the margin in the printer settings */
    }

   body {
        font-family:Verdana, Geneva, Tahoma, sans-serif;
        font-size: 10pt;
    }
    
</style>
</head>
<body>
    <table border=0 style="width:100%;margin-bottom: 20px;">
        <tr>
            <td style="text-align: center;font-weight: bold;"> {{ $header->description }} Deduction </td>
            
        </tr>
        <tr>
            <td style="text-align: center;">{{ $header->deduction_period }}</td>
            
        </tr>
    </table>

    <?php
        // dd($header->deduction_type == 8);

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

    <table border=1 style="width:100%;border:1px solid black;border-collapse: collapse;">
        <tr>
            <td style="text-align:center;width:50px;"> No. </td>
            <td style="text-align:center;">Employee Name</td>
            <td style="text-align:center;width:80px;">BPN</td>
            <td style="text-align:center;width:80px;">BPS</td>
            <td style="text-align:center;width:80px;">QAD</td>
            <td style="text-align:center;width:80px;">Amount</td>
        </tr>
        @foreach($details as $detail)
        @if($detail->amount > 0)
            <tr>
                <td style="padding: 2px 5px;text-align:right;">{{ $loop->iteration }}</td>
                <td style="padding: 2px 5px;text-align:left;">{{ $detail->employee_name }}</td>
                <td style="padding: 2px 5px;text-align:right;">{{ ($detail->bpn > 0 ? number_format($detail->bpn,2) : '') }}</td>
                <td style="padding: 2px 5px;text-align:right;">{{ ($detail->bps > 0 ? number_format($detail->bps,2) : '') }}</td>
                <td style="padding: 2px 5px;text-align:right;">{{ ($detail->qad > 0 ? number_format($detail->qad,2) : '') }}</td>
                <td style="padding: 2px 5px;text-align:right;">{{ ($detail->amount > 0 ? number_format($detail->amount,2) : '') }}</td>
            </tr>

            <?php

                if($detail->bpn > $highest_BPN['amount'])
                {
                    $highest_BPN['name'] = $detail->employee_name;
                    $highest_BPN['amount'] = $detail->bpn;
                }

                if($detail->bps > $highest_BPS['amount'])
                {
                    $highest_BPS['name'] = $detail->employee_name;
                    $highest_BPS['amount'] = $detail->bps;
                }

                if($detail->qad > $highest_QAD['amount'])
                {
                    $highest_QAD['name'] = $detail->employee_name;
                    $highest_QAD['amount'] = $detail->qad;
                }

                if($detail->bpn > 0) { 
                    $bpn_count++; 
                    $bpn_total += $detail->bpn;
                }

                if($detail->bps > 0) { 
                    $bps_count++; 
                    $bps_total += $detail->bpn;
                }

                if($detail->qad > 0) { 
                    $qad_count++; 
                    $qad_total += $detail->bpn;
                }
            ?>
        @endif
        @endforeach
        <tr>
            <td colspan="2" style="font-weight: bold; padding: 2px 5px;text-align:right;">TOTAL</td>
            <td style="font-weight: bold; padding: 2px 5px;text-align:right;">{{ ($details->sum('bpn') > 0 ? number_format($details->sum('bpn'),2) : '') }}</td>
            <td style="font-weight: bold; padding: 2px 5px;text-align:right;">{{ ($details->sum('bps') > 0 ? number_format($details->sum('bps'),2) : '') }}</td>
            <td style="font-weight: bold; padding: 2px 5px;text-align:right;">{{ ($details->sum('qad') > 0 ? number_format($details->sum('qad'),2) : '') }}</td>
            <td style="font-weight: bold; padding: 2px 5px;text-align:right;">{{ ($details->sum('amount') > 0 ? number_format($details->sum('amount'),2) : '') }}</td>
        </tr>
    </table>

    @if ($header->deduction_type == 8)

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
        
    @endif
</body>
</html>
