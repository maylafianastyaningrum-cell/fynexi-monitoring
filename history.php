<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

include "config/database.php";


// =========================
// AMBIL HISTORI MONITORING
// =========================

$query = mysqli_query(
    $conn,
    "SELECT *
     FROM monitoring_history
     ORDER BY id DESC
     LIMIT 50"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Monitoring History - Fynexi</title>

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

            <a href="devices.php">
                🌐 Devices
            </a>

            <a href="monitoring.php">
                📊 Monitoring
            </a>

            <a href="history.php"
               class="active">
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
                    Monitoring History
                </h1>

                <p>
                    Server performance history
                </p>

            </div>


            <div class="status">

                <span class="status-dot"></span>

                System Online

            </div>

        </header>



        <!-- HISTORY TABLE -->

        <section class="panel">

            <div class="panel-header">

                <h2>
                    CPU, RAM & Storage History
                </h2>

                <span>
                    Last 50 Records
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
                                CPU Usage
                            </th>

                            <th>
                                RAM Usage
                            </th>

                            <th>
                                Storage Usage
                            </th>

                            <th>
                                Date & Time
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php

                        $no = 1;

                        if (mysqli_num_rows($query) > 0):

                        ?>


                            <?php while ($row = mysqli_fetch_assoc($query)): ?>


                                <tr>

                                    <td>
                                        <?php echo $no++; ?>
                                    </td>


                                    <td>

                                        <strong>
                                            <?php
                                            echo $row['cpu_usage'];
                                            ?>%
                                        </strong>

                                    </td>


                                    <td>

                                        <strong>
                                            <?php
                                            echo $row['ram_usage'];
                                            ?>%
                                        </strong>

                                    </td>


                                    <td>

                                        <strong>
                                            <?php
                                            echo $row['storage_usage'];
                                            ?>%
                                        </strong>

                                    </td>


                                    <td>

                                        <?php

                                        echo date(
                                            'd M Y, H:i:s',
                                            strtotime(
                                                $row['created_at']
                                            )
                                        );

                                        ?>

                                    </td>

                                </tr>


                            <?php endwhile; ?>


                        <?php else: ?>


                            <tr>

                                <td colspan="5">

                                    Belum ada data monitoring.

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
                    Monitoring Information
                </h2>

            </div>


            <div class="server-info">


                <div>

                    <span>
                        Data Source
                    </span>

                    <strong>
                        System Monitoring
                    </strong>

                </div>


                <div>

                    <span>
                        Database
                    </span>

                    <strong>
                        monitoring_history
                    </strong>

                </div>


                <div>

                    <span>
                        Maximum Records
                    </span>

                    <strong>
                        50 Records
                    </strong>

                </div>


                <div>

                    <span>
                        Monitoring Status
                    </span>

                    <strong class="online">
                        Active
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

</body>

</html>