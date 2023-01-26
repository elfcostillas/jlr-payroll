<?php
    function numformat($n)
    {
        if($n>0){
            return number_format($n,2);
        }
        else {
            return '';
        }
    }
?>

<style>
    * {
        font-size: 9pt;
        font-family: Consolas;
    }

    table tr td {
        padding : 2px;
    }
</style>

<table border=1 style="border-collapse:collapse">
    <tr>
        <td>Biometric ID</td>
        <td>Lastname</td>
        <td>Firstname</td>
        <td>Suffix</td>
        <td>VL Credit</td>
        <td>SL Credit</td>
        <td>VL Consumed</td>
        <td>SL consumed</td>
        <td>Remaining Credit</td>
    </tr>
    @foreach($data as $emp) 
        <tr>
            <td> {{ $emp->biometric_id }} </td>
            <td> {{ $emp->lastname }} </td>
            <td> {{ $emp->firstname }} </td>
            <td> {{ $emp->suffixname }} </td>
            <td> {{ numformat($emp->vacation_leave) }}</td>
            <td> {{ numformat($emp->sick_leave) }}</td>
            <td> {{ numformat($emp->VL_PAY) }}</td>
            <td> {{ numformat($emp->SL_PAY) }}</td>
            <td> {{ numformat(( $emp->vacation_leave + $emp->sick_leave) - ($emp->VL_PAY + $emp->SL_PAY)) }}</td>
        </tr>
    @endforeach
</table>
