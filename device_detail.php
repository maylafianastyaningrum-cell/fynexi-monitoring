<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

include "config/database.php";


/* =========================
   AMBIL DEVICE ID
   ========================= */

$device_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;


/* =========================
   AMBIL DATA DEVICE
   ========================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM devices
     WHERE id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $device_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$device = mysqli_fetch_assoc($result);


/* =========================
   CEK DEVICE
   ========================= */

if (!$device) {
    die("Device tidak ditemukan.");
}


/* =========================
   AMBIL DATA MONITORING
   ========================= */

$monitoringQuery = mysqli_query(
    $conn,
    "SELECT cpu_usage, ram_usage, storage_usage, created_at
     FROM device_monitoring
     WHERE device_id = $device_id
     ORDER BY id DESC
     LIMIT 20"
);

$monitoring = [];

while ($row = mysqli_fetch_assoc($monitoringQuery)) {
    $monitoring[] = $row;
}

$monitoring = array_reverse($monitoring);


/* =========================
   DATA TERAKHIR
   ========================= */

$latestQuery = mysqli_query(
    $conn,
    "SELECT cpu_usage, ram_usage, storage_usage
     FROM device_monitoring
     WHERE device_id = $device_id
     ORDER BY id DESC
     LIMIT 1"
);

$latest = mysqli_fetch_assoc($latestQuery);

if (!$latest) {
    $latest = [
        "cpu_usage" => 0,
        "ram_usage" => 0,
        "storage_usage" => 0
    ];
}


/* =========================
   DATA CHART
   ========================= */

$labels = [];
$cpuData = [];
$ramData = [];
$storageData = [];

foreach ($monitoring as $row) {

    $labels[] = date(
        "H:i:s",
        strtotime($row["created_at"])
    );

    $cpuData[] = (float) $row["cpu_usage"];

    $ramData[] = (float) $row["ram_usage"];

    $storageData[] = (float) $row["storage_usage"];
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($device["device_name"]); ?>
        - Fynexi Monitoring
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

            <a href="devices.php"
               class="active">
                🌐 Devices
            </a>

            <a href="monitoring.php">
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


    <!-- MAIN CONTENT -->

    <main class="main-content">

        <header class="topbar">

            <div>

                <h1>
                    Device Detail
                </h1>

                <p>
                    Monitoring perangkat secara real-time
                </p>

            </div>

            <div class="status">

                <span class="status-dot"></span>

                System Online

            </div>

        </header>


        <!-- DEVICE INFORMATION -->

        <section class="panel">

            <div class="panel-header">

                <h2>
                    <?php
                    echo htmlspecialchars(
                        $device["device_name"]
                    );
                    ?>
                </h2>

                <span>
                    <?php
                    echo htmlspecialchars(
                        $device["device_type"]
                    );
                    ?>
                </span>

            </div>


            <div class="server-info">

                <div>

                    <span>
                        IP Address
                    </span>

                    <strong>
                        <?php
                        echo htmlspecialchars(
                            $device["ip_address"]
                        );
                        ?>
                    </strong>

                </div>


                <div>

                    <span>
                        Device Type
                    </span>

                    <strong>
                        <?php
                        echo htmlspecialchars(
                            $device["device_type"]
                        );
                        ?>
                    </strong>

                </div>


                <div>

                    <span>
                        Status
                    </span>

                    <strong class="online">

                        <?php
                        echo htmlspecialchars(
                            $device["status"]
                        );
                        ?>

                    </strong>

                </div>


                <div>

                    <span>
                        Last Seen
                    </span>

                    <strong>

                        <?php

                        if (!empty($device["last_seen"])) {

                            echo date(
                                "d M Y, H:i:s",
                                strtotime(
                                    $device["last_seen"]
                                )
                            );

                        } else {

                            echo "Belum ada data";

                        }

                        ?>

                    </strong>

                </div>

            </div>

        </section>


        <!-- MONITORING CARDS -->

        <section class="cards">

            <div class="card">

                <div class="card-icon">
                    ⚙️
                </div>

                <div>

                    <p>
                        CPU Usage
                    </p>

                    <h2>
                        <?php
                        echo $latest["cpu_usage"];
                        ?>%
                    </h2>

                </div>

            </div>


            <div class="card">

                <div class="card-icon">
                    💾
                </div>

                <div>

                    <p>
                        RAM Usage
                    </p>

                    <h2>
                        <?php
                        echo $latest["ram_usage"];
                        ?>%
                    </h2>

                </div>

            </div>


            <div class="card">

                <div class="card-icon">
                    💿
                </div>

                <div>

                    <p>
                        Storage Usage
                    </p>

                    <h2>
                        <?php
                        echo $latest["storage_usage"];
                        ?>%
                    </h2>

                </div>

            </div>

        </section>


        <!-- CHART -->

        <section class="panel">

            <div class="panel-header">

                <h2>
                    Performance History
                </h2>

                <span>
                    Last 20 Records
                </span>

            </div>


            <div style="height: 400px; padding: 20px;">

                <canvas id="deviceChart"></canvas>

            </div>

        </section>


        <footer>

            Fynexi Monitoring System

            <span>•</span>

            Create. Connect. Innovate.

        </footer>

    </main>

</div>


<script>

const ctx =
    document.getElementById("deviceChart");


new Chart(
    ctx,
    {

        type: "line",

        data: {

            labels:
                <?php
                echo json_encode($labels);
                ?>,

            datasets: [

                {
                    label: "CPU Usage (%)",

                    data:
                        <?php
                        echo json_encode($cpuData);
                        ?>,

                    borderWidth: 3,

                    tension: 0.4,

                    fill: false
                },

                {
                    label: "RAM Usage (%)",

                    data:
                        <?php
                        echo json_encode($ramData);
                        ?>,

                    borderWidth: 3,

                    tension: 0.4,

                    fill: false
                },

                {
                    label: "Storage Usage (%)",

                    data:
                        <?php
                        echo json_encode($storageData);
                        ?>,

                    borderWidth: 3,

                    tension: 0.4,

                    fill: false
                }

            ]

        },


        options: {

            responsive: true,

            maintainAspectRatio: false,

            scales: {

                y: {

                    beginAtZero: true,

                    max: 100,

                    ticks: {

                        callback:
                            function(value) {

                                return value + "%";

                            }

                    }

                }

            }

        }

    }

);


/* AUTO REFRESH */

setTimeout(
    function() {

        location.reload();

    },
    5000
);

</script>

</body>

</html>