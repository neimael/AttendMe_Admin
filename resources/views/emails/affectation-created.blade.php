<!DOCTYPE html>
<html>
<head>
    <title>You are assigned to a new work assignment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #000000;
        }
        h1 {
            margin-top: 0;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }
        .agenda {
            max-width: 500px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border-radius: 4px;
            padding: 20px;
        }
        .event {
            margin-bottom: 20px;
        }
        .event-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .event-details {
            margin-left: 20px;
        }
        .detail {
            margin-bottom: 5px;
        }
        .detail-label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="agenda">
        <h1>You are assigned to a new schedule</h1>
        
        <div class="event">
            <div class="event-title">Work Assignment</div>
            <div class="event-details">
                <div class="detail">
                    <span class="detail-label">Start Date:</span>
                    <span class="detail-value">{{ $startdate }}</span>
                </div>
                <div class="detail">
                    <span class="detail-label">End Date:</span>
                    <span class="detail-value">{{ $enddate }}</span>
                </div>
                <div class="detail">
                    <span class="detail-label">Check In:</span>
                    <span class="detail-value">{{ $checkin}}</span>
                </div>
                <div class="detail">
                    <span class="detail-label">Check Out:</span>
                    <span class="detail-value">{{ $checkout }}</span>
                </div>
                <div class="detail">
                    <span class="detail-label">Area:</span>
                    <span class="detail-value">{{ $area }}</span>
                </div>
              
            
        </div>
        
        <p>If you have any questions or concerns, please don't hesitate to contact us.</p>
    </div>
</body>
</html>
