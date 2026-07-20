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

        function custom_format($n)
        {
            $blank = [0,'',null];

            if(in_array($n,$blank))
            {
                return '';
            }else{
                return $n;
            }
        }

        function convert_hrs_to_days($hrs)
        {
            return ($hrs == '' || $hrs == 0) ? '' : $hrs / 8;
        }


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

        <table id="main" border=1 style="width:100%;border-collapse:collapse;font-size :4pt;">
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

                @foreach ($data->deduction_hcols as $deduction_cols)
                    <td class="vtop c b">{{ $deduction_cols->description }}</td>
                @endforeach

                @foreach ($data->govloans_hcols as $govloans_hcols)
                    <td class="vtop c b">{{ $govloans_hcols->description }}</td>
                @endforeach

                    <td class="vtop c b">Total Deduction</td>
                    <td class="vtop c b">Net Pay</td>

                
            </tr>
                @foreach ($data->data as $location)
                    <tr>
                        <td class="pad4 b" colspan="{{ $cols }}" style="padding-left:4px"> {{ $location->location_altername2 }}</td>
                    </tr>
                    @foreach ($location->divisions as $division)
                        <tr>
                            <td class="pad4 b" colspan="{{ $cols }}" style="padding-left:4px"> {{ $division->div_name }}</td>
                        </tr>

                        @foreach ($division->departments as $department)
                            <tr>
                                <td class="pad4 b" colspan="{{ $cols }}" style="padding-left:4px"> {{ $department->dept_name }} </td>
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
                                    <td class="r">{{ custom_format($employee->{$bcols->var_name}) }}</td>
                                    @endforeach

                                    @php  
                                        // @if (in_array($gcols->var_name,['vl_wpay','sl_wpay','absences','bl_wpay','svl']))
                                    @endphp
                                    @foreach ($data->gross_cols as $gcols)
                                        @if (in_array($gcols->var_name,[]))
                                            <td class="r">{{ convert_hrs_to_days($employee->{$gcols->var_name} )}}</td>
                                        @else
                                            <td class="r">{{ custom_format($employee->{$gcols->var_name} )}}</td>
                                        @endif
                                    
                                    @endforeach

                                    @foreach ($data->fixed_comp_hcols as $fxcols) 
                                        <td class="r"> {{ (array_key_exists($fxcols->compensation_type,$employee->other_earning) ? custom_format($employee->other_earning[$fxcols->compensation_type]) : '') }}
                                    @endforeach

                                    @foreach ($data->other_comp_hcols as $othcols) 
                                        <td class="r"> {{ (array_key_exists($othcols->compensation_type,$employee->other_earning) ? custom_format($employee->other_earning[$othcols->compensation_type]) : '') }}
                                            
                                    @endforeach
                                      <td class="r"> {{ custom_format($employee->gross_total) }} </td>

                                    @foreach ($data->contri as $contri_cols)
                                        <td class="r">{{ custom_format($employee->{$contri_cols->var_name}) }}</td>
                                    @endforeach
                                    @foreach ($data->deduction_hcols as $deduction_hcols)
                                        <td class="r"> {{ (array_key_exists($deduction_hcols->id,$employee->deductions) ? custom_format($employee->deductions[$deduction_hcols->id]) : '') }}
                                        
                                    @endforeach
                                    @foreach ($data->govloans_hcols as $govloans_hcols)
                                        <td class="r"> {{ (array_key_exists($govloans_hcols->id,$employee->gov_loans) ? custom_format($employee->gov_loans[$govloans_hcols->id]) : '') }}
                                    @endforeach
                                    <td class="r"> {{ custom_format($employee->total_deduction) }} </td>
                                    <td class="r"> {{ custom_format($employee->net_pay) }}</td>

                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="b" > Department Total </td>
                                @foreach ($data->basic_cols as $bcols)
                                    <td class="r b">{{ custom_format($data->computeTotalByDept($department,$bcols)) }}</td>
                                @endforeach
                                @foreach ($data->gross_cols as $gcols)

                                    @if (in_array($gcols->var_name,['vl_wpay','sl_wpay','absences','bl_wpay','svl']))
                                        <td class="r b">{{ convert_hrs_to_days($data->computeTotalByDept($department,$gcols) )}}</td>
                                    @else
                                        <td class="r b">{{ custom_format($data->computeTotalByDept($department,$gcols) )}}</td>
                                    @endif
                                @endforeach
                                @foreach ($data->fixed_comp_hcols as $fxcols)
                                    <td class="r b"> {{ custom_format($data->computeTotalByDept($department,$fxcols)) }} </td>
                                @endforeach
                                @foreach ($data->other_comp_hcols as $othcols) 
                                    <td class="r b"> {{ custom_format($data->computeTotalByDept($department,$othcols)) }} </td>
                                @endforeach
                                <td class="r b">  {{ custom_format($data->computeTotalByDept($department,'gross_total')) }} </td>
                                @foreach ($data->contri as $contri_cols)
                                    <td class="r b">{{ custom_format($data->computeTotalByDept($department,$contri_cols)) }}</td>
                                @endforeach

                                @foreach ($data->deduction_hcols as $deduction_hcols)
                                    <td class="r b"> {{ custom_format($data->computeTotalByDept($department,$deduction_hcols)) }} </td>
                                @endforeach
                                @foreach ($data->govloans_hcols as $govloans_hcols)
                                    <td class="r b"> {{ custom_format($data->computeTotalByDept($department,$govloans_hcols)) }} </td>
                                @endforeach
                                <td class="r b">  {{ custom_format($data->computeTotalByDept($department,'total_deduction')) }} </td>
                                <td class="r b">  {{ custom_format($data->computeTotalByDept($department,'net_pay')) }} </td>
              
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="b" > Division Total </td>
                                @foreach ($data->basic_cols as $bcols)
                                    <td class="r b">{{ custom_format($data->computeTotalByDivision($division,$bcols)) }}</td>
                                @endforeach
                                @foreach ($data->gross_cols as $gcols)

                                    @if (in_array($gcols->var_name,['vl_wpay','sl_wpay','absences','bl_wpay','svl']))
                                        <td class="r b">{{ convert_hrs_to_days($data->computeTotalByDivision($division,$gcols) )}}</td>
                                    @else
                                        <td class="r b">{{ custom_format($data->computeTotalByDivision($division,$gcols) )}}</td>
                                    @endif
                                @endforeach
                                @foreach ($data->fixed_comp_hcols as $fxcols)
                                    <td class="r b"> {{ custom_format($data->computeTotalByDivision($division,$fxcols)) }} </td>
                                @endforeach
                                @foreach ($data->other_comp_hcols as $othcols) 
                                    <td class="r b"> {{ custom_format($data->computeTotalByDivision($division,$othcols)) }} </td>
                                @endforeach
                                <td class="r b">  {{ custom_format($data->computeTotalByDivision($division,'gross_total')) }} </td>
                                @foreach ($data->contri as $contri_cols)
                                    <td class="r b">{{ custom_format($data->computeTotalByDivision($division,$contri_cols)) }}</td>
                                @endforeach

                                @foreach ($data->deduction_hcols as $deduction_hcols)
                                    <td class="r b"> {{ custom_format($data->computeTotalByDivision($division,$deduction_hcols)) }} </td>
                                @endforeach
                                @foreach ($data->govloans_hcols as $govloans_hcols)
                                    <td class="r b"> {{ custom_format($data->computeTotalByDivision($division,$govloans_hcols)) }} </td>
                                @endforeach
                                <td class="r b">  {{ custom_format($data->computeTotalByDivision($division,'total_deduction')) }} </td>
                                <td class="r b">  {{ custom_format($data->computeTotalByDivision($division,'net_pay')) }} </td>
              
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="b" > Location Total </td>
                        @foreach ($data->basic_cols as $bcols)
                            <td class="r b">{{ custom_format($data->computeTotalByLocation($location,$bcols)) }}</td>
                        @endforeach
                        @foreach ($data->gross_cols as $gcols)

                            @if (in_array($gcols->var_name,['vl_wpay','sl_wpay','absences','bl_wpay','svl']))
                                <td class="r b">{{ convert_hrs_to_days($data->computeTotalByLocation($location,$gcols) )}}</td>
                            @else
                                <td class="r b">{{ custom_format($data->computeTotalByLocation($location,$gcols) )}}</td>
                            @endif
                        @endforeach
                        @foreach ($data->fixed_comp_hcols as $fxcols)
                            <td class="r b"> {{ custom_format($data->computeTotalByLocation($location,$fxcols)) }} </td>
                        @endforeach
                        @foreach ($data->other_comp_hcols as $othcols) 
                            <td class="r b"> {{ custom_format($data->computeTotalByLocation($location,$othcols)) }} </td>
                        @endforeach
                        <td class="r b">  {{ custom_format($data->computeTotalByLocation($location,'gross_total')) }} </td>
                        @foreach ($data->contri as $contri_cols)
                            <td class="r b">{{ custom_format($data->computeTotalByLocation($location,$contri_cols)) }}</td>
                        @endforeach

                        @foreach ($data->deduction_hcols as $deduction_hcols)
                            <td class="r b"> {{ custom_format($data->computeTotalByLocation($location,$deduction_hcols)) }} </td>
                        @endforeach
                        @foreach ($data->govloans_hcols as $govloans_hcols)
                            <td class="r b"> {{ custom_format($data->computeTotalByLocation($location,$govloans_hcols)) }} </td>
                        @endforeach
                        <td class="r b">  {{ custom_format($data->computeTotalByLocation($location,'total_deduction')) }} </td>
                        <td class="r b">  {{ custom_format($data->computeTotalByLocation($location,'net_pay')) }} </td>
              
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="b" > Overall Total </td>
                    @foreach ($data->basic_cols as $bcols)
                        <td class="r b">{{ custom_format($data->computeOverAll($data,$bcols)) }}</td>
                    @endforeach
                    @foreach ($data->gross_cols as $gcols)

                        @if (in_array($gcols->var_name,['vl_wpay','sl_wpay','absences','bl_wpay','svl']))
                            <td class="r b">{{ convert_hrs_to_days($data->computeOverAll($data,$gcols) )}}</td>
                        @else
                            <td class="r b">{{ custom_format($data->computeOverAll($data,$gcols) )}}</td>
                        @endif
                    @endforeach
                    @foreach ($data->fixed_comp_hcols as $fxcols)
                        <td class="r b"> {{ custom_format($data->computeOverAll($data,$fxcols)) }} </td>
                    @endforeach
                    @foreach ($data->other_comp_hcols as $othcols) 
                        <td class="r b"> {{ custom_format($data->computeOverAll($data,$othcols)) }} </td>
                    @endforeach
                    <td class="r b">  {{ custom_format($data->computeOverAll($data,'gross_total')) }} </td>
                    @foreach ($data->contri as $contri_cols)
                        <td class="r b">{{ custom_format($data->computeOverAll($data,$contri_cols)) }}</td>
                    @endforeach

                    @foreach ($data->deduction_hcols as $deduction_hcols)
                        <td class="r b"> {{ custom_format($data->computeOverAll($data,$deduction_hcols)) }} </td>
                    @endforeach
                    @foreach ($data->govloans_hcols as $govloans_hcols)
                        <td class="r b"> {{ custom_format($data->computeOverAll($data,$govloans_hcols)) }} </td>
                    @endforeach
                    <td class="r b">  {{ custom_format($data->computeOverAll($data,'total_deduction')) }} </td>
                    <td class="r b">  {{ custom_format($data->computeOverAll($data,'net_pay')) }} </td>
                </tr>
        </table>
</body>
</html>