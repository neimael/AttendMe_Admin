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
    <h2>Today's Presence</h2>
    <table>
        <thead>
            <tr> 
                <th>Employee</th>
                <th>Elevator</th>
                <th>CheckIn</th>
                <th>CheckOut</th>
                <th>Attendance Day</th>
                
                <th>Area</th>

            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->employee->first_name }} {{ $employee->employee->last_name }}</td>
                <td>{{ $employee->qrcodes->elevator->name }} {{ $employee->qrcodes->elevator->location->ville }} {{ $employee->qrcodes->elevator->location->adress }}</td>
                <td>{{ $employee->check_in }}</td>
                <td>{{ $employee->check_out }}</td>
                <td>{{ $employee->attendance_day }}</td>
              
                <td>{{ $employee->qrcodes->mission }}</td>

            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
