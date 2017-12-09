<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'DbConnection.php';
require_once 'SqlQueries.php';

$db_conn = new DBConnection();
$conn = $db_conn->getDBConnection();


//$artist_title = htmlspecialchars($_GET['aname']);
//$username = $_SESSION['username'];
$username = htmlspecialchars($_GET['uname']);
//$username = 'dj';

$playlist_info = fetch_playlist_details($conn, $username);
print_r($playlist_info['playlist_info_details']);
print_r($playlist_info['playlist_track_details']);


function fetch_playlist_details($conn, $username) {
    $playlist_info = array();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = fetch_playlistname();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $playlist_info['playlist_info_details'] = $rows;

    $sql = fetch_playlisttracks();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $playlist_info['playlist_track_details'] = $rows;

    return $playlist_info;
}
