<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        $over_all_total = 0; 
        $over_all_total_pay = 0; 
    ?>
    <table style="float:left;font-size :12pt;border-collapse:collapse;margin-left : 8px; margin-top : 12px;border:1px solid black;" border=1>
            <tr>
                <td style="padding:2px 6px;width:80px;text-align:center;">Division</td>
                <td style="padding:2px 6px;width:80px;text-align:center;">OT Hrs</td>
                <td style="padding:2px 6px;width:80px;text-align:center;">Department</td>
               
                <td style="padding:2px 6px;width:80px;text-align:center;" class="c">OT Hrs</td>
                <td style="padding:2px 6px;width:80px;text-align:center;" class="c">Amount</td>
              
            </tr>
            @foreach ($data->getOTByDivisionDept() as $division )
            <tr>
                <?php $start = true; ?>
                <td style="padding:2px 6px;" rowspan="{{ $division->departments->count() }}" >{{ $division->div_code }} </td>
                <td style="padding:2px 6px;text-align:right;" rowspan="{{ $division->departments->count() }}" > {{ number_format($payroll->getOTDivision($division),2) }} </td>
                
                @foreach ( $division->departments as $department)
                    @if (!$start)
                        <tr>
                    @endif
                        <td style="padding:2px 6px;"> {{ $department->dept_code }} </td>    

                        
                        <td style="padding:2px 6px;text-align:right;"> {{ number_format($department->reg_ot + $department->rd_ot,2) }} </td>    
                        <td style="padding:2px 6px;text-align:right;"> {{ number_format($department->reg_ot_amount + $department->rd_ot_amount,2) }} </td>    
                     </tr>
                    @php 
                        $start = false; 
                        $over_all_total += ($department->reg_ot + $department->rd_ot);
                        $over_all_total_pay += ($department->reg_ot_amount + $department->rd_ot_amount);
                    @endphp
                @endforeach
            @endforeach
            <tr>
                <td colspan="3" style="padding:2px 6px;text-align:right;" >TOTAL</td>
                <td style="padding:2px 6px;text-align:right;"> {{ number_format($over_all_total,2) }} </td>
                <td style="padding:2px 6px;text-align:right;"> {{ number_format($over_all_total_pay,2) }} </td>
            </tr>
           
        </table>
</body>
</html>