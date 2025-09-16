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
<table border=1 style="border-collapse:collapse;" >
    <tr>
        <td rowspan="2" style="border:1px solid black;">Name</td>
        <td rowspan="2" style="border:1px solid black;">Date</td>
        <!-- <td rowspan="2" style="border:1px solid black;">Type</td> -->
        <td rowspan="2" style="border:1px solid black;">Reason</td>
        <td colspan="2" style="border:1px solid black;">VL</td>
        <td colspan="2" style="border:1px solid black;">SL</td>
        
        <td rowspan="2" style="border:1px solid black;">Birthday Leave</td>
        <td rowspan="2" style="border:1px solid black;">Under Time</td>
        <td rowspan="2" style="border:1px solid black;">SVL</td>
        <td rowspan="2" style="border:1px solid black;">Bereavement</td>
        <td rowspan="2" style="border:1px solid black;">Maternity/Paternity Leave</td>
        <!-- <td style="border:1px solid black;">VL Balance</td>
        <td style="border:1px solid black;">SL Balance  </td> -->
    </tr>
    <tr>
       
        <td style="border:1px solid black;">With Pay</td>
        <td style="border:1px solid black;">Without Pay</td>

        <td style="border:1px solid black;">With Pay</td>
        <td style="border:1px solid black;">Without Pay</td>

       

        <!-- <td style="border:1px solid black;">VL Balance</td>
        <td style="border:1px solid black;">SL Balance  </td> -->
    </tr>
    <!-- <tr>
        <td></td>
        <td colspan="5"></td>
    </tr> -->
    @foreach($data as $emp)
        <tr>
            <td style="border:1px solid black;"> {{ $emp->employee_name }} </td>
            <td colspan=10 style="border:1px solid black;"></td>
        </tr>
       
        @foreach($emp->leaves as $leave)
          
            <tr>
                <td style="border:1px solid black;"></td>
                <td style="border:1px solid black;">{{ $leave->mask_leave_date }}</td>
                <!-- <td style="border:1px solid black;">{{ $leave->leave_type }}</td> -->
                <td style="border:1px solid black;">{{ $leave->remarks }}</td>
                
                <td style="border:1px solid black;"> {{ nformat(($leave->leave_type=='VL') ? $leave->with_pay : 0  ) }} </td>  <!-- VL with pay  -->
                <td style="border:1px solid black;"> {{ nformat(($leave->leave_type=='VL') ? $leave->without_pay : 0  ) }}</td>  <!-- VL with out pay  -->
                <td style="border:1px solid black;"> {{ nformat(($leave->leave_type=='SL') ? $leave->with_pay : 0  ) }} </td>  <!-- VL with pay  -->
                <td style="border:1px solid black;"> {{ nformat(($leave->leave_type=='SL') ? $leave->without_pay : 0  ) }} </td>  <!-- VL with pay  -->
                <td style="border:1px solid black;">{{ nformat(($leave->leave_type=='BL') ? $leave->without_pay + $leave->with_pay : 0 )  }}</td>
                <td style="border:1px solid black;">{{ nformat(($leave->leave_type=='UT' && $leave->leave_type!='SVL' && $leave->leave_type!='BL') ? ($leave->without_pay + $leave->with_pay) : 0 )  }}</td>
                <td style="border:1px solid black;">{{ nformat(($leave->leave_type=='SVL') ? ($leave->without_pay + $leave->with_pay) : 0 )  }}</td>
                <td style="border:1px solid black;">{{ nformat(($leave->leave_type=='BRV') ? ($leave->without_pay + $leave->with_pay) : 0 )  }}</td>
                <td style="border:1px solid black;">{{ nformat(($leave->leave_type=='MP') ? ($leave->without_pay + $leave->with_pay) : 0 )  }}</td>
            </tr>
        @endforeach
    @endforeach
</table>