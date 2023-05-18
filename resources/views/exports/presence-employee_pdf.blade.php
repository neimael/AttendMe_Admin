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
    <h2 style="text-align: center;">List of Presence for {{ $employees[0]['employee'] }}</h2>
    <table>
        <thead>
            <tr> 
                <th>Elevator</th>
                <th>CheckIn</th>
                <th>CheckOut</th>
                <th>Attendance Day</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                
                <td>{{ $employee['elevator']? $employee['elevator']:"---"}}</td>
                <td>{{ $employee['check_in']? $employee['check_in']:"---"}}</td>
                <td>{{ $employee['check_out']? $employee['check_out']:"---"}}</td>
                <td>{{ $employee['day']? $employee['day']:"---"}}</td>
                <td style="color:
                @if($employee['status'] == 'On Time')
                    green;
                @elseif($employee['status'] == 'Late')
                    orange;
                @elseif($employee['status'] == 'Absent')
                    red;
                @endif">
                {{ $employee['status'] }}
            </td>
          

            

            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
