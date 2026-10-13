<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

include "config/database.php";


/* =========================
   FUNGSI CEK DEVICE
   ========================= */

function checkDevice($ip)
{
    $ip = escapeshellarg($ip);

    $command = "ping -n 1 -w 1000 " . $ip;

    exec($command, $output, $result);

    return $result === 0;
}


/* =========================
   AMBIL SEMUA DEVICE
   ========================= */

$query = mysqli_query(
    $conn,
    "SELECT *
     FROM devices
     ORDER BY id ASC"
);


/* =========================
   UPDATE STATUS DEVICE
   ========================= */

$devices = [];

while ($row = mysqli_fetch_assoc($query)) {

    $isOnline = checkDevice($row["ip_address"]);

    if ($isOnline) {

        $status = "Online";

        mysqli_query(
            $conn,
            "UPDATE devices
             SET status = 'Online',
                 last_seen = NOW()
             WHERE id = " . (int)$row["id"]
        );

    } else {

        $status = "Offline";

        mysqli_query(
            $conn,
            "UPDATE devices
             SET status = 'Offline'
             WHERE id = " . (int)$row["id"]
        );
    }

    $row["status"] = $status;

    $devices[] = $row;
}


/* =========================
   HITUNG STATUS
   ========================= */

$onlineCount = 0;
$offlineCount = 0;

foreach ($devices as $device) {

    if ($device["status"] === "Online") {
        $onlineCount++;
    } else {
        $offlineCount++;
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Devices - Fynexi Monitoring
    </title>

    <link rel="stylesheet"
          href="css/dashboard.css">

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


    <!-- MAIN -->

    <main class="main-content">


        <!-- HEADER -->

        <header class="topbar">

            <div>

                <h1>
                    Network Devices
                </h1>

                <p>
                    Monitoring perangkat yang terhubung
                </p>

            </div>


            <div class="status">

                <span class="status-dot"></span>

                System Online

            </div>

        </header>


        <!-- SUMMARY CARDS -->

        <section class="cards">


            <div class="card">

                <div class="card-icon">
                    🌐
                </div>

                <div>

                    <p>
                        Total Devices
                    </p>

                    <h2>
                        <?php
                        echo count($devices);
                        ?>
                    </h2>

                </div>

            </div>


            <div class="card">

                <div class="card-icon">
                    🟢
                </div>

                <div>

                    <p>
                        Online
                    </p>

                    <h2>
                        <?php
                        echo $onlineCount;
                        ?>
                    </h2>

                </div>

            </div>


            <div class="card">

                <div class="card-icon">
                    🔴
                </div>

                <div>

                    <p>
                        Offline
                    </p>

                    <h2>
                        <?php
                        echo $offlineCount;
                        ?>
                    </h2>

                </div>

            </div>


        </section>


        <!-- DEVICE TABLE -->

        <section class="panel">


            <div class="panel-header">

                <h2>
                    Connected Devices
                </h2>

                <span>
                    Real-time Status
                </span>

            </div>


            <div class="device-table">

                <table>

                    <thead>

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                Device
                            </th>

                            <th>
                                IP Address
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Last Seen
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php

                    if (count($devices) > 0):

                        $no = 1;

                        foreach ($devices as $device):

                    ?>

                        <tr>


                            <td>
                                <?php
                                echo $no++;
                                ?>
                            </td>


                            <td>

                                <strong>

                                    <?php
                                    echo htmlspecialchars(
                                        $device["device_name"]
                                    );
                                    ?>

                                </strong>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $device["ip_address"]
                                );
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $device["device_type"]
                                );
                                ?>

                            </td>


                            <td>

                                <?php

                                if ($device["status"] === "Online"):

                                ?>

                                    <span class="device-online">
                                        ● Online
                                    </span>

                                <?php else: ?>

                                    <span class="device-offline">
                                        ● Offline
                                    </span>

                                <?php endif; ?>

                            </td>


                            <td>

                                <?php

                                if (!empty($device["last_seen"])) {

                                    echo date(
                                        "d M Y, H:i:s",
                                        strtotime(
                                            $device["last_seen"]
                                        )
                                    );

                                } else {

                                    echo "-";

                                }

                                ?>

                            </td>


                            <td>

                                <a
                                    href="device_detail.php?id=<?php echo (int)$device["id"]; ?>"
                                    style="
                                        display:inline-block;
                                        padding:8px 12px;
                                        background:#0f172a;
                                        color:#38bdf8;
                                        text-decoration:none;
                                        border-radius:8px;
                                        border:1px solid #334155;
                                    "
                                >
                                    View Detail
                                </a>

                            </td>


                        </tr>


                    <?php

                        endforeach;

                    else:

                    ?>

                        <tr>

                            <td colspan="7">

                                Belum ada perangkat.

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </section>


        <!-- INFORMATION -->

        <section class="panel">

            <div class="panel-header">

                <h2>
                    Device Monitoring Information
                </h2>

            </div>


            <div class="server-info">

                <div>

                    <span>
                        Monitoring Method
                    </span>

                    <strong>
                        Network Ping
                    </strong>

                </div>


                <div>

                    <span>
                        Monitoring Database
                    </span>

                    <strong>
                        devices
                    </strong>

                </div>


                <div>

                    <span>
                        Client Monitoring
                    </span>

                    <strong>
                        Python Agent
                    </strong>

                </div>


                <div>

                    <span>
                        System Status
                    </span>

                    <strong class="online">
                        Active
                    </strong>

                </div>

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

setTimeout(
    function() {

        location.reload();

    },
    10000
);

</script>

</body>

</html>