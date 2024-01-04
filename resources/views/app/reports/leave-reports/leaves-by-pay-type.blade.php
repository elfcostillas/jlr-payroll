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


@foreach($data as $paytype)

    @if( count($paytype->emps) >0)
        <table border=1 style="border-collapse:collapse;" >
            <tr>
                <td style="border:1px solid black;" colspan=9 > {{ $paytype->pay_description }}</td>
            </tr>
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
            
            </tr>
            @foreach($paytype->emps as $leave)
                <tr>
                    <td style="border:1px solid black;">{{ $leave->employee_name }}</td>
                    <td style="border:1px solid black;">{{ $leave->mask_leave_date }}</td>
                    <td style="border:1px solid black;">{{ $leave->leave_type }}</td>
                    <td style="border:1px solid black;">{{ $leave->remarks }}</td>
                    
                    <td style="border:1px solid black;">{{ nformat(($leave->leave_type!='UT' && $leave->leave_type!='SVL' && $leave->leave_type!='BL') ? $leave->with_pay : 0 )  }}</td>
                    <td style="border:1px solid black;">{{ nformat(($leave->leave_type!='UT' && $leave->leave_type!='SVL' && $leave->leave_type!='BL') ? $leave->without_pay : 0 )  }}</td>
                    <td style="border:1px solid black;">{{ nformat(($leave->leave_type=='BL') ? $leave->without_pay + $leave->with_pay : 0 )  }}</td>
                    <td style="border:1px solid black;">{{ nformat(($leave->leave_type=='UT' && $leave->leave_type!='SVL' && $leave->leave_type!='BL') ? ($leave->without_pay + $leave->with_pay) : 0 )  }}</td>
                    <td style="border:1px solid black;">{{ nformat(($leave->leave_type=='SVL') ? ($leave->without_pay + $leave->with_pay) : 0 )  }}</td>
                </tr>
            @endforeach
        </table>
      @endif
    

@endforeach
