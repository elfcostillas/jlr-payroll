<table border=1 style="border-collapse:collapse;">
    <tr>
        <td style="width: 260px;text-align:center">Employee</td>
        <td style="width:80px;text-align:center" >Tardy</td>
        <td style="width:80px;text-align:center" >UT</td>
        <td style="width:80px;text-align:center" >AWOL</td>
        <td style="width:80px;text-align:center" >VL</td>
        <td style="width:80px;text-align:center" >SL</td>
        <td style="width:80px;text-align:center" >Other Leaves</td>
        
       
    </tr>
    @foreach($data as $row)
        <tr>
            <td> {{ $row->employee_name }} </td>
            <td style="text-align:center;"> {{ $row->tardy_count }} </td>
            <td style="text-align:center;"> {{ $row->ut_count }} </td>
            <td style="text-align:center;"> {{ $row->awol_count }} </td>
            <td style="text-align:center;"> {{ $row->vl_count }} </td>
            <td style="text-align:center;"> {{ $row->sl_count }} </td>
            <td style="text-align:center;"> {{ $row->others_count }} </td>
        </tr>
    @endforeach
</table>