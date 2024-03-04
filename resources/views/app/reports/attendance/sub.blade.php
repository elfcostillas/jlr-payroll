<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    td {
        padding : 2px 4px;
    }
</style>

<?php

    use Carbon\Carbon;
?>
<body>
    <table border=1 style="border-collapse:collapse;">
        <tr>
            <td>Day</td>
            <td>Date</td>
            <td>Time IN</td>
            <td>Time Out</td>
            <td>Late</td>
            <td>Undertime</td>
            <td>AWOL</td>
            <td>VL w/ Pay</td>
            <td>VL w/o Pay</td>
            <td>SL w/ Pay</td>
            <td>SL w/o Pay</td>
            <td>Other Leaves</td>
        </tr>
        @foreach($data as $row)
            <?php
                  $date = Carbon::createFromFormat('Y-m-d',$row->dtr_date);
            ?>
          
            <tr>
                <td> {{ $date->format('l') }} </td>
                <td>{{ $row->dtr_date  }}</td>
                <td>{{ $row->time_in  }}</td>
                <td>{{ $row->time_out  }}</td>
                <td>{{ $row->late  }}</td>
                <td>{{ $row->under_time  }}</td>
                <td>{{ $row->awol  }}</td>
                <td>{{ $row->vl_wp  }}</td>
                <td>{{ $row->vl_wop  }}</td>
                <td>{{ $row->sl_wp  }}</td>
                <td>{{ $row->sl_wop  }}</td>
                <td>{{ $row->other_leave  }}</td>
            </tr>

        @endforeach
    </table>
</body>
</html>
