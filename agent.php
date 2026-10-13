<?php

/* =========================
   KONFIGURASI CLIENT
   ========================= */

$serverUrl = "http://192.168.0.114/fynexi-monitoring/api/report.php";

$deviceId = 1;


/* =========================
   AMBIL CPU
   ========================= */

$cpuCommand = 'powershell -NoProfile -Command "(Get-CimInstance Win32_Processor | Measure-Object -Property LoadPercentage -Average).Average"';

$cpu_usage = trim(shell_exec($cpuCommand));

if ($cpu_usage === "" || !is_numeric($cpu_usage)) {
    $cpu_usage = 0;
}

$cpu_usage = round($cpu_usage, 1);


/* =========================
   AMBIL RAM
   ========================= */

$ramCommand = 'powershell -NoProfile -Command "$os=Get-CimInstance Win32_OperatingSystem; $total=$os.TotalVisibleMemorySize; $free=$os.FreePhysicalMemory; [math]::Round((($total-$free)/$total)*100,1)"';

$ram_usage = trim(shell_exec($ramCommand));

if ($ram_usage === "" || !is_numeric($ram_usage)) {
    $ram_usage = 0;
}

$ram_usage = round($ram_usage, 1);


/* =========================
   AMBIL STORAGE
   ========================= */

$total = disk_total_space("C:/");
$free = disk_free_space("C:/");

$used = $total - $free;

$storage_usage = round(
    ($used / $total) * 100,
    1
);


/* =========================
   KIRIM KE SERVER
   ========================= */

$data = [
    "device_id" => $deviceId,
    "cpu_usage" => $cpu_usage,
    "ram_usage" => $ram_usage,
    "storage_usage" => $storage_usage
];


$options = [
    "http" => [
        "method" => "POST",

        "header" =>
            "Content-Type: application/x-www-form-urlencoded\r\n",

        "content" => http_build_query($data),

        "timeout" => 5
    ]
];


$context = stream_context_create($options);

$result = @file_get_contents(
    $serverUrl,
    false,
    $context
);


/* =========================
   TAMPILKAN HASIL
   ========================= */

echo "Fynexi Client Agent\n";
echo "===================\n";

echo "Device ID : " . $deviceId . "\n";

echo "CPU       : " . $cpu_usage . "%\n";

echo "RAM       : " . $ram_usage . "%\n";

echo "Storage   : " . $storage_usage . "%\n";

echo "\n";

if ($result !== false) {

    echo "Server Response:\n";
    echo $result;

} else {

    echo "Gagal menghubungi Fynexi Server.";

}

?>