<?php

require_once('DbConnection.php');
$db_conn = new DBConnection();
$conn = $db_conn->getDBConnection();

$username = $_POST['uname'];
$password = $_POST['pass'];

$sql7 = "SELECT * FROM login_info WHERE UName='$username' AND Pass=password('$password')";
$stmt = $conn->prepare($sql7);
$stmt->execute();
$rowCount = $stmt->rowCount();
//$con->query($sql7);
//$n=$sql7->rowCount();
//echo $n;

if ($rowCount == 0) {
    header("Location: login.html");
} else {
    header("Location: user-dashboard.php");
    session_start();
    $_SESSION['username'] = $username;
}
?>
