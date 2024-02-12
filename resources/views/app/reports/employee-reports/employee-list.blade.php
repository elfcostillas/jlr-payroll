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
        <td>Employment Status</td>
        <td>Status</td>
        <td>Employee Type</td>
        <td>Bank Account</td>
        <td>Basic Rate</td>
        <td>Monthly Allowance</td>
        <td>Daily Allowance</td>
        <td>Date Hired</td>
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
            <td>{{ $emp->estatus_desc }}  </td>
            <td>{{ $emp->status_desc }}  </td>
            <td>{{ $emp->pay_description }}  </td>
            <td>{{ $emp->bank_acct }}</td>
            <td>{{ $emp->basic_salary }}  </td>
            <td>{{ $emp->monthly_allowance }}  </td>
            <td>{{ $emp->daily_allowance }}  </td>
            <td>{{ $emp->date_hired }} </td>
        </tr>
    @endforeach
</table>


