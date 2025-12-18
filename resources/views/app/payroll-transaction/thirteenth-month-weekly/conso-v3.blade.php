<!DOCTYPE html>
<html>
<head>
<title>Title of the document</title>
</head>

<?php

    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;

    $now = now();
    $user = Auth::user();

    DB::table('conso_13thmonth_headers')
        ->where('generated_by','=',$user->id)
        ->where('status','=','DRAFT-R')->delete();

    DB::table('conso_13thmonth_details')
        ->where('generated_by','=',$user->id)
        ->where('status','=','DRAFT-R')->delete();

    $weekly_basic_arr = [];

    foreach($weekly as $location)
    {
        // dd($location->id);
        if(isset($location->employees))
        {
            foreach($location->employees as $e)
            {
                // dd($e->biometric_id);
                foreach($e->basic as $month => $basic)
                {
                    // dd($month,$basic);
                    $weekly_basic_arr[$e->biometric_id][$month] = $basic;
                }
            }
        }
       
    }


    function getMultiDimension($weekly_basic_arr,$e,$month)
    {
      
        if(array_key_exists($e->biometric_id,$weekly_basic_arr))
        {
            // dd($weekly_basic_arr[$e->biometric_id]);
            if(array_key_exists($month,$weekly_basic_arr[$e->biometric_id]))
            {
                return (float) $weekly_basic_arr[$e->biometric_id][$month];
            }
        }

        return 0.00;
    }

    function inserToDB($basic_pay,$e,$key,$now,$user,$year)
    {

        $data = array(
            'biometric_id' => $e->biometric_id,
            'pyear' => $year,
            'pmonth' => $key,
            'basic_pay' => $basic_pay,
            'status' => 'DRAFT-R',
            'generated_by' => $user->id,
            'generated_on' => $now,
        );

        DB::table('conso_13thmonth_details')->insert($data);
    }

    function insertNetPay($total,$e,$key,$now,$user,$year)
    {
        $data = array(
            'pyear' => $year,
            'biometric_id' => $e->biometric_id,
            'status' => 'DRAFT-R',
            'generated_by' => $user->id,
            'generated_on' => $now,
            'gross_pay' => $total,
            'net_pay' => round($total/12,2)
        );
    
        DB::table('conso_13thmonth_headers')->insert($data);
    }
?>

<body>
    <table border=1>
        <tr>
            <td>No.</td>
            <td>Names</td>
            @foreach($months as $key => $value)
                <td> {{ $value }} </td>
            @endforeach
            <td>TOTAL</td>
            <td>NET PAY</td>
        </tr>
        @foreach($semi as $location)
            <?php
                $ctr = 1;
            ?>
            <tr >
                <td colspan=16> {{ $location->location_name  }} </td>
            </tr>
            @foreach($location->employees as $e)
                <?php
                    $total = 0;

                    // dd($e->biometric_id);
                ?>
                <tr>
                    <td> {{ $ctr++ }} </td>
                    <td> {{ $e->lastname }}, {{ $e->firstname }} </td>
                    @foreach($months as $key => $value)
                        <?php
                            $val = getMultiDimension($weekly_basic_arr,$e,$key,$now,$user);

                            $monthly = $e->basic[$key] + $val;

                            inserToDB($monthly,$e,$key,$now,$user,$year);
                        ?>
                        <td  style="background-color : {{ ($val > 0 ) ? 'orange' : 'none' }} "> {{ $e->basic[$key] + $val  }} </td>
                        <?php 
                            $total += $e->basic[$key] + $val;   
                        ?>
                    @endforeach
                        <?php
                            insertNetPay($total,$e,$key,$now,$user,$year);
                        ?>
                    <td> {{ $total }}</td>
                    <td> {{ round($total/12,2) }}</td>
                </tr>
            @endforeach
        @endforeach
    </table>

     <!-- <table border=1>
        <tr>
            <td>No.</td>
            <td>Names</td>
            @foreach($months as $key => $value)
                <td> {{ $value }} </td>
            @endforeach
            <td>TOTAL</td>
            <td>NET PAY</td>
        </tr>
        @foreach($weekly as $location)
            @if(isset($location->employees))
                
                <?php
                    $ctr = 1;
                ?>
                <tr>
                    <td colspan=16> {{ $location->location_name  }} </td>
                </tr>
                @foreach($location->employees as $e)
                    <?php
                        $total = 0;
                    ?>
                    <tr>
                        <td> {{ $ctr++ }} </td>
                        <td> {{ $e->lastname }}, {{ $e->firstname }} </td>
                        @foreach($months as $key => $value)
                            <td> {{ $e->basic[$key]  }} </td>
                            <?php $total +=$e->basic[$key];   ?>
                        @endforeach
                        <td> {{ $total }}</td>
                        <td> {{ round($total/12,2) }}</td>
                    </tr>
                @endforeach
           
            @endif
        @endforeach
    </table> -->
</body>
</html>