<?php
    function nformat($n)
    {
       if($n >0)
        {
            return round($n/8,1);
        }else{
            return '';
        }
    }

?>

@foreach($data as $division)
    <table border=1 > 
        <tr>
            <td>({{ $division->div_code }}) - {{ $division->div_name }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($division->departments as $department)
            @if(count($department->leaves)>0)
                <tr>
                  
                    <td>{{ $department->dept_name }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Biometric ID</td>
                    <td>Name</td>
                    <td>With Pay</td>
                    <td>Without Pay</td>
                </tr>
                    @foreach($department->leaves as $leave)
                        <tr>
                           
                            <td> {{ $leave->biometric_id }}  </td>
                            <td> {{ $leave->employee_name }}</td>
                            <td>{{ nformat($leave->with_pay) }}</td>
                            <td>{{ nformat($leave->without_pay) }}</td>
                        </tr>
                    @endforeach
            
            @endif

        @endforeach
    </table>

@endforeach
