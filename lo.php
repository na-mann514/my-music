<?php
session_start();
require_once('DbConnection.php');
$db_conn = new DBConnection();
$con = $db_conn->getDBConnection();

$a=$_POST['uname'];
$e=$_POST['pass'];
$_SESSION['username'] =$a;

echo $a;

$sql7 = "SELECT * FROM login_info WHERE uname='$a' AND pass='$e'";
$con->query($sql7);
$n=$sql7->rowCount();
echo $n;


if($sql7) 
 {echo "LOGIN FAILED!!";
  exit();
 }

else
{ 
echo "SUCCESS";  
exit();
}

?>
