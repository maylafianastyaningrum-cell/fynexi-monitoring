<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

include "system_info.php";

/* =========================
   DEVICE MONITORING
========================= */

function checkDevice($ip)
{
    $output = [];

    exec(
        "ping -n 1 -w 1000 " . escapeshellarg($ip),
        $output,
        $result
    );

    return $result === 0 ? "Online" : "Offline";
}


$devices = [
    [
        "name" => "Router",
        "ip" => "192.168.1.1"
    ],
    [
        "name" => "Server",
        "ip" => "192.168.1.10"
    ],
    [
        "name" => "Client 01",
        "ip" => "192.168.1.20"
    ]
];


/* =========================
   STORAGE MONITORING
========================= */

$total = disk_total_space("C:/");
$free = disk_free_space("C:/");

$used = $total - $free;

$storage_usage = round(($used / $total) * 100, 1);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Fynexi Monitoring</title>

    <link rel="stylesheet" href="css/dashboard.css">

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

            <a href="#" class="active">
                🏠 Dashboard
            </a>

            <a href="server.php">
                🖥 Server
            </a>

            <a href="devices.php">
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


        <!-- HEADER -->

        <header class="topbar">

            <div>

                <h1>
                    Dashboard
                </h1>

                <p>
                    Welcome back, <?php echo $_SESSION['username']; ?> 👋
                </p>

            </div>


            <div class="status">

                <span class="status-dot"></span>

                System Online

            </div>

        </header>



        <!-- SUMMARY CARDS -->

        <section class="cards">


            <!-- SERVER -->

            <div class="card">

                <div class="card-icon">
                    🖥️
                </div>

                <div>

                    <p>
                        Server
                    </p>

                    <h2>
                        Online
                    </h2>

                </div>

            </div>



            <!-- CPU -->

            <div class="card">

                <div class="card-icon">
                    ⚙️
                </div>

                <div>

                    <p>
                        CPU Usage
                    </p>

                    <h2>
                        <?php echo $cpu_usage; ?>%
                    </h2>

                </div>

            </div>



            <!-- RAM -->

            <div class="card">

                <div class="card-icon">
                    💾
                </div>

                <div>

                    <p>
                        RAM Usage
                    </p>

                    <h2>
                        <?php echo $ram_usage; ?>%
                    </h2>

                </div>

            </div>



            <!-- STORAGE REAL -->

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



        <!-- CONTENT GRID -->

        <section class="content-grid">


            <!-- DEVICE STATUS -->

            <div class="panel">

                <div class="panel-header">

                    <h2>
                        Device Status
                    </h2>

                    <span>
                        <?php echo count($devices); ?> Devices
                    </span>

                </div>


                <div class="device-list">

                    <?php foreach ($devices as $device): ?>

                        <?php
                        $status = checkDevice($device["ip"]);
                        ?>


                        <div class="device">

                            <div>

                                <strong>
                                    <?php echo $device["name"]; ?>
                                </strong>

                                <small>
                                    <?php echo $device["ip"]; ?>
                                </small>

                            </div>


                            <?php if ($status === "Online"): ?>

                                <span class="online">
                                    ● Online
                                </span>

                            <?php else: ?>

                                <span class="offline">
                                    ● Offline
                                </span>

                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>



            <!-- SERVER STATUS -->

            <div class="panel">

                <div class="panel-header">

                    <h2>
                        Server Status
                    </h2>

                    <span class="online">
                        ● Online
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
                            Uptime
                        </span>

                        <strong>
                            <?php echo $uptime; ?>
                        </strong>

                    </div>

                </div>

            </div>

        </section>



        <!-- ACTIVITY -->

        <section class="panel activity">

            <div class="panel-header">

                <h2>
                    Recent Activity
                </h2>

                <span>
                    Today
                </span>

            </div>


            <div class="activity-item">

                <span class="activity-dot"></span>

                <div>

                    <strong>
                        Server connected
                    </strong>

                    <p>
                        Fynexi Server is online
                    </p>

                </div>

                <time>
                    10:24
                </time>

            </div>


            <div class="activity-item">

                <span class="activity-dot"></span>

                <div>

                    <strong>
                        Monitoring started
                    </strong>

                    <p>
                        System monitoring is active
                    </p>

                </div>

                <time>
                    10:20
                </time>


            </div>

        </section>



        <!-- FOOTER -->

        <footer>

            Fynexi Monitoring System

            <span>•</span>

            Create. Connect. Innovate.

        </footer>


    </main>

</div>

<script>
setTimeout(function() {
    location.reload();
}, 5000);
</script>

</body>

</html>