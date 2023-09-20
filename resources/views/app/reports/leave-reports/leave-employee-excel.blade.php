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
        <td style="border:1px solid black;"  >Name</td>
        <td style="border:1px solid black;">Date</td>
        <td style="border:1px solid black;">Type</td>
        <td style="border:1px solid black;">Reason</td>
        <td style="border:1px solid black;">With Pay</td>
        <td style="border:1px solid black;">Without Pay</td>
        <td style="border:1px solid black;">Birthday Leave</td>
        <td style="border:1px solid black;">Under Time</td>
        <td style="border:1px solid black;">SVL</td>
        <td style="border:1px solid black;">VL Balance</td>
        <td style="border:1px solid black;">SL Balance  </td>
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
                <td style="border:1px solid black;">{{ $leave->leave_type }}</td>
                <td style="border:1px solid black;">{{ $leave->remarks }}</td>
                
                <td style="border:1px solid black;">{{ nformat(($leave->leave_type!='UT' && $leave->leave_type!='SVL' && $leave->leave_type!='BL') ? $leave->with_pay : 0 )  }}</td>
                <td style="border:1px solid black;">{{ nformat(($leave->leave_type!='UT' && $leave->leave_type!='SVL' && $leave->leave_type!='BL') ? $leave->without_pay : 0 )  }}</td>
                <td style="border:1px solid black;">{{ nformat(($leave->leave_type=='BL') ? $leave->without_pay + $leave->with_pay : 0 )  }}</td>
                <td style="border:1px solid black;">{{ nformat(($leave->leave_type=='UT' && $leave->leave_type!='SVL' && $leave->leave_type!='BL') ? ($leave->without_pay + $leave->with_pay) : 0 )  }}</td>
                <td style="border:1px solid black;">{{ nformat(($leave->leave_type=='SVL') ? ($leave->without_pay + $leave->with_pay) : 0 )  }}</td>
                <td style="border:1px solid black;">{{ $leave->bal[0]->vacation_leave - $leave->bal[0]->VL_PAY }}</td>
                <td style="border:1px solid black;">{{ $leave->bal[0]->sick_leave - $leave->bal[0]->SL_PAY }}</td>
            </tr>
        @endforeach
    @endforeach
</table>