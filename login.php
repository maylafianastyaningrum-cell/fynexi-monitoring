<?php

session_start();

include "config/database.php";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';


// =========================
// CEK USER
// =========================

$stmt = mysqli_prepare(
    $conn,
    "SELECT username, password FROM users WHERE username = ?"
);

mysqli_stmt_bind_param($stmt, "s", $username);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


// =========================
// LOGIN
// =========================

if ($user = mysqli_fetch_assoc($result)) {

    if (password_verify($password, $user['password'])) {

        $_SESSION['username'] = $user['username'];


        // =========================
        // CATAT AKTIVITAS LOGIN
        // =========================

        $activity = "User berhasil login ke sistem";

        $logStmt = mysqli_prepare(
            $conn,
            "INSERT INTO activity_logs (username, activity)
             VALUES (?, ?)"
        );

        mysqli_stmt_bind_param(
            $logStmt,
            "ss",
            $username,
            $activity
        );

        mysqli_stmt_execute($logStmt);


        // =========================
        // MASUK DASHBOARD
        // =========================

        header("Location: dashboard.php");
        exit;

    }

}


// =========================
// LOGIN GAGAL
// =========================

echo "<script>
        alert('Username atau password salah!');
        window.location='index.php';
      </script>";

?>