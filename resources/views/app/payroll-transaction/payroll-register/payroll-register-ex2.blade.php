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
        $cols = 0;
        // $cols = 6 + count($data->basic_cols) + count($data->gross_cols) + count($data->fixed_comp_hcols) 
        //         + count($data->other_comp_hcols) + count($data->contri) + count($data->deduction_hcols)
        //         + count($data->govloans_hcols);

        $grandCtr = 0;


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

        <table id="main" border=1 style="width:100%;border-collapse:collapse;font-size :5pt;">
            <tr>
                <td class="vtop c b">No.</td>
                <td class="vtop c b">Name</td>
                <td class="vtop c b">Job Title</td>
                @foreach ($data->basic_cols as $bcols)
                    <td class="vtop c b">{{ $bcols->col_label }}</td>
                @endforeach
                @foreach ($data->gross_cols as $gcols)
                    <td class="vtop c b">{{ $gcols->col_label }}</td>
                @endforeach

                @foreach ($data->fixed_comp_hcols as $fxcols)
                    <td class="vtop c b">{{ $fxcols->description }}</td>
                @endforeach

                @foreach ($data->other_comp_hcols as $othcols)
                    <td class="vtop c b">{{ $othcols->description }}</td>
                @endforeach
                <td class="vtop c b"> Gross Total </td>
                @foreach ($data->contri as $contricols)
                    <td class="vtop c b">{{ $contricols->col_label }}</td>
                @endforeach
                @if ($data->deduction_hcols)
                    @foreach ($data->deduction_hcols as $deduction_cols)
                        <td class="vtop c b">{{ $deduction_cols->description }}</td>
                    @endforeach
                @endif
                @if ($data->govloans_hcols)
                    @foreach ($data->govloans_hcols as $govloans_hcols)
                        <td class="vtop c b">{{ $govloans_hcols->description }}</td>
                    @endforeach
                @endif
                    <td class="vtop c b">Total Deduction</td>
                    <td class="vtop c b">Net Pay</td>
                
            </tr>
            @foreach ($data->data as $division)
                    <tr>
                        <td class="pad4 b" colspan={{ $cols }} style="padding-left:4px"> {{ $division->div_name }}</td>
                    </tr>
                    @foreach ($division->departments as $department)
                        <tr>
                            <td class="pad4 b" colspan={{ $cols }} style="padding-left:42px"> {{ $department->dept_name }} </td>
                        </tr>
                        @php
                            $ctr = 1;
                        @endphp
                    
                        @foreach ($department->employees as $employee)
                            <tr>
                                <td class="pad4 r" style="">{{ $ctr++ }}</td>
                                <td class="pad4"> {{$employee->lastname}}, {{ $employee->firstname }} </td>
                                <td class="pad4" style="" > {{$employee->job_title_name }} </td>
                                @foreach ($data->basic_cols as $bcols)
                                <td class="r">{{ $employee->{$bcols->var_name} }}</td>
                                @endforeach
                                @foreach ($data->gross_cols as $gcols)
                                    <td class="r">{{ $employee->{$gcols->var_name} }}</td>
                                @endforeach
                                
                                @foreach ($data->fixed_comp_hcols as $fxcols) <!-- Fixed Compensation -->
                                    <td class="r"> {{ array_key_exists($fxcols->compensation_type,$employee->other_earning) ? $employee->other_earning[$fxcols->compensation_type] : '' }}
                                @endforeach

                                @foreach ($data->other_comp_hcols as $othcols) <!-- Other Compensation -->
                                    <td class="r"> {{ array_key_exists($othcols->compensation_type,$employee->other_earning) ? $employee->other_earning[$othcols->compensation_type] : '' }}
                                        
                                @endforeach

                                <td class="r"> {{ $employee->gross_total }} </td> <!-- Gross Total-->

                                @foreach ($data->contri as $contri_cols)
                                    <td class="r">{{ $employee->{$contri_cols->var_name} }}</td>
                                @endforeach
                                @if ($data->deduction_hcols)
                                    @foreach ($data->deduction_hcols as $deduction_hcols)
                                        <td class="r"> {{ array_key_exists($deduction_hcols->id,$employee->deductions) ? $employee->deductions[$deduction_hcols->id] : '' }}
                                        
                                    @endforeach
                                @endif
                                @if ($data->govloans_hcols)
                                    @foreach ($data->govloans_hcols as $govloans_hcols)
                                        <td class="r"> {{ array_key_exists($govloans_hcols->id,$employee->gov_loans) ? $employee->gov_loans[$govloans_hcols->id] : '' }}
                                    @endforeach
                                @endif
                            
                                <td class="r"> {{ $employee->total_deduction }} </td>
                                <td class="r"> {{ $employee->net_pay }}</td>


                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" > Total Dept </td>
                            @foreach ($data->basic_cols as $bcols)
                                <td class="r b">{{ $data->computeTotalByDept($department,$bcols) }}</td>
                            @endforeach
                            @foreach ($data->gross_cols as $gcols)
                                <td class="r b">{{ $data->computeTotalByDept($department,$gcols) }}</td>
                            @endforeach
                            @foreach ($data->fixed_comp_hcols as $fxcols) <!-- Fixed Compensation -->
                                <td class="r b"> {{ $data->computeTotalOtherEarningByDept($department,$fxcols) }} </td>
                            @endforeach
                            @foreach ($data->other_comp_hcols as $othcols) <!-- Fixed Compensation -->
                                <td class="r b"> {{ $data->computeTotalOtherEarningByDept($department,$othcols) }} </td>
                            @endforeach
                            
                            <td class="r b">  {{ $data->computeTotalByDept($department,'gross_total') }} </td>
                            
                            @foreach ($data->contri as $contri_cols)
                                <td class="r b">{{ $data->computeTotalByDept($department,$contri_cols) }}</td>
                            @endforeach
                            @if ($data->deduction_hcols)
                                @foreach ($data->deduction_hcols as $deduction_hcols)
                                    <td class="r b"> {{ $data->computeTotalDeductionsByDept($department,$deduction_hcols) }} </td>
                                @endforeach
                            @endif
                            @if ($data->govloans_hcols)
                                @foreach ($data->govloans_hcols as $govloans_hcols)
                                    <td class="r b"> {{ $data->computeTotalLoansByDept($department,$govloans_hcols) }} </td>
                                @endforeach
                            @endif
                            
                            <td class="r b">  {{ $data->computeTotalByDept($department,'total_deduction') }} </td>
                            <td class="r b">  {{ $data->computeTotalByDept($department,'net_pay') }} </td>
                        </tr>
                    @endforeach
            @endforeach
               <tr>
                    <td colspan="3" > Total Overall </td>
                    @foreach ($data->basic_cols as $bcols)
                        <td class="r b">{{ $data->computeTotalByDivisionV2($data,$bcols) }}</td>
                    @endforeach
                    @foreach ($data->gross_cols as $gcols)
                        <td class="r b">{{ $data->computeTotalByDivisionV2($data,$gcols) }}</td>
                    @endforeach
                    @foreach ($data->fixed_comp_hcols as $fxcols) <!-- Fixed Compensation -->
                        <td class="r b"> {{ $data->computeTotalOtherEarningByDivisionV2($data,$fxcols) }} </td>
                    @endforeach
                    @foreach ($data->other_comp_hcols as $othcols) <!-- Fixed Compensation -->
                        <td class="r b"> {{ $data->computeTotalOtherEarningByDivisionV2($data,$othcols) }} </td>
                    @endforeach
                    <td class="r b">  {{ $data->computeTotalByDivisionV2($data,'gross_total') }} </td>

                    @foreach ($data->contri as $contri_cols)
                        <td class="r b">{{ $data->computeTotalByDivisionV2($data,$contri_cols) }}</td>
                    @endforeach
                    @if ($data->deduction_hcols)
                        @foreach ($data->deduction_hcols as $deduction_hcols)
                            <td class="r b"> {{ $data->computeTotalDeductionsByDivisionV2($data,$deduction_hcols) }} </td>
                        @endforeach
                    @endif
                    @if ($data->govloans_hcols)
                        @foreach ($data->govloans_hcols as $govloans_hcols)
                            <td class="r b"> {{ $data->computeTotalLoanByDivisionV2($data,$govloans_hcols) }} </td>
                        @endforeach
                    @endif
                   
                    <td class="r b">  {{ $data->computeTotalByDivisionV2($data,'total_deduction') }} </td>
                    <td class="r b">  {{ $data->computeTotalByDivisionV2($data,'net_pay') }} </td>
                </tr>
        </table>
       
</body>
</html>