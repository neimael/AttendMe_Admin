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
    <h2>Employee Regulations</h2>
    <table>
        <thead>
            <tr> 
                <th>Employee</th>
                <th>Attendance Day</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>status</th>
                <th>Issue Type</th>
                <th>Report</th>

            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->employee->first_name }} {{ $employee->employee->last_name }}</td>
                <td>{{ $employee->attendance_day }}</td>
                <td>{{ $employee->check_in }}</td>
                <td>{{ $employee->check_out }}</td>
                <td>{{ $employee->status }}</td>
                <td>{{ $employee->issue_type}}</td>
                <td>{{ $employee->report}}</td>

            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
