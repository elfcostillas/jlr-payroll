<?php

use Carbon\Carbon;

function dformat($date)
{
    return Carbon::createFromFormat('Y-m-d',$date)->format('M d');
}

function nformat($n)
{
    return ($n>0) ? number_format($n,2) : '';
}

?>

<script>
    $(document).ready(function(){
        $("table#rowClick tr").click(function(){
            $(this).toggleClass("active");
    
        });
    });
</script>
<style>
    .p02 { padding : 0px 6px; }
    .t_header { font-weight: bold; }
    .r-align { text-align : right;}
    .c-align { text-align : center;}

    .active {
        background-color: #90e0ef;
    }

    table tr.active {background: #90e0ef;}

    tbody th {
        position: -webkit-sticky; /* for Safari */
        position: sticky;
        left: 0;
        background: white; /* dont remove */
        /* border-right: 1px solid #CCC; */
        vertical-align: middle;
        
    }
</style>
<div>
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
                <td  class="p02 r-align"> {{ nformat($employee->getBasicPay($period->id)) }}   </td>
            @endforeach
            <td  class="p02 r-align"> {{ nformat($employee->getGrossPay()) }}</td>
            <td  class="p02 r-align"> {{ nformat($employee->getNetPay()) }}</td>
        </tr>
        @endforeach

    @endforeach
    </table>
</div>