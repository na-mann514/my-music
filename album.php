<?php
session_start();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'DbConnection.php';
require_once 'SqlQueries.php';

$db_conn = new DBConnection();
$conn = $db_conn->getDBConnection();

if (isset($_GET['album_id']))
$album_id = htmlspecialchars($_GET['album_id']);

$username ='dj'; // $_SESSION['username'];

$album_info = fetch_album_details($conn, $album_id);

function fetch_album_details($conn, $album_id) {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = album_info();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$album_id]);  
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $album_info['all_songs'] = $rows;

    $sql = album_name();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$album_id]);  
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $album_info['AlbumName'] = $rows['AlbumName'];

    return $album_info;
}

if (isset($_POST['user_play_track']) && isset($_POST['user_play_artist'])) {
    $track_id = htmlspecialchars($_POST['user_play_track']);
    $artist_title = htmlspecialchars($_POST['user_play_artist']);
    insert_into_playhistory($conn, $track_id, $artist_title, $username);
}

function insert_into_playhistory($conn, $track_id, $artist_title, $username) {

    $sql = insert_into_play_history();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username, $track_id, $artist_title]);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body><?php require_once 'header.html'; ?>



<div id="artist-summary">
<h1>Album Name:<?php echo ucwords($album_info['AlbumName']); ?> </h1>
</div>



        <div id="page-container">

            <?php if (isset($album_info['error'])): ?>
                <div class="alert alert-danger alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?php echo $album_info['error']['message']; ?>
                </div>
            <?php endif; ?>

            <?php if (!isset($album_info['error'])): ?>

                <!-- Displaying Artist Info -->
                <div id="artist-bio" class="row">
                    <div id="artist-image" class="col-sm-5">
                        <li class="song-header-duration col-sm-3"><?php echo ucwords($arr['AlbumName']);?></li>
                    </div>
                </div>   

                

                <!-- Displaying Top songs -->
                <?php if ($album_info['all_songs']): ?>
                    <div id = "top-songs">
                       
                        <ul id="top-songs-headers" class="row">
                            <li class="song-header-duration col-sm-3"><?php echo ucwords($arr['AlbumName']);?></li>
                            <li class="song-header-cnt col-sm-3">#</li>
                            <li class="song-header-title col-sm-3">TRACK NAME</li>
                            <li class="song-header-duration col-sm-3">DURATION</li>
                            <li class="song-header-atitle col-sm-3">ARTIST TITLE</li>
                        </ul>
                        <?php foreach ($album_info['all_songs'] as $i => $arr): ?>
                            <ul id ="nav-<?php echo $i; ?>" class="row pay-load">
                                <li class="song-header-cnt col-sm-3"><?php echo $i + 1; ?></li>
                                <form id="nav-<?php echo $arr['TrackId']; ?>" method="POST" action="#nav-<?php echo $i;?>">
                                    <input type="hidden" name="user_play_track" id="user_play_track" value="<?php echo $arr['TrackId']; ?>"/>
                                     <input type="hidden" name="user_play_artist" id="user_play_artist" value="<?php echo $arr['ArtistTitle']; ?>"/>
                                    <li class="song-header-title col-sm-3">
                                        <a onclick="document.getElementById('nav-<?php echo $arr['TrackId']; ?>').submit();">
                                            <?php echo ucwords($arr['TrackName']); ?>
                                        </a>
                                    </li>
                                </form>
                                
                                <li class="song-header-duration col-sm-3"><?php echo number_format(($arr['TrackDuration'] / 60000), 2, ':', ''); ?></li>

                                <li class="song-header-title col-sm-3">
                                  <?php $temp1= ucwords($arr['ArtistTitle']); ?>
                            <a href="artistbio.php?aname=<?php echo $temp1; ?>"><?php echo ucwords($arr['ArtistTitle']); ?></a>
                    
                                    </li>
                            </ul>

                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
              

            <?php endif; ?>
        </div>
        <div class="iframe-container">
            <div style="overflow: hidden;"></div>

            <iframe src='https://open.spotify.com/embed/track/<?php echo $_POST['user_play_track']; ?>' width='100%' height='100' frameborder='0' allowtransparency='true'></iframe>
        </div>
    </body>
</html>
