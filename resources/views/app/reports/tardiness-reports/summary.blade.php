<?php
    function nformat($n)
    {
        if($n>8){
            return number_format($n,0);
        }
        else if($n >0 && $n<=8)
        {
            return round($n/8,1);
        }else{
            return '';
        }
    }
?>
<style>
    * {
        font-family: 'Consolas';
        font-size: 10pt;
    }

    table tr td {
        padding : 4px;
    }
</style>

<table style="border-collapse:collapse;" border=1>
    <tr>
        <td>Biometric ID</td>
        <td>Name</td>
        <td>Count</td>
       
    </tr>
    @if($data)

        @foreach($data as $late)
            <tr>
                <td>{{ $late->biometric_id }}</td>
                <td>{{ $late->employee_name }}</td>
                <td style="text-align:center">{{ $late->late_count }}</td>
            </tr>

        @endforeach

    @endif
</table>
