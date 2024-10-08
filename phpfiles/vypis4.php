<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Average Visitors Chart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Average Monthly Visitors</h2>
        <canvas id="visitorChart" width="400" height="200"></canvas>
    </div>

    <?php
    include __DIR__ . '/../CRONS/config.php';

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to get the average number of unique visitors per month
    $sql = "SELECT DATE_FORMAT(datum, '%Y-%m') AS month, AVG(unique_visitors) AS avg_visitors 
            FROM (SELECT datum, COUNT(DISTINCT ipadresa) AS unique_visitors FROM navstevnost GROUP BY datum) AS daily_data 
            GROUP BY month";
    $result = $conn->query($sql);

    $months = [];
    $avg_visitors = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $months[] = $row['month'];
            $avg_visitors[] = $row['avg_visitors'];
        }
    } else {
        echo "0 results";
    }
    $conn->close();
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('visitorChart').getContext('2d');
            var visitorChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($months); ?>,
                    datasets: [{
                        label: 'Average Visitors Per Month',
                        data: <?php echo json_encode($avg_visitors); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return Math.round(context.raw) + ' visitors';  // Zobrazí reálné celé číslo
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
