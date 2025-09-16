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
                font-size : 10pt;
            }

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

            tbody th:first-child {
                left: 0;
                z-index: 1;
                
            } 

            thead th {
                position: -webkit-sticky; /* for Safari */
                position: sticky;
                top: 0;
                background: #ebecf0;
                color: #000;
            
            }

            tbody th {
                position: -webkit-sticky; /* for Safari */
                position: sticky;
                left: 0;
                background: #fff; /* dont remove */
                /* border-right: 1px solid #CCC; */
                
            }

            .location {
                background-color:  #0096FF;
            }

            .division {
                background-color:  #FFFF00;
            }

            .department {
                background-color:  #BEBEBE;
            }

            td {
                padding : 4px;
            }

    </style>
</head>
<body>
    {{-- <table>
        <tr>
            <td>Last name</td>
            <td>First Name</td>
        </tr>
        @foreach($employees as $employee)
            <tr>
                <td> {{ $employee->lastname }} </td>
                <td> {{ $employee->firstname }} </td>
            </tr>
        @endforeach
    </table> --}}
    {{-- <div id="container2" >
        <table style="width:4240px;white-space:nowrap;border-collapse:collapse;" border=1>
            <thead>
                <tr>
                    @for($x=1;$x<=50;$x++)
                        <th style="width:140px"> {{ $x }} </th>

                    @endfor
                </tr>
            </thead>
            <tbody>
                @for($y=1;$y<=100;$y++)
                <tr>
                    <th> Costillas, Elmer </th>
                    @for($x=1;$x<=49;$x++)
                        <td style="width:140px"> {{ $x }} </td>

                    @endfor
                </tr>
                @endfor
            </tbody>
        </table>
    </div> --}}
    <?php
        $colspan=4;
    ?>
    <div id="" >
        <table style="border-collapse:collapse;" border=1 >
            @foreach($data as $location)
                <tr>
                    <td colspan={{$colspan}} class="location"> {{ $location->location_name }} </td>
                </tr>
                    @foreach($location->divisions as $division)  
                        <tr>
                            <td colspan={{$colspan}}  class="division"> {{ $division->div_name }} </td>
                        </tr>
                        @foreach($division->departments as $department)
                            <tr>
                                <td colspan={{$colspan}}  class="department"> {{ $department->dept_name }} </td>
                            </tr>
                            @foreach($department->employees as $employee)
                                <?php//  dd($employee); ?>
                                <tr style="vertical-align: top;">
                                    <td> {{ $employee->biometric_id }} </td> <td> {{ $employee->employee_name }} </td>
                                    <td>
                                        @foreach($employee->basicEarnings as $basic)
                                            <span style="display:inline-block;width:200px;"> {{ $basic->name }} </span> <span style="display:inline-block;width:90px;text-align:right;"> {{ $basic->hours }} </span><span style="display:inline-block;width:110px;text-align:right;"> {{ number_format($basic->amount,2) }} </span><br>    
                                            
                                        @endforeach

                                        @foreach($employee->otherEarnings as $other)
                                            <span style="display:inline-block;width:200px;"> {{ $other->description }} </span> <span style="display:inline-block;width:90px;text-align:right;"> </span><span style="display:inline-block;width:110px;text-align:right;"> {{ number_format($other->amount,2) }} </span><br>    
                                            
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($employee->gov_deductions as $key => $prem)
                                            @if($prem>0)
                                            <span style="display:inline-block;width:200px;"> {{ $key }} </span> <span style="display:inline-block;width:90px;text-align:right;"> </span><span style="display:inline-block;width:110px;text-align:right;"> {{ number_format($prem,2) }} </span><br>    
                                            @endif
                                        @endforeach
                                        @foreach($employee->deductions as $deduction)
                                            <span style="display:inline-block;width:200px;"> {{ $deduction->description }} </span> <span style="display:inline-block;width:90px;text-align:right;"> </span><span style="display:inline-block;width:110px;text-align:right;"> {{ number_format($deduction->amount,2) }} </span><br>
                                        @endforeach
                                        @foreach($employee->loans as $loan)
                                            <span style="display:inline-block;width:200px;"> {{ $loan->description }} </span> <span style="display:inline-block;width:90px;text-align:right;"> </span><span style="display:inline-block;width:110px;text-align:right;"> {{ number_format($loan->amount,2) }} </span><br>    
                                        @endforeach
                                        @foreach($employee->absences as $absences)
                                            <span style="display:inline-block;width:200px;"> {{ $absences->name }} </span> <span style="display:inline-block;width:90px;text-align:right;"> {{ $absences->hours }} </span><span style="display:inline-block;width:110px;text-align:right;"> {{ number_format($absences->amount,2) }} </span><br>    
                                        @endforeach

                                       
                                      
                                        
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
            @endforeach
        </table>
        @if(count($no_pay)>0)
        <table border="1" style="border-collapse:collapse;margin-top : 12px">
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