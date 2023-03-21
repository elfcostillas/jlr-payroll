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
        $colspan=25;
      
    ?>
    <div id="" >
        <table style="border-collapse:collapse;white-space:nowrap;" border=1 >
            <thead>
                <tr>    
                        <td> No. </td>
                        <td > Bio ID</td>
                        <td >Name</td>
                        <td >Basic Rate</td>
                        <td >Daily Rate</td>
                        <td >Allowance  <br> (Monthly)</td>
                        <td >Allowance  <br> (Daily)</td>
                        <td >No Days</td>
                        <td >Basic Pay</td>
                        
                        <td >Daily <br> Allowance</td>
                        <td >Semi Monthly <br> Allowance</td>

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
                        <?php 
                          
                                $divTotal['basic_salary'] = 0;
                                $divTotal['daily_rate'] = 0;

                                $divTotal['mallowance'] = 0;
                                $divTotal['dallowance'] = 0;

                                $divTotal['ndays'] = 0;
                                $divTotal['basic_pay'] = 0;
                                $divTotal['daily_allowance'] = 0;
                                $divTotal['semi_monthly_allowance'] = 0;

                                $divTotal['late_eq'] = 0;
                                $divTotal['late_eq_amount'] = 0;
                                $divTotal['under_time'] = 0;
                                $divTotal['under_time_amount'] = 0;

                                $divTotal['vl_wpay'] = 0;
                                $divTotal['vl_wpay_amount'] = 0;
                                $divTotal['sl_wpay'] = 0;
                                $divTotal['sl_wpay_amount'] = 0;
                                $divTotal['bl_wpay'] = 0;
                                $divTotal['bl_wpay_amount'] = 0;
                                $divTotal['absences'] = 0;
                                $divTotal['absences_amount'] = 0;

                                $divTotal['gross_pay'] = 0;
                                $divTotal['gross_total'] = 0;

                                foreach($headers as $key => $val)
                                {
                                    $deptDIV[$key] = 0;
                                }

                                foreach($compensation as $comp)
                                {
                                    $deptCompDIV[$comp->id] = 0;
                                }

                                $divTotal['sss'] = 0;
                                $divTotal['wisp'] = 0;
                                $divTotal['phic'] = 0;
                                $divTotal['hdmf'] = 0;

                                foreach($govLoan as $gkey => $glabel)
                                {
                                    $govDedDIV[$glabel->id] = 0;
                                }
                                
                                foreach($deductionLabel as $key => $label)
                                {
                                    $compDedDIV[$label->id] = 0;
                                }

                                $divTotal['total_deduction'] = 0;
                                $divTotal['net_pay'] = 0;
                                            
                            ?>
                        
                        @foreach($division->departments as $department)
                            <tr>
                                <td colspan={{$colspan}}  class="department"> {{ $department->dept_name }} </td>
                            </tr>
                            <?php 
                                $ctr = 1;

                                $dept['basic_salary'] = 0; 
                                $dept['daily_rate'] = 0;

                                $dept['mallowance'] = 0;
                                $dept['dallowance'] = 0;

                                $dept['ndays'] = 0; 
                                $dept['basic_pay'] = 0; 
                                $dept['daily_allowance'] = 0; 
                                $dept['semi_monthly_allowance'] = 0; 

                                $dept['late_eq'] = 0;
                                $dept['late_eq_amount'] = 0; 
                                $dept['under_time'] = 0;
                                $dept['under_time_amount'] = 0;

                                $dept['vl_wpay'] = 0;
                                $dept['vl_wpay_amount'] = 0; 
                                $dept['sl_wpay'] = 0; 
                                $dept['sl_wpay_amount'] = 0; 
                                $dept['bl_wpay'] = 0;
                                $dept['bl_wpay_amount'] = 0;
                                $dept['absences'] = 0; 
                                $dept['absences_amount'] = 0;

                                $dept['gross_pay'] = 0; 
                                $dept['gross_total'] = 0;

                                foreach($headers as $key => $val)
                                {
                                    $dept[$key] = 0;
                                }

                                foreach($compensation as $comp)
                                {
                                    $deptComp[$comp->id] = 0;
                                }

                                $dept['sss'] = 0; 
                                $dept['wisp'] = 0; 
                                $dept['phic'] = 0; 
                                $dept['hdmf'] = 0; 

                                foreach($govLoan as $gkey => $glabel)
                                {
                                    $govDed[$glabel->id] = 0;
                                }
                                
                                foreach($deductionLabel as $key => $label)
                                {
                                    $compDed[$label->id] = 0;
                                }

                                $dept['total_deduction'] = 0; 
                                $dept['net_pay'] = 0; 
                                            
                            ?>
                            @foreach($department->employees as $employee)
                                <?php
                                
                                    $dept['basic_salary'] += $employee->basic_salary;
                                    $dept['daily_rate'] += $employee->daily_rate;
                                    $dept['mallowance'] += $employee->mallowance;
                                    $dept['dallowance'] += $employee->dallowance;
                                    $dept['ndays'] += $employee->ndays;
                                    $dept['basic_pay'] += $employee->basic_pay;
                                    $dept['daily_allowance'] += $employee->daily_allowance;
                                    $dept['semi_monthly_allowance'] += $employee->semi_monthly_allowance;
                                    $dept['late_eq'] += $employee->late_eq;
                                    $dept['late_eq_amount'] += $employee->late_eq_amount;
                                    $dept['under_time'] += $employee->under_time;
                                    $dept['under_time_amount'] += $employee->under_time_amount;
                                    $dept['vl_wpay'] += $employee->vl_wpay;
                                    $dept['vl_wpay_amount'] += $employee->vl_wpay_amount;
                                    $dept['sl_wpay'] += $employee->sl_wpay;
                                    $dept['sl_wpay_amount'] += $employee->sl_wpay_amount;
                                    $dept['bl_wpay'] += $employee->bl_wpay;
                                    $dept['bl_wpay_amount'] += $employee->bl_wpay_amount;
                                    $dept['absences'] += $employee->absences;
                                    $dept['absences_amount'] += $employee->absences_amount;

                                    $dept['gross_pay'] += $employee->gross_pay;
                                    $dept['gross_total'] += $employee->gross_total;

                                    foreach($headers as $key => $val)
                                    {
                                        $dept[$key] += $employee->{$key};
                                    }

                                    foreach($compensation as $comp)
                                    {
                                        $deptComp[$comp->id] += (array_key_exists($comp->id,$employee->otherEarnings)) ? $employee->otherEarnings[$comp->id] : 0;
                                    }

                                    foreach($govLoan as $gkey => $glabel)
                                    {
                                        $govDed[$glabel->id] += (array_key_exists($glabel->id,$employee->loans)) ? $employee->loans[$glabel->id] : 0;
                                    }

                                    foreach($deductionLabel as $key => $label)
                                    {
                                        $compDed[$label->id] +=(array_key_exists($label->id,$employee->deductions)) ? $employee->deductions[$label->id] : 0;
                                    }

                                    $dept['sss'] += $employee->gov_deductions['SSS Premium'];
                                    $dept['wisp'] += $employee->gov_deductions['SSS WISP'];
                                    $dept['phic'] += $employee->gov_deductions['PhilHealt Premium'];
                                    $dept['hdmf'] += $employee->gov_deductions['PAG IBIG Contri'];

                                    $dept['total_deduction'] += $employee->total_deduction;
                                    $dept['net_pay'] += $employee->net_pay;

                                    $divTotal['basic_salary'] += $employee->basic_salary;
                                    $divTotal['daily_rate'] += $employee->daily_rate;
                                    $divTotal['mallowance'] += $employee->mallowance;
                                    $divTotal['dallowance'] += $employee->dallowance;
                                    $divTotal['ndays'] += $employee->ndays;
                                    $divTotal['basic_pay'] += $employee->basic_pay;
                                    $divTotal['daily_allowance'] += $employee->daily_allowance;
                                    $divTotal['semi_monthly_allowance'] += $employee->semi_monthly_allowance;
                                    $divTotal['late_eq'] += $employee->late_eq;
                                    $divTotal['late_eq_amount'] += $employee->late_eq_amount;
                                    $divTotal['under_time'] += $employee->under_time;
                                    $divTotal['under_time_amount'] += $employee->under_time_amount;
                                    $divTotal['vl_wpay'] += $employee->vl_wpay;
                                    $divTotal['vl_wpay_amount'] += $employee->vl_wpay_amount;
                                    $divTotal['sl_wpay'] += $employee->sl_wpay;
                                    $divTotal['sl_wpay_amount'] += $employee->sl_wpay_amount;
                                    $divTotal['bl_wpay'] += $employee->bl_wpay;
                                    $divTotal['bl_wpay_amount'] += $employee->bl_wpay_amount;
                                    $divTotal['absences'] += $employee->absences;
                                    $divTotal['absences_amount'] += $employee->absences_amount;
                                    $divTotal['gross_pay'] += $employee->gross_pay;
                                    $divTotal['gross_total'] += $employee->gross_total;

                                    foreach($headers as $key => $val)
                                    {
                                        $deptDIV[$key] += $employee->{$key};
                                    }

                                    foreach($compensation as $comp)
                                    {
                                        $deptCompDIV[$comp->id]  += (array_key_exists($comp->id,$employee->otherEarnings)) ? $employee->otherEarnings[$comp->id] : 0;
                                    }

                                    $divTotal['sss'] += $employee->gov_deductions['SSS Premium'];
                                    $divTotal['wisp'] += $employee->gov_deductions['SSS WISP'];
                                    $divTotal['phic'] += $employee->gov_deductions['PhilHealt Premium'];
                                    $divTotal['hdmf'] += $employee->gov_deductions['PAG IBIG Contri'];

                                    $dept['total_deduction'] += $employee->total_deduction;
                                    $dept['net_pay'] += $employee->net_pay;

                                    foreach($govLoan as $gkey => $glabel)
                                    {
                                        $govDedDIV[$glabel->id] += (array_key_exists($glabel->id,$employee->loans)) ? $employee->loans[$glabel->id] : 0;
                                    }
                                    
                                    foreach($deductionLabel as $key => $label)
                                    {
                                        $compDedDIV[$label->id] +=(array_key_exists($label->id,$employee->deductions)) ? $employee->deductions[$label->id] : 0;
                                    }

                                    $divTotal['total_deduction'] += $employee->total_deduction;
                                    $divTotal['net_pay'] += $employee->net_pay;

                                ?>
                              
                                <tr >
                                    <td> {{ $ctr++ }} </td>
                                    <td> {{ $employee->biometric_id }} </td> 
                                    <td > {{ $employee->employee_name }} </td> 
                                    <td > {{ $employee->basic_salary }}</td>
                                    <td > {{ $employee->daily_rate }}</td>

                                    <td > {{ ($employee->mallowance>0) ? $employee->mallowance : '' }}</td>
                                    <td > {{ ($employee->dallowance>0) ? $employee->dallowance : '' }}</td>
                                    
                                    <td > {{ $employee->ndays }}</td>
                                    <td > {{ $employee->basic_pay }}</td>

                                    <td > {{ ($employee->daily_allowance>0) ? $employee->daily_allowance : ''; }}</td>
                                    <td > {{ ($employee->semi_monthly_allowance>0) ? $employee->semi_monthly_allowance : ''; }}</td>

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
                                       
                                        <td  > {{ (array_key_exists($comp->id,$employee->otherEarnings)) ? $employee->otherEarnings[$comp->id] : ''; }}</td>
                                       
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

                                <?php
                                    
                                ?>
                            @endforeach
                            <tr>
                                <td>TOTAL</td>
                                <td></td>
                                <td></td>
                                <td>{{$dept['basic_salary']}}</td>
                                <td>{{ $dept['daily_rate'] }}</td>
                                <td>{{ $dept['mallowance'] }}</td>
                                <td>{{ $dept['dallowance'] }}</td>
                                <td>{{ $dept['ndays'] }}</td>
                                <td>{{ $dept['basic_pay'] }}</td>
                                <td>{{ $dept['daily_allowance'] }}</td>
                                <td>{{ $dept['semi_monthly_allowance'] }}</td>
                                <td>{{ $dept['late_eq'] }}</td>
                                <td>{{ $dept['late_eq_amount'] }}</td>
                                <td>{{ $dept['under_time'] }}</td>
                                <td>{{ $dept['under_time_amount'] }}</td>
                                <td>{{ $dept['vl_wpay'] }}</td>
                                <td>{{ $dept['vl_wpay_amount'] }}</td>
                                <td>{{ $dept['sl_wpay'] }}</td>
                                <td>{{ $dept['sl_wpay_amount'] }}</td>
                                <td>{{ $dept['bl_wpay'] }}</td>
                                <td>{{ $dept['bl_wpay_amount'] }}</td>
                                <td>{{ $dept['absences_amount'] }}</td>
                                <td>{{ $dept['absences_amount'] }}</td>
                                @foreach($headers as $key => $val)
                                   <td> {{ $dept[$key] }} </td>
                                @endforeach
                                <td>{{ $dept['gross_pay'] }}</td>
                                @foreach($compensation as $comp)
                                    <td> {{ $deptComp[$comp->id] }} </td>   
                                @endforeach
                                <td>{{ $dept['gross_total'] }}</td>

                                <td>{{ $dept['sss'] }}</td>
                                <td>{{ $dept['wisp'] }}</td>
                                <td>{{ $dept['phic'] }}</td>
                                <td>{{ $dept['hdmf'] }}</td>
                                @foreach($govLoan as $gkey => $glabel)
                                    <td  >{{ $govDed[$glabel->id] }}</td>
                                @endforeach

                                @foreach($deductionLabel as $key => $label)
                                    <td  >{{ $compDed[$label->id] }}</td>
                                @endforeach
                                    <td>{{ $dept['total_deduction'] }}</td>
                                    <td>{{ $dept['net_pay'] }}</td>
                            </tr>
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