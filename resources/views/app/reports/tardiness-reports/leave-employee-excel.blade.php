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
                <td>{{ nformat($leave->with_pay) }}</td>
                <td>{{ nformat($leave->without_pay) }}</td>
            </tr>
        @endforeach
    @endforeach
</table>