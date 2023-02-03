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

<table>
    <tr>
        <td>Biometric ID</td>
        <td>Name</td>
        <td>With Pay</td>
        <td>Without Pay</td>
    </tr>
    @foreach($data as $row)
        <tr>
            <td>{{ $row->biometric_id }}</td>
            <td>{{ $row->employee_name }}</td>
            <td>{{ nformat($row->with_pay) }}</td>
            <td>{{ nformat($row->without_pay) }}</td>
        </tr>
    @endforeach
</table>