<table border=1>
    <tr>
        <td></td>
        <td></td>
        <td></td>
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