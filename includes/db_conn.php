<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servername = "DBHOSTNAME";
$username = "DBADMIN";
$password = "DBPASSWORD";
$dbname = "DBNAME";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    openlog("myapp", LOG_PID | LOG_ODELAY, LOG_USER);
    syslog(LOG_INFO, "Connected successfully: " . date("Y-m-d H:i:s"));
    closelog();
}
?>
