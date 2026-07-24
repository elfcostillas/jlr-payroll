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

        // $payroll->callthisfunction();

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


        <table border="1">
            <tr>
                <td>Division</td>
                <td>Department</td>
                <td>Gross Pay</td>
                
                @foreach ($data->contri as $contricols)
                <td >{{ $contricols->col_label }}</td>
                @endforeach
                @foreach ($data->deduction_hcols as $deduction_cols)
                    <td>{{ $deduction_cols->description }}</td>
                @endforeach
                @foreach ($data->govloans_hcols as $govloans_hcols)
                    <td>{{ $govloans_hcols->description }}</td>
                @endforeach
                <td>Total Deduction</td>
                <td>Net Pay</td>
            </tr>

            @foreach ($data->data as $division)
                <?php $first = true; ?>
              
                <tr>
                    <td rowspan="{{ count($division->departments) }}"> {{ $division->div_code }} </td>
                    @foreach ($division->departments as $department)
                        @if (!$first)
                            <tr>
                        @endif
                            
                            <td> {{ $department->dept_code }} </td>
                            <td> {{ $payroll->getDeptTotal($department->data,'gross_total') }} </td>

                            @foreach ($data->contri as $contricols)
                                <td >{{ $payroll->getDeptTotal($department->data,$contricols) }} </td>
                            @endforeach
                            @foreach ($data->deduction_hcols as $deduction_cols)
                                <td >{{ $payroll->getDeptTotal($department->data,$deduction_cols) }} </td>
                            @endforeach
                            @foreach ($data->govloans_hcols as $govloans_hcols)
                                <td></td>
                            @endforeach

                            <td> {{ $payroll->getDeptTotal($department->data,'total_deduction') }} </td>
                            <td> {{ $payroll->getDeptTotal($department->data,'net_pay') }} </td>
                        </tr>
                    @endforeach
                    
                </tr>
                <?php $first = false; ?>
            @endforeach
        </table>

       
       
</body>
</html>