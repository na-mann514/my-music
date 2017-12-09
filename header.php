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

$user_info = fetch_user_profile_details($conn, $username);
print_r($user_info['user_bio_details']);
print_r($user_info['followers_count']);
print_r($user_info['followers']);
print_r($user_info['following_count']);
print_r($user_info['following']);
print_r($user_info['fav_artists']);
print_r($user_info['self_playlist']);
print_r($user_info['users_playlist']);

function fetch_user_profile_details($conn, $username) {
    $user_info = array();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = fetch_user_bio_details();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_info['user_bio_details'] = $rows;


    $sql = fetch_user_followers_count();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $user_info['followers_count'] = $rows;


    $sql = fetch_user_followers();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $user_info['followers'] = $rows;


    $sql = fetch_user_following_count();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_info['following_count'] = $rows;


    $sql = fetch_user_following();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $user_info['following'] = $rows;

     $sql = fetch_fav_artists();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $user_info['fav_artists'] = $rows;

     $sql = fetch_self_playlists();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $user_info['self_playlist'] = $rows;

    $sql = fetch_other_users_playlists();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $user_info['users_playlist'] = $rows;

    return $user_info;
}
