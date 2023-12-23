<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spider Tracking</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data.min.js"></script>
    
    <!-- Chart.js Moment Adapter -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0/dist/chartjs-adapter-moment.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h2>Google Spider Tracking</h2>
        <div class="row text-center">
            <div class="col-md-6 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Today's Total</h5>
                        <p class="card-text">{{ $todaysTotal }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Yesterday's Total</h5>
                        <p class="card-text">{{ $yesterdaysTotal }}</p>
                    </div>
                </div>
            </div>
        </div>
        <p>Graph showing the number of visits by Google spiders</p>

        <!-- Graph Container -->
        <div class="mt-4">
            <canvas id="spiderVisitsChart"></canvas>
        </div>
        <!-- Spider Details Table -->
        <div class="mt-4">
            <h3>Latest Spider Visits</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>URL</th>
                        <th>User Agent</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($spiderDetails as $detail)
                        <tr>
                            <td>{{ $detail['url'] }}</td>
                            <td>{{ $detail['userAgent'] }}</td>
                            <td>{{ $detail['ip'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        moment.tz.setDefault("Asia/Singapore"); // or any other city in GMT+8 timezone
        const spiderData = @json($spiderVisits);

        const ctx = document.getElementById('spiderVisitsChart').getContext('2d');
        const spiderChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: Object.keys(spiderData), // Hours
                datasets: [{
                    label: 'Google Spider Visits (Hourly)',
                    data: Object.values(spiderData), // Visit counts
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        type: 'time',
                        time: {
                            unit: 'hour',
                            tooltipFormat: 'yyyy-MM-dd HH:mm'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
