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

            .green {
                color : green;
            }

            .red {
                color : red;
            }
    </style>
</head>
<body>
 
    <?php
        $cols = 2;
        // $cols = 6 + count($data->basic_cols) + count($data->gross_cols) + count($data->fixed_comp_hcols) 
        //         + count($data->other_comp_hcols) + count($data->contri) + count($data->deduction_hcols)
        //         + count($data->govloans_hcols);

        $grandCtr = 0;

        $ctr2 = 1;
        $ctr3 = 1;

       


    //   <img src="{{ public_path('images/header-logo.jpg') }}" style="height:24px;" class="center" >
    ?>
    <div id="" >
        <div>
        
        </div>
        <table>
            <tr >
                <td style="height:92px;"></td>
            </tr>
            <tr>
                <td style="font-weight:bold;" >JLR Construction and Aggregates Inc.</td>
            </tr>
            <tr>
                <td> Date / Time Printed : {{ now()->format('m/d/Y H:i:s') }}</td>
            </tr>
            <tr>
                <td> {{ $label }}</td>
            </tr>
        </table>

        <table >
            <tr>
                <td>SUMMARY 1</td>
            </tr>
            <tr>
                <td >No.</td>
                <td >Name</td>
                <td >Period Covered</td>
                <td >Dept</td>
                <td >Basic Salary (Reg Hours)</td>
                <td >BELS</td>
                <td >OT REGULAR</td>
                @foreach ($data->fixed_comp_hcols as $fxcols)
                    <td>{{ $fxcols->description }}</td>
                @endforeach

                @foreach ($data->other_comp_hcols as $othcols)
                    <td>{{ $othcols->description }}</td>
                @endforeach
                <td>GROSS PAY</td>
                <td>Absences / Tardeness</td>
                @if ($data->contri)
                    @foreach ($data->contri as $contricols)
                        <td >{{ $contricols->col_label }}</td>
                    @endforeach
                @endif
                @if ($data->deduction_hcols)
                    @foreach ($data->deduction_hcols as $deduction_cols)
                        <td>{{ $deduction_cols->description }}</td>
                    @endforeach
                @endif
                @if ($data->govloans_hcols)
                    @foreach ($data->govloans_hcols as $govloans_hcols)
                        <td>{{ $govloans_hcols->description }}</td>
                    @endforeach
                @endif
                <td>Total Deduction</td>
                <td>Net Pay</td>
                
            </tr>
            @foreach ($data->data as $division)
 
                    @foreach ($division->departments as $department)
                       
                        @php
                            $ctr = 1;
                        @endphp
                    
                        @foreach ($department->employees as $employee)
                            <tr>
                                <td style="">{{ $ctr++ }}</td>
                                <td> {{$employee->lastname}}, {{ $employee->firstname }} </td>
                                <td>{{ $label2 }}</td>
                                <td> {{ $employee->dept_label }}</td>
                                <td> {{ $payroll->getBasicPay($employee) }}</td>
                                <td> {{ $employee->semi_monthly_allowance }}</td>
                                <td> {{ $employee->reg_ot_amount }}</td>
                                @foreach ($data->fixed_comp_hcols as $fxcols) <!-- Fixed Compensation -->
                                    <td class="r"> {{ (array_key_exists($fxcols->compensation_type,$employee->other_earning) ? $employee->other_earning[$fxcols->compensation_type] : '') }}
                                @endforeach

                                @foreach ($data->other_comp_hcols as $othcols) <!-- Other Compensation -->
                                    <td class="r"> {{ (array_key_exists($othcols->compensation_type,$employee->other_earning) ? $employee->other_earning[$othcols->compensation_type] : '') }}
                                        
                                @endforeach
                                <!-- <td  class="{{ ($payroll->getGrossPay($employee)==$employee->gross_total+$payroll->getTardyAbsenceUT($employee)) ? 'green' : 'red' }}"  > {{ $payroll->getGrossPay($employee) }} = {{ $employee->gross_total + $payroll->getTardyAbsenceUT($employee)  }}</td> -->
                                <td > {{ $payroll->getGrossPay($employee) }} </td>
                                <td> {{ $payroll->getTardyAbsenceUT($employee) }} </td>
                                @if ($data->contri)
                                    @foreach ($data->contri as $contri_cols)
                                       <td>  {{ $employee->{$contri_cols->var_name} }}</td>
                                    @endforeach
                                @endif
                                
                                @if ($data->deduction_hcols)
                                    @foreach ($data->deduction_hcols as $deduction_hcols)
                                        <td> {{ array_key_exists($deduction_hcols->id,$employee->deductions) ? $employee->deductions[$deduction_hcols->id] : '' }}
                                        
                                    @endforeach
                                @endif
                                @if ($data->govloans_hcols)
                                    @foreach ($data->govloans_hcols as $govloans_hcols)
                                        <td> {{ array_key_exists($govloans_hcols->id,$employee->gov_loans) ? $employee->gov_loans[$govloans_hcols->id] : '' }}
                                    @endforeach
                                @endif
                                <td>{{ $payroll->getTotalDeduction($employee) }} </td>
                                <td  >{{ $employee->net_pay }}</td>
                                
                               
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td > {{ $department->dept_name }} </td>
                            <td>{{ $label2 }}</td>
                            <td> {{ $department->dept_name }} ({{ count($department->employees) }}) </td>
                            <td> {{ $payroll->getDepartmentTotal('basic_pay',$department) }}</td>
                            <td> {{ $payroll->getDepartmentTotal('semi_monthly_allowance',$department) }}</td>
                            <td> {{ $payroll->getDepartmentTotal('reg_ot_amount',$department) }}</td>
                            @foreach ($data->fixed_comp_hcols as $fxcols) <!-- Fixed Compensation -->
                                <td> {{ $payroll->getDepartmentTotalCompensation($fxcols,$department) }} </td>
                            @endforeach

                            @foreach ($data->other_comp_hcols as $othcols)
                                <td> {{ $payroll->getDepartmentTotalCompensation($othcols,$department) }} </td>
                            @endforeach
                            <td>
                                {{  $payroll->getDepartmentTotal('gross_total',$department) }}
                            </td>
                            <td>
                                {{  $payroll->getDepartmentTotal('tardyAbsence',$department) }}
                            </td>
                            @if ($data->contri)
                                @foreach ($data->contri as $contri_cols)
                                    <td>  {{ $payroll->getDeptTotalContri($contri_cols->var_name,$department) }}</td>
                                @endforeach
                            @endif

                            @if ($data->deduction_hcols)
                                @foreach ($data->deduction_hcols as $deduction_hcols)
                                    <td> {{ $payroll->getDeptDeduction($deduction_hcols,$department) }}
                                    
                                @endforeach
                            @endif

                            @if ($data->govloans_hcols)
                                @foreach ($data->govloans_hcols as $govloans_hcols)
                                    <td> {{ $payroll->getDeptGovLoan($govloans_hcols,$department)  }}
                                @endforeach
                            @endif
                            <td> {{  $payroll->getDepartmentTotal('total_deduction',$department) }} </td>
                            <td> {{  $payroll->getDepartmentTotal('net_pay',$department) }} </td>

                        </tr>
                    @endforeach
                    
            @endforeach
                <!-- GRAND TOTAL -->
            <tr style="color:red;font-weight:bold;">
                <td></td>
                <td style="padding-left:42px"> GRAND TOTAL </td>
                <td>{{ $label2 }}</td>
                <td> ({{ $payroll->getOverAllCount($data->data) }}) </td>
                <td> {{ $payroll->getOverAllTotal('basic_pay',$data->data) }}</td>
                <td> {{ $payroll->getOverAllTotal('semi_monthly_allowance',$data->data) }}</td>
                    <td> {{ $payroll->getOverAllTotal('reg_ot_amount',$data->data) }}</td>
                    @foreach ($data->fixed_comp_hcols as $fxcols)
                        <td> {{ $payroll->getOverAllTotalCompensation($fxcols,$data->data) }} </td>
                    @endforeach

                    @foreach ($data->other_comp_hcols as $othcols)
                        <td> {{ $payroll->getOverAllTotalCompensation($othcols,$data->data) }} </td>
                    @endforeach
                    <td>
                        {{  $payroll->getOverAllTotal('gross_total',$data->data) }}
                    </td>
                    <td>
                        {{  $payroll->getOverAllTotal('tardyAbsence',$data->data) }}
                    </td>
                    @if ($data->contri)
                        @foreach ($data->contri as $contri_cols)
                            <td>  {{ $payroll->getTotalContri($contri_cols->var_name,$data->data) }}</td>
                        @endforeach
                    @endif

                    @if ($data->deduction_hcols)
                        @foreach ($data->deduction_hcols as $deduction_hcols)
                            <td> {{ $payroll->getOverAllDeduction($deduction_hcols,$data->data) }}
                            
                        @endforeach
                    @endif

                    @if ($data->govloans_hcols)
                        @foreach ($data->govloans_hcols as $govloans_hcols)
                            <td> {{ $payroll->getOverAllALoan($govloans_hcols,$data->data)  }}
                        @endforeach
                    @endif
                    <td> {{  $payroll->getOverAllTotal('total_deduction',$data->data) }} </td>
                    <td> {{  $payroll->getOverAllTotal('net_pay',$data->data) }} </td>
            </tr>

               
        </table>

        <table >
            <tr>
                <td>SUMMARY 2</td>
            </tr>
            <tr>
                <td >No.</td>
                <td >Name</td>
                <td >Period Covered</td>
                <td >Dept</td>
                <td >Basic Salary (Reg Hours)</td>
                <td >BELS</td>
                <td >OT REGULAR</td>
                @foreach ($data->fixed_comp_hcols as $fxcols)
                    <td>{{ $fxcols->description }}</td>
                @endforeach

                @foreach ($data->other_comp_hcols as $othcols)
                    <td>{{ $othcols->description }}</td>
                @endforeach
                <td>GROSS PAY</td>
                <td>Absences / Tardeness</td>
                @if ($data->contri)
                    @foreach ($data->contri as $contricols)
                        <td >{{ $contricols->col_label }}</td>
                    @endforeach
                @endif
                @if ($data->deduction_hcols)
                    @foreach ($data->deduction_hcols as $deduction_cols)
                        <td>{{ $deduction_cols->description }}</td>
                    @endforeach
                @endif
                @if ($data->govloans_hcols)
                    @foreach ($data->govloans_hcols as $govloans_hcols)
                        <td>{{ $govloans_hcols->description }}</td>
                    @endforeach
                @endif
                <td>Total Deduction</td>
                <td>Net Pay</td>
                
            </tr>
            @foreach ($data->data as $division)
               
                    @foreach ($division->departments as $department)
                    
                        <tr>
                            <td>{{ $ctr2 }}</td>
                            <td style="padding-left:42px"> {{ $division->div_name }} - {{ $department->dept_name }} </td>
                            <td>{{ $label2 }}</td>
                            <td> {{ $department->dept_name }} ({{ count($department->employees) }}) </td>
                            <td> {{ $payroll->getDepartmentTotal('basic_pay',$department) }}</td>
                            <td> {{ $payroll->getDepartmentTotal('semi_monthly_allowance',$department) }}</td>
                            <td> {{ $payroll->getDepartmentTotal('reg_ot_amount',$department) }}</td>
                            @foreach ($data->fixed_comp_hcols as $fxcols) <!-- Fixed Compensation -->
                                <td> {{ $payroll->getDepartmentTotalCompensation($fxcols,$department) }} </td>
                            @endforeach

                            @foreach ($data->other_comp_hcols as $othcols)
                                <td> {{ $payroll->getDepartmentTotalCompensation($othcols,$department) }} </td>
                            @endforeach
                            <td>
                                {{  $payroll->getDepartmentTotal('gross_total',$department) }}
                            </td>
                            <td>
                                {{  $payroll->getDepartmentTotal('tardyAbsence',$department) }}
                            </td>
                            @if ($data->contri)
                                @foreach ($data->contri as $contri_cols)
                                    <td>  {{ $payroll->getDeptTotalContri($contri_cols->var_name,$department) }}</td>
                                @endforeach
                            @endif

                            @if ($data->deduction_hcols)
                                @foreach ($data->deduction_hcols as $deduction_hcols)
                                    <td> {{ $payroll->getDeptDeduction($deduction_hcols,$department) }}
                                    
                                @endforeach
                            @endif

                            @if ($data->govloans_hcols)
                                @foreach ($data->govloans_hcols as $govloans_hcols)
                                    <td> {{ $payroll->getDeptGovLoan($govloans_hcols,$department)  }}
                                @endforeach
                            @endif
                            <td> {{  $payroll->getDepartmentTotal('total_deduction',$department) }} </td>
                            <td> {{  $payroll->getDepartmentTotal('net_pay',$department) }} </td>

                        </tr> 
                        @php
                            $ctr2++;

                        @endphp
                    @endforeach
            @endforeach
            <tr style="color:red;font-weight:bold;">
                <td></td>
                <td style="padding-left:42px"> GRAND TOTAL </td>
                <td>{{ $label2 }}</td>
                <td> ({{ $payroll->getOverAllCount($data->data) }}) </td>
                <td> {{ $payroll->getOverAllTotal('basic_pay',$data->data) }}</td>
                <td> {{ $payroll->getOverAllTotal('semi_monthly_allowance',$data->data) }}</td>
                    <td> {{ $payroll->getOverAllTotal('reg_ot_amount',$data->data) }}</td>
                    @foreach ($data->fixed_comp_hcols as $fxcols)
                        <td> {{ $payroll->getOverAllTotalCompensation($fxcols,$data->data) }} </td>
                    @endforeach

                    @foreach ($data->other_comp_hcols as $othcols)
                        <td> {{ $payroll->getOverAllTotalCompensation($othcols,$data->data) }} </td>
                    @endforeach
                    <td>
                        {{  $payroll->getOverAllTotal('gross_total',$data->data) }}
                    </td>
                    <td>
                        {{  $payroll->getOverAllTotal('tardyAbsence',$data->data) }}
                    </td>
                    @if ($data->contri)
                        @foreach ($data->contri as $contri_cols)
                            <td>  {{ $payroll->getTotalContri($contri_cols->var_name,$data->data) }}</td>
                        @endforeach
                    @endif

                    @if ($data->deduction_hcols)
                        @foreach ($data->deduction_hcols as $deduction_hcols)
                            <td> {{ $payroll->getOverAllDeduction($deduction_hcols,$data->data) }}
                            
                        @endforeach
                    @endif

                    @if ($data->govloans_hcols)
                        @foreach ($data->govloans_hcols as $govloans_hcols)
                            <td> {{ $payroll->getOverAllALoan($govloans_hcols,$data->data)  }}
                        @endforeach
                    @endif
                    <td> {{  $payroll->getOverAllTotal('total_deduction',$data->data) }} </td>
                    <td> {{  $payroll->getOverAllTotal('net_pay',$data->data) }} </td>
            </tr>
            
        </table>

        <table id="main" border=1 style="width:100%;border-collapse:collapse;font-size :5pt;">
            <tr>
                <td>SUMMARY 3</td>
            </tr>
            <tr>
                <td >No.</td>
                <td >Name</td>
                <td >Period Covered</td>
                <td >Dept</td>
                <td >Basic Salary (Reg Hours)</td>
                <td >BELS</td>
                <td >OT REGULAR</td>
                @foreach ($data->fixed_comp_hcols as $fxcols)
                    <td>{{ $fxcols->description }}</td>
                @endforeach

                @foreach ($data->other_comp_hcols as $othcols)
                    <td>{{ $othcols->description }}</td>
                @endforeach
                <td>GROSS PAY</td>
                <td>Absences / Tardeness</td>
                @if ($data->contri)
                    @foreach ($data->contri as $contricols)
                        <td >{{ $contricols->col_label }}</td>
                    @endforeach
                @endif
                @if ($data->deduction_hcols)
                    @foreach ($data->deduction_hcols as $deduction_cols)
                        <td>{{ $deduction_cols->description }}</td>
                    @endforeach
                @endif
                @if ($data->govloans_hcols)
                    @foreach ($data->govloans_hcols as $govloans_hcols)
                        <td>{{ $govloans_hcols->description }}</td>
                    @endforeach
                @endif
                <td>Total Deduction</td>
                <td>Net Pay</td>
                
            </tr>
            @foreach ($data->data as $division)
               
                   
                        <tr>
                           <td>{{ $ctr3 }}</td>
                            <td style="padding-left:42px"> {{ $division->div_name }}  </td>
                            <td>{{ $label2 }}</td>
                            <td>  </td>
                            <td> {{ $payroll->getOverAllTotalDivision('basic_pay',$division) }}</td>
                            <td> {{ $payroll->getOverAllTotalDivision('semi_monthly_allowance',$division) }}</td>
                            <td> {{ $payroll->getOverAllTotalDivision('reg_ot_amount',$division) }}</td>
                            @foreach ($data->fixed_comp_hcols as $fxcols) <!-- Fixed Compensation -->
                                <td> {{ $payroll->getOverAllTotalCompensationDivision($fxcols,$division) }} </td>
                            @endforeach

                            @foreach ($data->other_comp_hcols as $othcols)
                                <td> {{ $payroll->getOverAllTotalCompensationDivision($othcols,$division) }} </td>
                            @endforeach
                            <td>
                                {{  $payroll->getOverAllTotalDivision('gross_total',$division) }}
                            </td>
                            <td>
                                {{  $payroll->getOverAllTotalDivision('tardyAbsence',$division) }}
                            </td>
                            @if ($data->contri)
                                @foreach ($data->contri as $contri_cols)
                                    <td>  {{ $payroll->getTotalContriDivision($contri_cols->var_name,$division) }}</td>
                                @endforeach
                            @endif

                            @if ($data->deduction_hcols)
                                @foreach ($data->deduction_hcols as $deduction_hcols)
                                    <td> {{ $payroll->getOverAllDeductionDivision($deduction_hcols,$division) }}
                                    
                                @endforeach
                            @endif

                            @if ($data->govloans_hcols)
                                @foreach ($data->govloans_hcols as $govloans_hcols)
                                    <td> {{ $payroll->getOverAllALoanDivision($govloans_hcols,$division)  }}
                                @endforeach
                            @endif
                            <td> {{  $payroll->getOverAllTotalDivision('total_deduction',$division) }} </td>
                            <td> {{  $payroll->getOverAllTotalDivision('net_pay',$division) }} </td>

                        </tr>
                  
                    @php
                        $ctr3++;

                    @endphp
                   
            @endforeach
            <tr style="color:red;font-weight:bold;">
                <td></td>
                <td style="padding-left:42px"> GRAND TOTAL </td>
                <td>{{ $label2 }}</td>
                <td> ({{ $payroll->getOverAllCount($data->data) }}) </td>
                <td> {{ $payroll->getOverAllTotal('basic_pay',$data->data) }}</td>
                <td> {{ $payroll->getOverAllTotal('semi_monthly_allowance',$data->data) }}</td>
                    <td> {{ $payroll->getOverAllTotal('reg_ot_amount',$data->data) }}</td>
                    @foreach ($data->fixed_comp_hcols as $fxcols)
                        <td> {{ $payroll->getOverAllTotalCompensation($fxcols,$data->data) }} </td>
                    @endforeach

                    @foreach ($data->other_comp_hcols as $othcols)
                        <td> {{ $payroll->getOverAllTotalCompensation($othcols,$data->data) }} </td>
                    @endforeach
                    <td>
                        {{  $payroll->getOverAllTotal('gross_total',$data->data) }}
                    </td>
                    <td>
                        {{  $payroll->getOverAllTotal('tardyAbsence',$data->data) }}
                    </td>
                    @if ($data->contri)
                        @foreach ($data->contri as $contri_cols)
                            <td>  {{ $payroll->getTotalContri($contri_cols->var_name,$data->data) }}</td>
                        @endforeach
                    @endif

                    @if ($data->deduction_hcols)
                        @foreach ($data->deduction_hcols as $deduction_hcols)
                            <td> {{ $payroll->getOverAllDeduction($deduction_hcols,$data->data) }}
                            
                        @endforeach
                    @endif

                    @if ($data->govloans_hcols)
                        @foreach ($data->govloans_hcols as $govloans_hcols)
                            <td> {{ $payroll->getOverAllALoan($govloans_hcols,$data->data)  }}
                        @endforeach
                    @endif
                    <td> {{  $payroll->getOverAllTotal('total_deduction',$data->data) }} </td>
                    <td> {{  $payroll->getOverAllTotal('net_pay',$data->data) }} </td>
            </tr>
            
        </table>
       
</body>
</html>