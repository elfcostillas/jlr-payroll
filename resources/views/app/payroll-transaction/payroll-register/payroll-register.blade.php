<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
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
    <div id="container2" >
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
    </div>
</body>
</html>