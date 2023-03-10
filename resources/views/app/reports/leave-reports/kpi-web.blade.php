<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        * {
            font-family: 'Consolas';
            font-size: 9pt;
        }

        table tr td {
            padding : 4px 6px;
        }
    </style>

    <?php
    
        function nformat($n)
        {
            if($n > 0){
                return $n;
            }else {
                return '';
            }
        }

        function convert($total){
            if($total>0){
                if($total%60 > 0){
                    $mins = $total % 60;
                    $hrs = floor($total /60);
                    $str = ($hrs>0) ? $hrs.' Hr(s) ' : '';
                    $str .= ($mins>0) ? $mins.' Min(s)' : '';
                } else {    
                    $str = floor($total / 60) .'Hr(s)';
                }
            } else {
                $str = '';
            }
           

            return $str;
        }
    
    
    ?>
</head>
<body>
    @foreach($divisions as $div)
        {{ $div->div_name }}
        @php $ctr = 1; @endphp
        <table border=1 style="border-collapse:collapse">
            <tr>
                <td rowspan=2>No. </td>
                <td rowspan=2>Biometric ID</td>
                <td rowspan=2>Employee Name</td>
                @for($i = $index;$i<=$limit;$i++)
                    <td colspan=10>{{ $month[$i] }}</td>
                @endfor
            </tr>
            <tr>
                
                @for($i = $index;$i<=$limit;$i++)
                    <td>Tardy</td>
                    <td>SL</td>
                    <td>VL</td>
                    <td>EL</td>
                    <td>UT</td>
                    <td>BL</td>
                    <td>MP</td>
                    <td>SVL</td>
                    <td>Other</td>
                    <td>Total Tardy</td>
                @endfor
            </tr>
            @foreach($div->emp as $emp)
                <tr>
                    <td> {{ $ctr++ }} </td>
                    <td> {{ $emp->biometric_id }} </td>
                    <td style="white-space:nowrap"> {{ $emp->employee_name }} </td>
                    @for($i = $index;$i<=$limit;$i++)
                        <td >{{ nformat($tableData[$emp->biometric_id][$i]['late_count']) }}</td>
                        <td >{{ nformat($tableData[$emp->biometric_id][$i]['sl_count']) }}</td>
                        <td >{{ nformat($tableData[$emp->biometric_id][$i]['vl_count']) }}</td>
                        <td >{{ nformat($tableData[$emp->biometric_id][$i]['el_count']) }}</td>
                        <td >{{ nformat($tableData[$emp->biometric_id][$i]['ut_count']) }}</td>
                        <td >{{ nformat($tableData[$emp->biometric_id][$i]['bl_count']) }}</td>
                        <td >{{ nformat($tableData[$emp->biometric_id][$i]['mp_count']) }}</td>
                        <td >{{ nformat($tableData[$emp->biometric_id][$i]['svl_count']) }}</td>
                        <td >{{ nformat($tableData[$emp->biometric_id][$i]['o_count']) }}</td>
                        <td style="white-space:nowrap" >{{ convert($tableData[$emp->biometric_id][$i]['in_minutes']) }}</td>
                       
                    @endfor
                </tr>
            @endforeach
        </table>
        <br>
    @endforeach
</body>
</html>