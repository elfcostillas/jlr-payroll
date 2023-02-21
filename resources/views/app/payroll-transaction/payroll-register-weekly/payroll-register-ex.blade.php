<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
            * {
                font-family: 'Consolas';
                font-size : 9pt;
            }
    </style>
</head>
<body>
 
    <?php
        $colspan=24;
      
    ?>
    <div id="" >
        <table style="border-collapse:collapse;white-space:nowrap;" border=1 >
            <thead>
                <tr>
                        <td  > Bio ID</td>
                        <td  >Name</td>
                        <td >Basic Rate</td>
                        <td >Daily Rate</td>
                        <td >Allowance  <br> (Monthly)</td>
                        <td >Allowance  <br> (Daily)</td>
                        <td >No Days</td>
                        <td >Basic Pay</td>
                        
                        <td >Daily <br> Allowance</td>
                        <td >Semi Montdly <br> Allowance</td>

                        <td >Late <br>(Hrs)</td>
                        <td >Late Amount</td>

                        <td >Undertime <br>(Hrs)</td>
                        <td >Undertime <br>Amount</td>

                        <td >VL</td>
                        <td >VL Amount</td>

                        <td >SL</td>
                        <td >SL Amount</td>

                        <td >BL</td>
                        <td >BL Amount</td>

                        <td >Absent</td>
                        <td >Absent <br> Amount</td>


                        @foreach($headers as $key => $val)
                            <td  width ="90px" ><p>{{ $labels[$key] }}</p></td>
                            @php $colspan++; @endphp
                        @endforeach
                        <td >Gross Pay</td>
                        @foreach($compensation as $comp)
                            <td  width ="90px" style="white-space:normal"><p>{{ $comp->description }}</p></td>
                            @php $colspan++; @endphp
                        @endforeach
                        <td >Gross Total</td>
                        <td >SSS Premium</td>
                        <td >SSS WISP</td>
                        <td >PhilHealt <br> Premium</td>
                        <td >PAG IBIG <br> Contri</td>  @php $colspan+=3; @endphp
                        @foreach($govLoan as $glabel)
                            <td width ="90px" style="white-space:normal"><p>{{ $glabel->description }}</p></td>
                            @php $colspan++; @endphp
                        @endforeach
                        
                        @foreach($deductionLabel as $label)
                            <td width ="90px" style="white-space:normal"><p>{{ $label->description }}</p></td>
                            @php $colspan++; @endphp
                        @endforeach

                        <td >Total Deduction</td>
                        <td >Net Pay</td>
                </tr>
            </thead>

                    @foreach($data as $division)  
                        <tr>
                            <td colspan={{$colspan}}  class="division"> {{ $division->div_name }} </td>
                        </tr>
                        @foreach($division->departments as $department)
                            <tr>
                                <td colspan={{$colspan}}  class="department"> {{ $department->dept_name }} </td>
                            </tr>
                            @foreach($department->employees as $employee)
                              
                                <tr >
                                    <td> {{ $employee->biometric_id }} </td> 
                                    <td > {{ $employee->employee_name }} </td> 
                                    <td > {{ $employee->basic_salary }}</td>
                                    <td > {{ $employee->daily_rate }}</td>

                                    <td > {{ ($employee->mallowance>0) ? $employee->mallowance : '' }}</td>
                                    <td > {{ ($employee->dallowance>0) ? $employee->dallowance : '' }}</td>
                                    
                                    
                                    <td > {{ $employee->ndays }}</td>
                                    <td > {{ $employee->basic_pay }}</td>

                                    <td > {{ ($employee->daily_allowance>0) ? $employee->daily_allowance : ''; }}</td>
                                    <td > {{ ($employee->payrollregister_unposted_s>0) ? $employee->payrollregister_unposted_s : ''; }}</td>

                                    <td > {{ ($employee->late_eq>0) ? $employee->late_eq : ''; }}</td>
                                    <td > {{ ($employee->late_eq_amount>0) ? $employee->late_eq_amount : ''; }}</td>
                                    <td > {{ ($employee->under_time>0) ? $employee->under_time : ''; }}</td>
                                    <td > {{ ($employee->under_time_amount>0) ? $employee->under_time_amount : ''; }}</td>
                                
                                    <td > {{ ($employee->vl_wpay>0) ? $employee->vl_wpay : ''; }}</td>
                                    <td > {{ ($employee->vl_wpay_amount>0) ? $employee->vl_wpay_amount : ''; }}</td>
                                    
                                    <td > {{ ($employee->sl_wpay>0) ? $employee->sl_wpay : ''; }}</td>
                                    <td > {{ ($employee->sl_wpay_amount>0) ? $employee->sl_wpay_amount : ''; }}</td>

                                    <td > {{ ($employee->bl_wpay>0) ? $employee->bl_wpay : ''; }}</td>
                                    <td > {{ ($employee->bl_wpay_amount>0) ? $employee->bl_wpay_amount : ''; }}</td>
                                    
                                    <td > {{ ($employee->absences_amount>0) ? $employee->absences : ''; }}</td>
                                    <td > {{ ($employee->absences_amount>0) ? $employee->absences_amount : ''; }}</td>
                                    

                                    @foreach($headers as $key => $val)
                                        <td >{{ ($employee->$key > 0) ? $employee->$key : '' }}</td>
                                    @endforeach
                                        <td>{{ ($employee->gross_pay > 0) ? $employee->gross_pay : '' }}</td>
                                    @foreach($compensation as $comp)
                                        <td  > {{ (array_key_exists($comp->id,$employee->otderEarnings)) ? $employee->otderEarnings[$comp->id] : ''; }}</td>
                                       
                                    @endforeach
                                        <td >{{ ($employee->gross_total>0) ? $employee->gross_total : ''; }}</td>
                                        <td  >{{ ($employee->gov_deductions['SSS Premium']>0) ? $employee->gov_deductions['SSS Premium'] : ''; }}</td>
                                        <td  >{{ ($employee->gov_deductions['SSS WISP']>0) ? $employee->gov_deductions['SSS WISP'] : ''; }}</td>
                                        <td  >{{ ($employee->gov_deductions['PhilHealt Premium']>0) ? $employee->gov_deductions['PhilHealt Premium'] : ''; }}</td>
                                        <td  >{{ ($employee->gov_deductions['PAG IBIG Contri']>0) ? $employee->gov_deductions['PAG IBIG Contri'] : ''; }}</td>

                                    @foreach($govLoan as $gkey => $glabel)
                                       
                                        <td  >{{ (array_key_exists($glabel->id,$employee->loans)) ? $employee->loans[$glabel->id] : ''; }}</td>
                                    @endforeach
                                    @foreach($deductionLabel as $key => $label)
                                        <td  >{{ (array_key_exists($label->id,$employee->deductions)) ? $employee->deductions[$label->id] : ''; }}</td> 
                                    @endforeach
                                    <td >{{ ($employee->total_deduction>0) ? $employee->total_deduction : ''; }}</td>
                                    <td >{{ ($employee->net_pay>0) ? $employee->net_pay : ''; }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        <tr>
                            <td></td>
                        </tr>
                    @endforeach
           
        </table>
        @if(count($no_pay)>0)
        <table>
            <tr>
                <td colspan="2"> Employees not in computation</td>
            </tr>
            <tr>
                <td>Biometric ID</td>
                <td>Employee Name</td>
            </tr>

            @foreach($no_pay as $e)
                <tr>
                    <td> {{ $e->biometric_id }}</td>
                    <td> {{ $e->employee_name }}</td>
                    
                </tr>
            @endforeach
        </table>
        @endif
    </div>
</body>
</html>