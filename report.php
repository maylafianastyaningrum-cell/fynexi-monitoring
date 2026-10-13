<?php

header("Content-Type: application/json");

include "../config/database.php";

/* Ambil data dari client */
$device_id = $_POST['device_id'] ?? '';
$cpu_usage = $_POST['cpu_usage'] ?? null;
$ram_usage = $_POST['ram_usage'] ?? null;
$storage_usage = $_POST['storage_usage'] ?? null;

/* Validasi */
if ($device_id === '') {

    echo json_encode([
        "success" => false,
        "message" => "Device ID tidak ditemukan"
    ]);

    exit;
}

/* Pastikan device ada */
$check = mysqli_prepare(
    $conn,
    "SELECT id FROM devices WHERE id = ?"
);

mysqli_stmt_bind_param(
    $check,
    "i",
    $device_id
);

mysqli_stmt_execute($check);

$result = mysqli_stmt_get_result($check);

if (mysqli_num_rows($result) === 0) {

    echo json_encode([
        "success" => false,
        "message" => "Device tidak ditemukan"
    ]);

    exit;
}

/* Simpan data monitoring */
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO device_monitoring
    (device_id, cpu_usage, ram_usage, storage_usage)
    VALUES (?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "iddd",
    $device_id,
    $cpu_usage,
    $ram_usage,
    $storage_usage
);

if (mysqli_stmt_execute($stmt)) {

    /* Update waktu terakhir perangkat mengirim data */
    $update = mysqli_prepare(
        $conn,
        "UPDATE devices
         SET status = 'Online',
             last_seen = NOW()
         WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $update,
        "i",
        $device_id
    );

    mysqli_stmt_execute($update);

    echo json_encode([
        "success" => true,
        "message" => "Data monitoring berhasil diterima"
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Gagal menyimpan data"
    ]);
}

?>