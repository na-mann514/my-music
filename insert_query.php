<?php
session_start();
require_once('DbConnection.php');
$db_conn = new DBConnection();
$con = $db_conn->getDBConnection();



$a=$_POST['uname'];
$_SESSION['username']=$a;
$b=$_POST['Name'];
$c=$_POST['Email'];
$d=$_POST['City'];
$e=$_POST['pass'];

echo "$a";

$sql = "INSERT into user (UName,Name,Email,City) VALUES ('" . $a . "','" . $b . "','" . $c . "','" . $d . "')";
$sql1= "INSERT into login_info (UName,Pass) VALUES('" . $a . "','" . $e . "')";
$con->query($sql);
$con->query($sql1);
header('Location: user-dashboard.php');
exit();



mysql_close($con);
?>
