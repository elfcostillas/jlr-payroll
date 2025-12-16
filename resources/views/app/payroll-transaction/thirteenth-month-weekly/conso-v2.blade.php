<!DOCTYPE html>
<html>
<head>
<title>Title of the document</title>
</head>

<?php

    $weekly_basic_arr = [];

    foreach($weekly as $location)
    {
        // dd($location->id);
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
                            $val = getMultiDimension($weekly_basic_arr,$e,$key);
                        ?>
                        <td  style="background-color : {{ ($val > 0 ) ? 'orange' : 'none' }} "> {{ $e->basic[$key] + $val  }} </td>
                        <?php $total +=$e->basic[$key] + $val ;   ?>
                    @endforeach
                    <td> {{ $total }}</td>
                    <td> {{ round($total/12,2) }}</td>
                </tr>
            @endforeach
        @endforeach
    </table>

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
        @foreach($weekly as $location)
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
        @endforeach
    </table>
</body>
</html>