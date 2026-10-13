<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

include "system_info.php";

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Server - Fynexi</title>

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

            <a href="server.php"
               class="active">
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
                    Server Information
                </h1>

                <p>
                    Fynexi server system information
                </p>

            </div>


            <div class="status">

                <span class="status-dot"></span>

                System Online

            </div>

        </header>



        <!-- STATUS CARDS -->

        <section class="cards">


            <div class="card">

                <div class="card-icon">
                    🖥️
                </div>

                <div>

                    <p>
                        Server Status
                    </p>

                    <h2>
                        Online
                    </h2>

                </div>

            </div>



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



        <!-- SERVER INFORMATION -->

        <section class="panel">

            <div class="panel-header">

                <h2>
                    Server Information
                </h2>

                <span>
                    Current System
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
                        Operating System
                    </span>

                    <strong>
                        Windows
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



        <!-- SERVER STATUS -->

        <section class="panel">

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
                        Monitoring
                    </span>

                    <strong>
                        Active
                    </strong>

                </div>


                <div>

                    <span>
                        CPU Monitoring
                    </span>

                    <strong>
                        Active
                    </strong>

                </div>


                <div>

                    <span>
                        RAM Monitoring
                    </span>

                    <strong>
                        Active
                    </strong>

                </div>


                <div>

                    <span>
                        Storage Monitoring
                    </span>

                    <strong>
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


<!-- AUTO REFRESH -->

<script>

setTimeout(
    function() {

        location.reload();

    },
    5000
);

</script>


</body>

</html>