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

function fetch_artist_details($conn, $artist_title, $username) {
    $artist_info['artist_title'] = $artist_title;
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = fetch_artist_bio_details();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$artist_title]);
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $artist_info['artist_desc'] = $rows['artist_desc'];
    $artist_info['track_count'] = $rows['track_count'];


    $sql = fetch_top_songs_by_artist();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$artist_title]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $artist_info['top_songs'] = $rows;

    if ($username) {
        $sql = does_user_like_artist();
        $stmt = $conn->prepare($sql);
        $stmt->execute([$artist_title, $username]);
        $rows = $stmt->fetch(PDO::FETCH_ASSOC);
        $artist_info['does_like'] = $rows['rec_count'] > 0 ? TRUE : FALSE;
    }

    $sql = fetch_artist_likes_count();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$artist_title]);
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $artist_info['like_count'] = $rows['like_count'];


    $sql = fetch_artist_follower_count();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$artist_title]);
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $artist_info['follower_count'] = $rows['follower_count'];

    return $artist_info;
}
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>

        <div id="page-container">
            <div id="artist-bio">
                <div id="artist-image">
                    <img title="<?php echo ucwords($artist_info['artist_title']); ?> image" alt="<?php ucwords($artist_info['artist_title']) ?>" src="artist-images/download.png">
                </div>


                <div id="artist-summary">
                    <h1><?php echo ucwords($artist_info['artist_title']); ?> Songs</h1>
                    <p><?php echo $artist_info['track_count'];?> Tracks | <?php echo $artist_info['like_count'] ?> Likes | <?php echo $artist_info['follower_count'] ?> Followers</p>
                </div>
                
                <div id="artist-desc">
                    <h2>Bio</h2>
                    <p id = "artist-desc-txt"><?php echo ucwords($artist_info['artist_desc']);?></p>
                </div>
            </div>
            <div id = "top-songs">
                <h3>Top Songs</h3>
                <ul id="top-songs-headers">
                    <li class="song-header-cnt">#</li>
                    <li class="song-header-title">TITLE</li>
                    <li class="song-header-rating">RATINGS</li>
                    <li class="song-header-duration">DURATION</li>
                </ul>
                <?php foreach($artist_info['top_songs'] as $i => $arr):?>
                
                <ul id ="pay-load">
                    <li class="song-header-cnt"><?php echo $i+1; ?></li>
                    <li class="song-header-title"><?php echo ucwords($arr['TrackName']); ?></li>
                    <li class="song-header-rating"><?php echo $arr['avg_rating']; ?></li>
                    <li class="song-header-duration"><?php echo $arr['TrackDuration']; ?></li>
                </ul>
                
                <?php endforeach;?>
            </div>
        </div>Ï
    </body>
</html>
