<?php
/*
 $con = mysql_connect("localhost","root","Rajdaiya7", "music");
 if(!$con)
{die("connection failed".mysql_error());}

$db=mysql_select_db("music",$con);
if(!$db)
{die("connection failed".mysql_error());}
*/
require_once('DbConnection.php');
$db_conn = new DBConnection();
$con = $db_conn->getDBConnection();



$a=$_POST['UName'];
$b=$_POST['Name'];
$c=$_POST['Email'];
$d=$_POST['City'];
$e=$_POST['Pass'];

echo "$a";

$sql = "INSERT into user (UName,Name,Email,City) VALUES ('" . $a . "','" . $b . "','" . $c . "','" . $d . "')";
$sql1= "INSERT into login_info (UName,Pass) VALUES('" . $a . "','" . $e . "')";
$con->query($sql);
$con->query($sql1);
header("location: mainhome.html"); 
exit();



mysql_close($con);
?>
