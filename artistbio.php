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


$artist_title = htmlspecialchars($_GET['aname']);
//$username = $_SESSION['username'];
$username = 'dj';

$artist_info = fetch_artist_details($conn, $artist_title, $username);
print_r($artist_info['bio_details']);
print_r($artist_info['top_songs']);
print_r($artist_info['does_like']);
print_r($artist_info['like_count']);
print_r($artist_info['follower_count']);

function fetch_artist_details($conn, $artist_title, $username) {
    $artist_info = array();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = fetch_artist_bio_details();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$artist_title]);
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $artist_info['bio_details'] = $rows;


    $sql = fetch_top_songs_by_artist();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$artist_title]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $artist_info['top_songs'] = $rows;


    $sql = does_user_like_artist();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$artist_title, $username]);
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $artist_info['does_like'] = $rows;


    $sql = fetch_artist_likes_count();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$artist_title]);
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $artist_info['like_count'] = $rows;


    $sql = fetch_artist_follower_count();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$artist_title]);
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $artist_info['follower_count'] = $rows;

    return $artist_info;
}
