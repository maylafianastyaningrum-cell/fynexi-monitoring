<?php

/* =========================
   CPU USAGE
========================= */

$cpuCommand = 'powershell -NoProfile -Command "(Get-CimInstance Win32_Processor | Measure-Object -Property LoadPercentage -Average).Average"';

$cpu_usage = trim(shell_exec($cpuCommand));

if ($cpu_usage === "" || !is_numeric($cpu_usage)) {
    $cpu_usage = 0;
}

$cpu_usage = round($cpu_usage, 1);


/* =========================
   RAM USAGE
========================= */

$ramCommand = 'powershell -NoProfile -Command "$os=Get-CimInstance Win32_OperatingSystem; $total=$os.TotalVisibleMemorySize; $free=$os.FreePhysicalMemory; [math]::Round((($total-$free)/$total)*100,1)"';

$ram_usage = trim(shell_exec($ramCommand));

if ($ram_usage === "" || !is_numeric($ram_usage)) {
    $ram_usage = 0;
}

$ram_usage = round($ram_usage, 1);


/* =========================
   STORAGE
========================= */

$total = disk_total_space("C:/");
$free = disk_free_space("C:/");

$used = $total - $free;

$storage_usage = round(($used / $total) * 100, 1);


/* =========================
   UPTIME
========================= */

$bootCommand = "powershell -NoProfile -Command \"(Get-CimInstance Win32_OperatingSystem).LastBootUpTime\"";

$bootTime = trim(shell_exec($bootCommand));

if ($bootTime != "") {

    $bootDate = new DateTime($bootTime);
    $now = new DateTime();

    $difference = $now->diff($bootDate);

    $uptime = $difference->days . " Days " .
              $difference->h . " Hours";

} else {

    $uptime = "Unknown";

}

?>