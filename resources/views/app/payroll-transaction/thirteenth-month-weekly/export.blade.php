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

?>

<script>
   
</script>
<style>

</style>

    <table id="rowClick" border=1 style="font-size : 8pt;">
    @foreach($result as $location)
    <tr>
        <td class="p02 t_header c-align" style="min-width: 200px">{{ $location->location_name }}</td>
        @foreach($payroll_period as $period)
            <td  class="p02 t_header c-align" style="min-width: 106px"> {{ dformat($period->date_from) }} - {{ dformat($period->date_to) }} </td>
        @endforeach
        <td class="p02 t_header c-align" style="min-width: 106px"> Gross Pay </td>
        <td class="p02 t_header c-align" style="min-width: 106px"> Net Pay </td>
    </tr>
        @foreach($location->employees as $employee)
        <tr>
            <th  class="p02"> {{ $employee->getName() }}</th>
            @foreach($payroll_period as $period)
              
                    <td  class="p02 r-align"> {{ nformat($employee->getBasicPay($period->id)) }}</td>
            
            @endforeach
            <td  class="p02 r-align"> {{ $employee->getGrossPay() }}</td>
            <td  class="p02 r-align"> {{ $employee->getNetPay() }}</td>
        </tr>
        @endforeach

    @endforeach
    </table>

</body>
</html>