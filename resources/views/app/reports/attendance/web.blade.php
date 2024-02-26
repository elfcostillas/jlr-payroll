<table border=1 style="border-collapse:collapse;">
    <tr>
        <td style="width: 260px;text-align:center">Employee</td>
        <td style="width:80px;text-align:center" >Tardy</td>
    </tr>
    @foreach($data as $row)
        <tr>
            <td> {{ $row->employee_name }} </td>
            <td></td>
        </tr>
    @endforeach
</table>