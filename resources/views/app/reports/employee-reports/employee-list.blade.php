<table>
    <tr>
        <td>ID</td>
        <td>Bio ID</td>
        <td>Lastname</td>
        <td>Firstname</td>
        <td>Middlename</td>
        <td>(Jr/Sr/II/III)</td>
        <td>Gender</td>
        <td>Birthdate</td>
        <td>Primary Address </td>
        <td>Secondary Address</td>
        <td>Contant No</td>
        <td>Civil Status</td>
        <td>Division</td>
        <td>Department</td>
        <td>SSS No</td>
        <td>TIN no</td>
        <td>PHIC No</td>
        <td>HDMF No</td>
    </tr>
    
    @foreach($data as $emp)
        <tr>
            <td>{{ $emp->id }} </td>
            <td>{{ $emp->biometric_id }} </td>
            <td>{{ $emp->lastname }} </td>
            <td>{{ $emp->firstname }} </td>
            <td>{{ $emp->middlename }} </td>
            <td>{{ $emp->suffixname }}</td>
            <td>{{ $emp->gender }} </td>
            <td>{{ $emp->birthdate }} </td>
            <td>{{ $emp->primary_addr }}   </td>
            <td>{{ $emp->secondary_addr }}  </td>
            <td>{{ $emp->contact_no }}  </td>
            <td>{{ $emp->stat_desc }}  </td>
            <td>{{ $emp->div_code }} </td>
            <td>{{ $emp->dept_code }} </td>
            <td>{{ $emp->sss_no }}  </td>
            <td>{{ $emp->tin_no }}  </td>
            <td>{{ $emp->phic_no }}  </td>
            <td>{{ $emp->hdmf_no }}  </td>
        </tr>
    @endforeach
</table>

