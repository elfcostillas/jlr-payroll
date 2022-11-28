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
        <table style="border-collapse:collapse;white-space:nowrap" border=1 >
            <thead>
                <tr>
                        <th style="padding : 0px 4px;" > Bioc ID</th>
                        <th style="padding : 0px 4px;" >Name</th>
                        <th style="padding : 0px 4px;" >Basic Rate</th>
                        <th style="padding : 0px 4px;" >Daily Rate</th>
                        @foreach($headers as $key => $val)
                            <th style="padding : 0px 4px;" >{{ $labels[$key] }}</th>
                            @php $colspan++; @endphp
                        @endforeach
                </tr>
            </thead>
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
                                    <td> {{ $employee->biometric_id }} </td> 
                                    <td> {{ $employee->employee_name }} </td> 
                                    <td style="text-align:right;"> {{ number_format($employee->basic_salary,2) }}</td>
                                    <td style="text-align:right;"> {{ number_format($employee->daily_rate,2) }}</td>
                                    @foreach($headers as $key => $val)
                                        <td style="text-align:right;">{{ $employee->$key}}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
            @endforeach
        </table>
        @if(count($no_pay)>0)
        <table border="1" style="border-collapse:collapse;margin-top : 12px;">
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