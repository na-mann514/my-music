<?php
session_start();

require_once('DbConnection.php');
$db_conn = new DBConnection();
$con = $db_conn->getDBConnection();
$username='dj'
$_SESSION['username']=$username;
?>



<html>
<head>
<title>
CREATE PLAYLIST
</title>
<style>
t {font-family:broadway}
</style>
</HEAD>
<body background="background.jpg" size="100% 100%">
<center>
<form  action="insert_playlist.php" method="post">
	<br />
	<br />
	<br />
	<br />
<table border="1" bgcolor="#00CCFF">
<tr><td>Playlist Name</td>
<td><input type="text" name="playlistname"/></td>
</tr>
<tr><td rowspan="2">Playlist Type:</td>
<td><input type="radio" name="type" value="0"/>Public</td>
<tr>
<td><input type="radio" name="type" value="1"/>Private</td></tr>
</tr>
<tr><td><input type="submit" name="submit" value="Submit"/></td></tr>
</table>
</form>
</center>
</body>
<style>
a:link {
    color: black;
}
a:visited{
color: black;
}
</style>
</html>


