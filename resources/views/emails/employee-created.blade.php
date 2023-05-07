<!DOCTYPE html>
<html>
<head>
    <title>Employee Account Created</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        h1 {
            margin-top: 0;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }
        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        li {
            margin-bottom: 10px;
        }
        .wrapper {
            max-width: 500px;
            margin: 0 auto;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #009688;
            color: #000;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1>Employee Account Created</h1>
        <p>Hello,</p>
        <p>An account has been created for you as an employee. Here are your login credentials:</p>
        <ul>
            <li><strong>Email:</strong> {{ $email }}</li>
            <li><strong>Password:</strong> {{ $password }}</li>
        </ul>
        <p>You can login using these credentials at any time.</p>
        <p>If you have any questions or concerns, please don't hesitate to contact us.</p>
     
    </div>
</body>
</html>
