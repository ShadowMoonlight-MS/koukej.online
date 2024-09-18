<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Chart full</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Visitor Chart full</h2>
        <canvas id="visitorChart" width="400" height="200"></canvas>
    </div>

    <?php
    include 'CRONS/config.php';
    

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to get the number of unique IP addresses per date
    $sql = "SELECT datum, COUNT(DISTINCT ipadresa) AS unique_visitors FROM navstevnost GROUP BY datum";
    $result = $conn->query($sql);

    $dates = [];
    $visitors = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $dates[] = $row['datum'];
            $visitors[] = $row['unique_visitors'];
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
                    labels: <?php echo json_encode($dates); ?>,
                    datasets: [{
                        label: 'Unique Visitors',
                        data: <?php echo json_encode($visitors); ?>,
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
                                    return context.raw + ' visitors';
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
