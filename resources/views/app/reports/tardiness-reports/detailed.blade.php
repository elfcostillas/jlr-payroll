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

    use Carbon\Carbon;
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


    @if($data)

        @foreach($data as $emp)
            <table style="border-collapse:collapse;" border=1>
                @php  $ctr = 0; $total = 0; @endphp
                <tr>
                    <td> &nbsp; &nbsp;</td>
                    <td width="100px">Biometric ID</td>
                    <td colspan=2 width="240px">Name</td>
                    <td width="100px"></td>
                    
                </tr>
                <tr>
                    <td></td>
                    <td>{{ $emp->biometric_id }}</td>
                    <td colspan=2 >{{ $emp->employee_name }}</td>
                    <td style="text-align:center">{{ $emp->late_count }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Date</td>
                    <td>Time In</td>
                    <td>Minutes</td>
                    <td></td>
                </tr>
                @foreach($emp->late_punch as $late)
                    @php 
                        $dtr_date = Carbon::createFromFormat('Y-m-d',$late->dtr_date);
                        $total += $late->in_minutes;
                    @endphp
                    <tr>
                        <td> {{++$ctr}}</td>
                        <td>{{ $dtr_date->format('m/d/Y') }}</td>
                        <td>{{ $late->time_in }}</td>
                        <td>{{ $late->in_minutes }}</td>
                        <td> </td>
                        
                    </tr>
                @endforeach

                @php 
                    if($total%60 > 0){
                        $mins = $total % 60;
                        $hrs = floor($total /60);
                        $str = ($hrs>0) ? $hrs.' Hr(s) ' : '';
                        $str .= ($mins>0) ? $mins.' Min(s)' : '';
                    } else {    
                        $str = floor($total / 60) .'Hr(s)';
                    }
                @endphp
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{$total}}</td>
                    <td style="white-space: nowrap;"> {{ $str }}</td>
                </tr>
            </table>
            <br>
        @endforeach

    @endif

