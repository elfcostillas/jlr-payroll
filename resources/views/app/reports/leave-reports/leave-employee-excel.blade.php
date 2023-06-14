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
<table>
    <tr>
        <td>Name</td>
        <td>Date</td>
        <td>Type</td>
        <td>Reason</td>
        <td>With Pay</td>
        <td>Without Pay</td>
        <td>Under Time</td>
        <td>VL Balance</td>
        <td>SL Balance  </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="5"></td>
    </tr>
    @foreach($data as $emp)
        <tr>
            <td> {{ $emp->employee_name }} </td>
        </tr>
       
        @foreach($emp->leaves as $leave)
           
            <tr>
                <td></td>
                <td>{{ $leave->mask_leave_date }}</td>
                <td>{{ $leave->leave_type }}</td>
                <td>{{ $leave->remarks }}</td>
                
                <td>{{ nformat(($leave->leave_type!='UT') ? $leave->with_pay : 0 )  }}</td>
                <td>{{ nformat(($leave->leave_type!='UT') ? $leave->without_pay : 0 )  }}</td>
                <td>{{ nformat(($leave->leave_type=='UT') ? ($leave->without_pay + $leave->with_pay) : 0 )  }}</td>
                <td>{{ $leave->bal[0]->vacation_leave - $leave->bal[0]->VL_PAY }}</td>
                <td>{{ $leave->bal[0]->sick_leave - $leave->bal[0]->SL_PAY }}</td>
            </tr>
        @endforeach
    @endforeach
</table>