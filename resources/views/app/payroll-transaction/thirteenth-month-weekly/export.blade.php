<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php

use Carbon\Carbon;

function dformat($date)
{
    return Carbon::createFromFormat('Y-m-d',$date)->format('M d');
}

function nformat($n)
{
    return ($n>0) ? $n : '';
}

$total_gross = 0;
$total_net = 0;

?>

<script>
   
</script>
<style>

</style>

    <table id="rowClick" border=1 style="font-size : 8pt;">
    @foreach($result as $location)
    <?php $ctr = 1; ?>
    <tr>
        <td class="p02 t_header c-align" style="min-width: 200px;font-weight:bold;">{{ $location->location_name }}</td>
        @foreach($payroll_period as $period)
            <td  class="p02 t_header c-align" style="min-width: 106px;font-weight:bold;"> {{ dformat($period->date_from) }} - {{ dformat($period->date_to) }} </td>
        @endforeach
        <td class="p02 t_header c-align" style="min-width: 106px;font-weight:bold;"> Gross Pay </td>
        <td class="p02 t_header c-align" style="min-width: 106px;font-weight:bold;"> Net Pay </td>
    </tr>
        @foreach($location->employees as $employee)
        <?php
            $total_gross += $employee->getGrossPay();
            $total_net += $employee->getNetPay();

        ?>

        <tr>
            <td> {{ $ctr++ }} </td>
            <th  class="p02"> {{ $employee->getName() }}</th>
            @foreach($payroll_period as $period)
              
                    <td  class="p02 r-align"> {{ nformat($employee->getBasicPay($period->id)) }}</td>
            
            @endforeach
            <td  class="p02 r-align"> {{ $employee->getGrossPay() }}</td>
            <td  class="p02 r-align"> {{ $employee->getNetPay() }}</td>
        </tr>
        @endforeach
        <tr>
            <td></td>
        </tr>

    @endforeach
        <tr>
         
            <td></td>
            <td></td>
                @foreach($payroll_period as $period) 
                    <td  class="p02 r-align"></td>
                @endforeach
            <td> {{ $total_gross }}</td>
            <td> {{ $total_net }}</td>
        </tr>
    </table>

</body>
</html>