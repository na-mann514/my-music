<?php
session_start();

require_once('DbConnection.php');
$db_conn = new DBConnection();
$con = $db_conn->getDBConnection();

$a=$_POST['UName'];
$e=$_POST['Pass'];

$_SESSION['username'] =$_POST['UName'];
$_SESSION['password'] =$_POST['Pass'];


//$sql= "SELECT * FROM login_info WHERE UName='$a' AND Pass='$e'";
//$result=$con->query($sql);
$sql = $DB->prepare("SELECT * FROM login_info WHERE UName='$a' AND Pass='$e'");
$sql->execute();
$res=$sql;
 if($res){
	//Check whether the query was successful or not
	if($sql->fetchColumn() == 0) 
    {echo "<b>LOGIN FAILED!!</b>";
		exit();}

    	//$res = $DB->prepare("SELECT * FROM login_info WHERE UName='$a' AND Pass='$e'";);
		//$res->execute();
		//$num_rows = $res->fetchColumn();
		//echo $num_rows;
    	else
		{ 
		header("location: c.html");           
		exit();
		}
        
	}
    else 
    {
	die("Query failed");
	}
?>
