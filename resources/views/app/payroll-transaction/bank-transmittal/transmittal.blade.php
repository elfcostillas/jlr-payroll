<table border=1>
    <tr>
        <td>Acct. Number</td>
        <td>Amount</td>
        <td>Name</td>
    </tr>
    @if(is_string($data))

    @else
        @foreach($data as $row)
            <tr>
                <td>{{ $row->bank_acct }}</td>
                <td>{{ $row->net_pay }}</td>
                <td>{{ strtoupper($row->employee_name) }}</td>
            </tr>
        @endforeach
    @endif
</table>