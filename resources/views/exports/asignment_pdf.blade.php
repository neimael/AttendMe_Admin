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
    <h2>Elevator's Assignments List</h2>
    <table>
        <thead>
            <tr> 
                <th>Employee</th>
                <th>Elevator</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Start Day</th>
                <th>End Day</th>
                <th>Area</th>

            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->employee->first_name }} {{ $employee->employee->last_name }}</td>
                <td>{{ $employee->qrcode->elevator->name }} {{ $employee->qrcode->elevator->location->ville }} {{ $employee->qrcode->elevator->location->adress }}</td>
                <td>{{ $employee->start_date }}</td>
                <td>{{ $employee->end_date }}</td>
                <td>{{ $employee->time_in }}</td>
                <td>{{ $employee->time_out}}</td>
                <td>{{ $employee->qrcode->mission }}</td>

            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
