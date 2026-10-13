<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

include "config/database.php";

$query = "SELECT * FROM activity_logs
          ORDER BY created_at DESC";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Activity Log - Fynexi</title>

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

            <a href="dashboard.php">
                🏠 Dashboard
            </a>

            <a href="#">
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

            <a href="activity_log.php" class="active">
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

        <header class="topbar">

            <div>

                <h1>
                    Activity Log
                </h1>

                <p>
                    System activity history
                </p>

            </div>

            <div class="status">

                <span class="status-dot"></span>

                System Online

            </div>

        </header>


        <!-- LOG PANEL -->

        <section class="panel">

            <div class="panel-header">

                <h2>
                    Recent Activity
                </h2>

                <span>
                    Activity History
                </span>

            </div>


            <div class="device-table">

                <table>

                    <thead>

                        <tr>

                            <th>
                                User
                            </th>

                            <th>
                                Activity
                            </th>

                            <th>
                                Date & Time
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php if (mysqli_num_rows($result) > 0): ?>

                            <?php while ($log = mysqli_fetch_assoc($result)): ?>

                                <tr>

                                    <td>

                                        <strong>
                                            <?php echo htmlspecialchars($log['username']); ?>
                                        </strong>

                                    </td>


                                    <td>

                                        <?php echo htmlspecialchars($log['activity']); ?>

                                    </td>


                                    <td>

                                        <?php echo date(
                                            'd M Y, H:i',
                                            strtotime($log['created_at'])
                                        ); ?>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="3">
                                    Belum ada aktivitas.
                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </section>


        <footer>

            Fynexi Monitoring System

            <span>•</span>

            Create. Connect. Innovate.

        </footer>

    </main>

</div>

</body>

</html>