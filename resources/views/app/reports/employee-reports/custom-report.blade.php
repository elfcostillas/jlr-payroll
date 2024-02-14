<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        * {
            font-size : 10pt;
        }
    </style>
</head>
<body>
    <table border=1>
        <tr>
        @foreach($headers as $header )
            <td> {{ $header->header_label }} </td>
        @endforeach
        </tr>

        @foreach($data as $location)
            <tr>
                <td colspan={{ $headers->count() }} > {{ $location->location_name }} </td>
            </tr>
            @foreach($location->division as $division)
                <tr>
                    <td colspan={{ $headers->count() }} >{{ $division->div_name }}</td>
                </tr>
                @foreach($division->departments as $departments)
                    @if($departments->employees->count()>0)
                        <tr>
                            <td colspan={{ $headers->count() }} > {{ $departments->dept_name }} </td>
                        </tr>
                        @foreach($departments->employees as $e)
                            <tr>
                                @foreach($headers as $col)
                                    <td> {{ $e->{$col->var_name} }} </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endforeach
        @endforeach
    </table>
   
</body>
</html>