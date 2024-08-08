<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="{{ asset('theme/plugins/jquery/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $("table#rowClick tr").click(function(){
                $(this).toggleClass("active");
        
            });
        });
    </script>
    <style>
            * {
                font-family: 'Consolas';
                font-size : 9pt;
            }

            .active {
                background-color: #90e0ef;
            }

            table tr.active {background: #90e0ef;}

            div#container2 {
                max-width:  1320px;
                max-height: 670px;
                overflow: scroll;
                position: relative;
            }

            thead {
                color : red;
            }

            thead th:first-child {

                left: 0;
                z-index: 3;
            }

            thead th:nth-child(2) {
                left: 30px;
                z-index: 3;
                
            }


            thead th:nth-child(3) {
                left: 130px;
                z-index: 3;
                
            }

            tbody th:first-child {
                left: 0;
                z-index: 1;
               
            } 
            
            tbody th:nth-child(2) {
                left: 30px;
                z-index: 1;
                
            } 

          
            tbody th:nth-child(3) {
                left: 130px;
                z-index: 1;
                
            } 

            thead th {
                position: -webkit-sticky; /* for Safari */
                position: sticky;
                top: 0;
                background: #e3e3e3;
                color: #000;
                
            
            }

            tbody th {
                position: -webkit-sticky; /* for Safari */
                position: sticky;
                left: 0;
                background: #acacac; /* dont remove */
                /* border-right: 1px solid #CCC; */
                vertical-align: middle;
                
            }

            .location {
                /* background-color:  #0096FF; */
            }

            .division {
                /* background-color:  #FFFF00; */
            }

            .department {
                /* background-color:  #BEBEBE; */
            }

            td {
                padding : 4px;
                /* border-style: dotted; */
            }

    </style>
</head>
<body>

    <?php
        $colspan=25;
        $rcount = 1;
       //ndays,basic_pay,late_eq,late_eq_amount,under_time,under_time_amount
    ?>
    <div id="" >
        <table id="rowClick" style="border-collapse:collapse;white-space:nowrap;" border=1 >
            <thead>
                <tr>
                        <th style="padding : 0px 4px;min-width: 30px">No.</th>
                        <th style="padding : 0px 4px;min-width: 100px" > Bio ID</th>
                        <th style="padding : 0px 4px; width : 240px;" >Name</th>
                        <!-- <th style="padding : 0px 4px;min-width:110px;" >Basic Rate</th> -->
                        <th style="padding : 0px 4px;min-width:110px;" >Daily Rate</th>
                        <!-- <th style="padding : 0px 4px;min-width:110px;" >Allowance (Monthly)</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Allowance (Daily)</th> -->
                        <th style="padding : 0px 4px;min-width:110px;" >No Days</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Basic Pay</th>
                        
                        <!-- <th style="padding : 0px 4px;min-width:110px;" >Daily Allowance</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Semi Monthly Allowance</th> -->

                        <th style="padding : 0px 4px;min-width:110px;" >Late (Hrs)</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Late Amount</th>

                       
                       

                        @foreach($headers as $key => $val)
                            <th style="padding : 0px 4px;min-width:100px;" >{{ $labels[$key] }}</th>
                            @php $colspan++; @endphp
                        @endforeach
                        <th style="padding : 0px 4px;min-width:110px;" >Other Earnings</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Retro Pay</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Gross Pay</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Cash Advance</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Canteen</th>
                        <th style="padding : 0px 4px;min-width:110px;" >PPE</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Office Acct</th>
                       
                        <th style="padding : 0px 4px;min-width:110px;" >Total Deduction</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Net Pay</th>
                </tr>
            </thead>
           
                  
                            @foreach($data as $employee)
                               
                                <tr style="vertical-align: top;">
                                    <th>{{ $rcount }}</th>
                                    <th style="width:120px;"> {{ $employee->biometric_id }} </th> 
                                    <th style="text-align:left; width : 240px;"> {{ $employee->employee_name }} </th> 
                                    <!-- <td style="text-align:right;background-color:#acacac;"> {{ number_format($employee->basicpay,2) }}</td> -->
                                    <td style="text-align:right;background-color:#acacac;"> {{ number_format($employee->daily_rate,2) }}</td>

                                    <!-- <td style="text-align:right;background-color:#acacac;"> {{ ($employee->mallowance>0) ? number_format(round($employee->mallowance/2),2) : '' }}</td>
                                    <td style="text-align:right;background-color:#acacac;"> {{ ($employee->dallowance>0) ? number_format($employee->dallowance,2) : '' }}</td>
                                     -->
                                    
                                    <td style="text-align:right;"> {{ number_format($employee->ndays,2) }}</td>
                                    <td style="text-align:right;"> {{ number_format($employee->basic_pay,2) }}</td>

                                    <!-- <td style="text-align:right;"> {{ ($employee->daily_allowance>0) ? number_format($employee->daily_allowance,2) : ''; }}</td>
                                    <td style="text-align:right;"> {{ ($employee->semi_monthly_allowance>0) ? number_format($employee->semi_monthly_allowance,2) : ''; }}</td> -->

                                    <td style="text-align:right;"> {{ ($employee->late_eq>0) ? number_format($employee->late_eq,2) : ''; }}</td>
                                    <td style="text-align:right;"> {{ ($employee->late_eq_amount>0) ? number_format($employee->late_eq_amount,2) : ''; }}</td>
                                
                                  

                                    @foreach($headers as $key => $val)
                                        <td style="text-align:right;">{{ ($employee->$key > 0) ? number_format($employee->$key,2) : '' }}</td>
                                    @endforeach
                                        <td style="text-align:right;"> {{ ($employee->otherEarnings['earnings']>0) ? number_format($employee->otherEarnings['earnings'],2) : ''; }}</td>
                                        <td style="text-align:right;"> {{ ($employee->otherEarnings['retro_pay']>0) ? number_format($employee->otherEarnings['retro_pay'],2) : ''; }}</td>
                                
                                        <td style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($employee->gross_total > 0) ? number_format($employee->gross_total,2) : '' }}</td>
                                        <td style="text-align:right;"> {{ ($employee->otherEarnings['cash_advance']>0) ? number_format($employee->otherEarnings['cash_advance'],2) : ''; }}</td>
                                        <td style="text-align:right;"> {{ ($employee->otherEarnings['canteen']>0) ? number_format($employee->otherEarnings['canteen'],2) : ''; }}</td>
                                       
                                        <td style="text-align:right;"> {{ ($employee->otherEarnings['deductions']>0) ? number_format($employee->otherEarnings['deductions'],2) : ''; }}</td>
                                        <td style="text-align:right;"> {{ ($employee->otherEarnings['office_account']>0) ? number_format($employee->otherEarnings['office_account'],2) : ''; }}</td>
                                    <td style="text-align:right;font-weight:bold;border-bottom:1px solid;" >{{ ($employee->total_deduction>0) ? number_format($employee->total_deduction,2) : ''; }}</td>
                                    <td style="text-align:right;font-weight:bold;border-bottom:double;{{ ($employee->net_pay < ($employee->gross_total*0.3)) ? 'color:red'  : '' }};" >{{ ($employee->net_pay>0) ? number_format($employee->net_pay,2) :  number_format($employee->net_pay,2) }}</td>
                                </tr>
                                <?php $rcount++; ?>
                            @endforeach
                    
            
        </table>
       

        @if($nopay->count()>0)
            <table border=1 style="border-collapse:collapse;">
                <tr>
                    <td>Biometric ID</td>
                    <td>Employee Name</td>
                </tr>
                @foreach($nopay as $ee)
                    <tr>
                        <td>{{ $ee->biometric_id }}</td>
                        <td>{{ $ee->employee_name }}</td>
                    </tr>
                @endforeach
            </table>
        @endif
       
    </div>
</body>
</html>