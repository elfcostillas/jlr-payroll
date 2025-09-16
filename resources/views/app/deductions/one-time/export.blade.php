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
            <td>{{ $row->biometric_id  }}</td>
            <td> {{ $row->empname }}</td>
            <td> {{ $row->amount }} </td>
        </tr>

    @endforeach

</table>