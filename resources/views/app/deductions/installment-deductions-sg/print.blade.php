<?php

    function getBalance($amount,$details)
    {
        $amount = $amount;

        $amount_paid = 0;


        foreach($details as $detail)
        {
            $amount_paid += $detail->ammortization;
        }

        return $amount - $amount_paid;
    }

    $isPaid = (getBalance($header->total_amount,$details['data']) == 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10pt;
        }

        body.paid {
            background-image: url({{ asset('images/paid.jpg') }});
            background-position: center;
            background-repeat: no-repeat;
            opacity : 0.3
        }
    </style>
</head>

<body class="{{ $isPaid ? 'paid' : '' }}">
    
    <table style="width:100%;font-weight: bold;text-align:center;margin-bottom:24px">
        <tr>
            <td style="text-align:center;font-size:12pt;">{{ strtoupper($header->description) }}</td>
        </tr>
    </table>
    <table border=0 style="border-collapse:collapse;margin-bottom:12px">
      
        <tr>
            <td> Employee Name </td> 
            <td>: {{ $header->employee_name }} </td>
        </tr>
        <tr>
            <td> Deduction Type </td> 
            <td>: {{ $header->description }} </td>
        </tr>
        <tr>
            <td> Remarks </td> 
            <td>: {{ $header->remarks }} </td>
        </tr>
        <tr>
            <td> Ammortization </td> 
            <td>: {{ number_format($header->ammortization,2) }} </td>
        </tr>
        <tr>
            <td> Amount </td> 
            <td>: {{ number_format($header->total_amount,2) }} </td>
        </tr>

        <tr>
            <td> Balance </td> 
            <td>: {{ number_format(getBalance($header->total_amount,$details['data']),2) }} </td>
        </tr>

        <tr>
            <td> Payroll Period </td> 
            <td>: {{ $header->template }} </td>
        </tr>

    </table>

    <?php 
        $balance =  $header->total_amount;
    ?>

    <!-- <img src="{{ asset('images/paid.jpg') }}" alt="">  -->
    <div id="paymenttable" >
        <table border=0 style="margin:0 auto; width:80%">
            <tr>
                <td colspan="3" style="font-weight: bold;text-align:center" >PAYMENTS</td>
            </tr>
            <tr>
                <td style="text-align: center;">Payroll Period</td>
                <td style="text-align: center;">Amortization</td>
                <td style="text-align: center;">Balance</td>
            </tr>
            @foreach($details['data'] as $detail)
                {{  $balance-= $detail->ammortization }}
                <tr>
                    <td> {{ $detail->payrollperiod }} </td>
                    <td style="text-align: right;padding-right:12px;"> {{ $detail->ammortization }} </td>
                    <td style="text-align: right;padding-right:12px;"> {{ number_format($balance,2) }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</body>
</html>