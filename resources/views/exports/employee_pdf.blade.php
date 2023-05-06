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
    <h2>Employee List</h2>
    <table>
        <thead>
            <tr> 
            
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>CIN</th>
                <th>Address</th>
                <th>Birthday</th>
               
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                             <td>{{ $employee->first_name }}</td>
                <td>{{ $employee->last_name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->phone_number }}</td>
                <td>{{ $employee->cin }}</td>
                <td>{{ $employee->adress }}</td>
                <td>{{ $employee->birthday }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
