<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table>
        <tr>
            <td>No</td>
            @foreach ($payroll->getColsFinanceTemplate() as $label => $key )
                @if(is_array($key))
                <td>
                    {{ $label }}
                </td>
                @else
                <td>
                    {{ $label }}
                </td>
                @endif
            @endforeach
        </tr>
    </table>
</body>
</html>