<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 1px;
        }
        td {
        font-size: 10px;
    }
    </style>
</head>
<body>
    <h2>Employee Justification</h2>
    <table>
        <thead>
            <tr> 
                <th>Employee</th>
                <th>report</th>
                <th>certificate</th>
                <th>Date</th>
                

            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->employee->first_name }} {{ $employee->employee->last_name }}</td>
                <td>{{ $employee->report }}</td>
                <td>{{ $employee->certificate }}</td>
                <td>{{ $employee->created_at }}</td>
         

            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
