<?php

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


if (mysqli_stmt_execute($stmt)) {

    echo "Monitoring data berhasil disimpan.";

} else {

    echo "Gagal menyimpan monitoring data.";

}

?>