<?php
    $cntr = 1;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<script src="{{ asset('theme/plugins/jquery/jquery.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.tardy').on('click',function(e){
            e.preventDefault();

            let url = $(this).attr('href');
        
            let values = url.split('|');
            let new_url = `../../sub/${values[0]}/${values[1]}/${values[2]}/${values[3]}`;

            window.open(new_url);
        });
    });
</script>
<body>
    <table border=1 style="border-collapse:collapse;">
        <tr>
            <td></td>
            <td style="width: 260px;text-align:center">Employee</td>
            <td style="width:80px;text-align:center" >Tardy</td>
            <td style="width:80px;text-align:center" >UT</td>
            <td style="width:80px;text-align:center" >AWOL</td>
            <td style="width:80px;text-align:center" >VL</td>
            <td style="width:80px;text-align:center" >SL</td>
            <td style="width:80px;text-align:center" >Other Leaves</td>
        </tr>
        @foreach($data as $row)
            <tr>
                <td> {{ $cntr++ }}</td>
                <td> {{ $row->employee_name }} - {{ $row->biometric_id }} </td>
                <td style="text-align:center;"> <a class="tardy" href="{{$range.'|'.$row->biometric_id.'|'.'LATE'}}"  >{{ $row->tardy_count }} </a> </td>
                <td style="text-align:center;"> <a class="tardy" href="{{$range.'|'.$row->biometric_id.'|'.'LATE'}}"  >{{ $row->ut_count }} </a> </td>
                <td style="text-align:center;"> <a class="tardy" href="{{$range.'|'.$row->biometric_id.'|'.'LATE'}}"  >{{ $row->awol_count }}  </a></td>
                <td style="text-align:center;"> <a class="tardy" href="{{$range.'|'.$row->biometric_id.'|'.'LATE'}}"  >{{ $row->vl_count }}  </a></td>
                <td style="text-align:center;"> <a class="tardy" href="{{$range.'|'.$row->biometric_id.'|'.'LATE'}}"  >{{ $row->sl_count }}  </a></td>
                <td style="text-align:center;"> <a class="tardy" href="{{$range.'|'.$row->biometric_id.'|'.'LATE'}}"  >{{ $row->others_count }} </a> </td>
            </tr>
        @endforeach
    </table>
</body>
</html>