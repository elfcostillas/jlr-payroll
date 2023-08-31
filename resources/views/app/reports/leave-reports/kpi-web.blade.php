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

        .grey {
            /* background-color : #E5E4E2; */
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

        function computePoints($n)
        {
            if($n > 0){
                return round($n * 0.02,2);
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

        function convertoToDecimal($minutes)
        {
            if($minutes>0){
               return round($minutes/60,2);
            }else {
                return '';
            }
        }
    
    
    ?>
</head>
<body>
    @foreach($divisions as $div)
        {{ $div->div_name }}
        @php $ctr = 1; @endphp
        <table border=1 style="border-collapse:collapse;">
            <tr>
                <td rowspan=2>No. </td>
                {{-- <td rowspan=2>Biometric ID</td> --}}
                <td rowspan=2 style="width:280px">Employee Name</td>
                <td rowspan=2 style="">Department</td>
                @for($i = $index;$i<=$limit;$i++)
                    {{-- <td colspan=11>{{ $month[$i] }} {{ $year }}</td> --}}
                    <td colspan=12>{{ $month[$i] }} {{ $year }}</td>
                @endfor
                {{-- <td colspan="12" >Summary</td> --}}
                <td colspan="12" >Summary</td>
                <td colspan=7 ></td>
            </tr>
            <tr>
                
                @for($i = $index;$i<=$limit;$i++)
                    <td>Tardy</td>
                    <td class="grey">SL</td>
                    <td>VL</td>
                    <td class="grey">EL</td>
                    <td>UT</td>
                    <td class="grey">BL</td>
                    <td>MP</td>
                    <td class="grey">SVL</td>
                    <td>Others</td>
                    <td class="grey">SUS</td>
                    <td>AWOL</td>
                    {{-- <td colspan=2>Total Tardy</td> --}}

                    <td  class="grey">Total Tardy</td>
                @endfor
                    <td>Tardy</td>
                    <td class="grey">SL</td>
                    <td>VL</td>
                    <td class="grey">EL</td>
                    <td>UT</td>
                    <td class="grey">BL</td>
                    <td>MP</td>
                    <td class="grey">SVL</td>
                    <td>Others</td>
                    <td class="grey">Total Leaves and UT</td>
                    <td>SUS</td>
                    <td class="grey">AWOL</td>
                    {{-- <td colspan=2>Total Tardy</td> --}}
                    <!-- <td>Total Tardy</td> -->
                    <td>QPIP Equivalent</td>
                    <td>TOTAL</td>
                    <td>Incentives</td>
                    <td>Status</td>
                    <td>Date Hired</td>
                    <td>No of Months</td>
                    <td>Reasons</td>
            </tr>
            @foreach($div->emp as $emp)
                <?php
                   
                    $late_count = 0;
                    $sl_count = 0;
                    $vl_count = 0;
                    $el_count = 0;
                    $ut_count = 0;
                    $bl_count = 0;
                    $mp_count = 0;
                    $svl_count = 0;
                    $o_count = 0;
                    $in_minutes =0;
                ?>
                <tr>
                    <td> {{ $ctr++ }} </td>
                    {{-- <td> {{ $emp->biometric_id }} </td> --}}
                    <td style="white-space:nowrap"> {{ $emp->employee_name }} </td>
                    <td> {{ $emp->dept_code }} </td>
                    @for($i = $index;$i<=$limit;$i++)
                        <?php
                            $late_count  +=$tableData[$emp->biometric_id][$i]['late_count'];
                            $sl_count  +=$tableData[$emp->biometric_id][$i]['sl_count'];
                            $vl_count  +=$tableData[$emp->biometric_id][$i]['vl_count'];
                            $el_count  +=$tableData[$emp->biometric_id][$i]['el_count'];
                            $ut_count  +=$tableData[$emp->biometric_id][$i]['ut_count'];
                            $bl_count  +=$tableData[$emp->biometric_id][$i]['bl_count'];
                            $mp_count  +=$tableData[$emp->biometric_id][$i]['mp_count'];
                            $svl_count  +=$tableData[$emp->biometric_id][$i]['svl_count'];
                            $o_count  +=$tableData[$emp->biometric_id][$i]['o_count'];
                            $in_minutes +=$tableData[$emp->biometric_id][$i]['in_minutes'];


                        ?>

                        <td >{{ nformat($tableData[$emp->biometric_id][$i]['late_count']) }}</td>
                        <td  class="grey">{{ nformat($tableData[$emp->biometric_id][$i]['sl_count']) }}</td>
                        <td >{{ nformat($tableData[$emp->biometric_id][$i]['vl_count']) }}</td>
                        <td  class="grey">{{ nformat($tableData[$emp->biometric_id][$i]['el_count']) }}</td>
                        <td >{{ computePoints($tableData[$emp->biometric_id][$i]['ut_count']) }}</td>
                        <td  class="grey">{{ nformat($tableData[$emp->biometric_id][$i]['bl_count']) }}</td>
                        <td >{{ nformat($tableData[$emp->biometric_id][$i]['mp_count']) }}</td>
                        <td  class="grey">{{ nformat($tableData[$emp->biometric_id][$i]['svl_count']) }}</td>
                        <td >{{ nformat($tableData[$emp->biometric_id][$i]['o_count']) }}</td>
                        <td  class="grey"></td>
                        <td ></td>
                        {{-- <td style="white-space:nowrap" >{{ convert($tableData[$emp->biometric_id][$i]['in_minutes']) }}</td> --}}
                        {{-- <td style="white-space:nowrap" >{{ convert($tableData[$emp->biometric_id][$i]['in_minutes']) }}</td> --}}
                        <td  class="grey" style="white-space:nowrap" >{{ convertoToDecimal($tableData[$emp->biometric_id][$i]['in_minutes']) }}</td>
                       
                    @endfor
                    <?php 
                        $total_ut = $sl_count + $vl_count + $el_count + $mp_count + round($ut_count*0.02,2);
                    ?>
                    <td> {{ nformat($late_count) }} </td>
                    <td class="grey"> {{ nformat($sl_count) }} </td>
                    <td> {{ nformat($vl_count) }} </td>
                    <td class="grey"> {{ nformat($el_count) }} </td>
                    <td> {{ computePoints($ut_count) }} </td>
                    <td class="grey"> {{ nformat($bl_count) }} </td>
                    <td> {{ nformat($mp_count) }} </td>
                    <td class="grey"> {{ nformat($svl_count) }} </td>
                    <td> {{ nformat($o_count) }} </td>
                    <td  class="grey" style="text-align:center;font-weight:bold;"> {{ nformat($total_ut ) }}</td>  <!-- TOTAL UT and Leaves  -->
                 
                    <td></td>
                    <td  class="grey"></td>
                   
                    {{-- <td  style="white-space:nowrap" > {{ nformat(convert($in_minutes)) }} </td> --}}
                    <!-- <td  style="white-space:nowrap" > {{ nformat(convertoToDecimal($in_minutes)) }} </td> -->

                    <td></td>
                    <td></td>
                    <td></td>
                    <td> {{ $emp->estatus_desc }}</td>
                    <td>{{$emp->date_hired }}</td>
                    <td></td>
                    <td></td>
                  
                </tr>
            @endforeach
        </table>
        <br>
    @endforeach
</body>
</html>