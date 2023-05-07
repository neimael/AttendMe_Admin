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
    <h2>Elevator List</h2>
    <table>
        <thead>
            <tr> 
                <th>Name</th>
                <th>location</th>    
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->location->ville }} {{ $employee->location->adress }} {{ $employee->location->latitude }} {{ $employee->location->longitude }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
