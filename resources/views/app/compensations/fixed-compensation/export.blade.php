<table>
    <tr>
        <td>Deduction ID</td>
        <td>Biometric ID</td>
        <td>Employee</td>
        <td>Amount</td>
      
    </tr>
    @foreach($data as $row)
        
        <tr>
            <td> {{ $header_id }}</td>
            <td> {{ $row->biometric_id }}</td>
            <td> {{ $row->employee_name }}</td>
            <td> {{ ($row->total_amount > 0 ) ? $row->total_amount : '' }}</td>
        </tr>

    @endforeach

</table>