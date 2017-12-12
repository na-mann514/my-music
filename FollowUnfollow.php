<?php
session_start();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'DbConnection.php';
require_once 'SqlQueries.php';

$username = $_SESSION['username'];
$username1= htmlspecialchars($_POST['username1']);


echo $_SESSION['username'];
echo $username1;
//$artist_title = $_POST['artist_title'];

$db_conn = new DBConnection();
$conn = $db_conn->getDBConnection();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = does_user_follow_user1();
$stmt = $conn->prepare($sql);
$stmt->execute([$username, $username1]);
$rows = $stmt->fetch(PDO::FETCH_ASSOC);
$does_user_follow = $rows['rec_count'] > 0 ? 1 : 0;

$current_follow_value = isset($_POST['follow-check']) ? 1 : 0;

if ($does_user_follow == 0 && $current_follow_value == 1) {
    try {
        $sql = insert_into_followers();
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username, $username1]);
        $success = 1;
    }
    catch (PDOException $E) {
        echo $E->getMessage();
    }
}
elseif ($does_user_follow == 1 && $current_follow_value == 0) {
    $sql = delete_from_followers();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username, $username1]);
    $success = 1;
}

if (isset($_REQUEST["destination"])) {
    if($success == 1 && !isset($_GET['success'])) {
        header("Location: {$_REQUEST["destination"]}&success=$success");
    }
    else {
        header("Location: {$_REQUEST["destination"]}");
    }
}
else if (isset($_SERVER["HTTP_REFERER"])) {
    header("Location: {$_SERVER["HTTP_REFERER"]}");
}