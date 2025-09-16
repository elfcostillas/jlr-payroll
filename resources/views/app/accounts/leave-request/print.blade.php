@php
    use Carbon\Carbon;

    function nformat($n)
    {
        if($n!=0)
        {
            return $n;
        } else {
            return '';
        }
    }

    $total_wpay = 0;
    $total_wopay = 0;


    $total_vlwpay= 0;
    $total_slwpay= 0;

    $vlc = ($leave_credits) ? $leave_credits->vacation_leave : 0;
    $slc = ($leave_credits) ? $leave_credits->sick_leave : 0;


@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leaves</title>

    <style>
        #leave_tb tr td {
            color:white;
            font-size : 8pt;
            font-family : Arial;
            padding : 3px;
        }
    </style>
</head>
<body>

    <table id="leave_tb" border=1 style="border-collapse:collapse;" width="100%">
        <tr>
            <td colspan=3></td>
            <td>V.L.</td>
            <td>S.L.</td>
            <td></td>
        </tr>
      
        @if(count($data)>0)
           
        @foreach($data as $row)
            @php
              
                $total_vlwpay += ($row->leave_type=='VL' || $row->leave_type=='EL') ? $row->with_pay : 0;
                $total_slwpay += ($row->leave_type=='SL') ? $row->with_pay : 0;
                
            @endphp
             
            @endforeach
            
            <?php
                
                $bal_vlwpay = $vlc - $total_vlwpay;
                $bal_slwpay = $slc - $total_slwpay;
                $bal_wopay =  0 ;
                
            ?>
            
        @else
            <?php
                $bal_vlwpay = $vlc;
                $bal_slwpay = $slc;
            ?>
        @endif
        <tr>
            <td colspan=3>Balance</td>
            <td><b>{{ number_format($bal_vlwpay,2) }}</b></td>
            <td><b>{{ number_format($bal_slwpay,2) }}</b></td>
            <td></td>
        </tr>
    </table>

</body>
</html>