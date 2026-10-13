<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

include "config/database.php";
include "system_info.php";


// =========================
// SIMPAN DATA MONITORING
// =========================

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO monitoring_history
    (cpu_usage, ram_usage, storage_usage)
    VALUES (?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "ddd",
    $cpu_usage,
    $ram_usage,
    $storage_usage
);

mysqli_stmt_execute($stmt);

// Hapus data lama, simpan maksimal 1000 data terbaru
mysqli_query($conn, "
    DELETE FROM monitoring_history
    WHERE id NOT IN (
        SELECT id FROM (
            SELECT id
            FROM monitoring_history
            ORDER BY id DESC
            LIMIT 1000
        ) AS keep_rows
    )
");


// =========================
// AMBIL HISTORI MONITORING
// =========================

$historyQuery = mysqli_query(
    $conn,
    "SELECT cpu_usage, ram_usage, created_at
     FROM monitoring_history
     ORDER BY id DESC
     LIMIT 10"
);


$history = [];

while ($row = mysqli_fetch_assoc($historyQuery)) {
    $history[] = $row;
}


// Balikkan urutan agar grafik dari lama → terbaru

$history = array_reverse($history);


// =========================
// DATA GRAFIK
// =========================

$labels = [];
$cpuData = [];
$ramData = [];

foreach ($history as $row) {

    $labels[] = date(
        'H:i:s',
        strtotime($row['created_at'])
    );

    $cpuData[] = (float) $row['cpu_usage'];

    $ramData[] = (float) $row['ram_usage'];

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Monitoring - Fynexi
    </title>

    <link rel="stylesheet"
          href="css/dashboard.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>


<body>

<div class="dashboard">


    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="brand">

            FYNEXI

            <span>
                Monitoring System
            </span>

        </div>


        <nav>

            <a href="dashboard.php">
                🏠 Dashboard
            </a>

            <a href="server.php">
                🖥 Server
            </a>

            <a href="devices.php">
                🌐 Devices
            </a>

            <a href="monitoring.php"
               class="active">
                📊 Monitoring
            </a>

            <a href="history.php">
                📈 History
            </a>

            <a href="activity_log.php">
                📋 Activity Log
            </a>

        </nav>


        <div class="sidebar-bottom">

            <a href="logout.php">
                🚪 Logout
            </a>

        </div>

    </aside>



    <!-- MAIN -->

    <main class="main-content">


        <!-- HEADER -->

        <header class="topbar">

            <div>

                <h1>
                    System Monitoring
                </h1>

                <p>
                    Real-time server performance
                </p>

            </div>


            <div class="status">

                <span class="status-dot"></span>

                System Online

            </div>

        </header>



        <!-- CARDS -->

        <section class="cards">


            <div class="card">

                <div class="card-icon">
                    ⚙️
                </div>

                <div>

                    <p>
                        Current CPU
                    </p>

                    <h2>
                        <?php echo $cpu_usage; ?>%
                    </h2>

                </div>

            </div>



            <div class="card">

                <div class="card-icon">
                    💾
                </div>

                <div>

                    <p>
                        Current RAM
                    </p>

                    <h2>
                        <?php echo $ram_usage; ?>%
                    </h2>

                </div>

            </div>



            <div class="card">

                <div class="card-icon">
                    💿
                </div>

                <div>

                    <p>
                        Storage
                    </p>

                    <h2>
                        <?php echo $storage_usage; ?>%
                    </h2>

                </div>

            </div>


        </section>



        <!-- GRAPH -->

        <section class="panel">

            <div class="panel-header">

                <h2>
                    CPU & RAM Performance
                </h2>

                <span>
                    Monitoring History
                </span>

            </div>


            <div style="
                height: 350px;
                padding: 20px;
            ">

                <canvas id="performanceChart">
                </canvas>

            </div>

        </section>



        <!-- SYSTEM INFORMATION -->

        <section class="panel">

            <div class="panel-header">

                <h2>
                    System Information
                </h2>

                <span>
                    Current Status
                </span>

            </div>


            <div class="server-info">


                <div>

                    <span>
                        Hostname
                    </span>

                    <strong>
                        Fynexi Server
                    </strong>

                </div>


                <div>

                    <span>
                        IP Address
                    </span>

                    <strong>
                        192.168.1.10
                    </strong>

                </div>


                <div>

                    <span>
                        CPU Usage
                    </span>

                    <strong>
                        <?php echo $cpu_usage; ?>%
                    </strong>

                </div>


                <div>

                    <span>
                        RAM Usage
                    </span>

                    <strong>
                        <?php echo $ram_usage; ?>%
                    </strong>

                </div>


                <div>

                    <span>
                        Storage Usage
                    </span>

                    <strong>
                        <?php echo $storage_usage; ?>%
                    </strong>

                </div>


                <div>

                    <span>
                        Uptime
                    </span>

                    <strong>
                        <?php echo $uptime; ?>
                    </strong>

                </div>


            </div>

        </section>



        <!-- FOOTER -->

        <footer>

            Fynexi Monitoring System

            <span>
                •
            </span>

            Create. Connect. Innovate.

        </footer>


    </main>

</div>



<!-- CHART.JS -->

<script>

const ctx =
    document.getElementById(
        'performanceChart'
    );


const chart = new Chart(
    ctx,
    {

        type: 'line',


        data: {

            labels:
                <?php echo json_encode($labels); ?>,


            datasets: [

                {

                    label:
                        'CPU Usage (%)',

                    data:
                        <?php echo json_encode($cpuData); ?>,

                    borderWidth:
                        3,

                    tension:
                        0.4,

                    fill:
                        false

                },


                {

                    label:
                        'RAM Usage (%)',

                    data:
                        <?php echo json_encode($ramData); ?>,

                    borderWidth:
                        3,

                    tension:
                        0.4,

                    fill:
                        false

                }

            ]

        },


        options: {

            responsive:
                true,

            maintainAspectRatio:
                false,


            animation: {

                duration:
                    800

            },


            scales: {

                y: {

                    beginAtZero:
                        true,

                    max:
                        100,


                    ticks: {

                        callback:
                            function(value) {

                                return value + '%';

                            }

                    }

                }

            },


            plugins: {

                legend: {

                    display:
                        true

                }

            }

        }

    }
);


// =========================
// AUTO REFRESH
// =========================

setTimeout(
    function() {

        location.reload();

    },
    5000
);

</script>


</body>

</html>