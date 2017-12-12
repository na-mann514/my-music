<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();

require_once 'DbConnection.php';
require_once 'SqlQueries.php';

$db_conn = new DBConnection();
$conn = $db_conn->getDBConnection();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$username = $_SESSION['username'];


$user_play_history = get_user_play_history($conn, $username);

$best_songs = get_best_songs($conn);

$songs_by_artists_you_like = get_songs_by_artists_you_like($conn, $username);

$recent_albums = get_recent_albums($conn);

$playlists_of_users_you_follow = get_playlists_of_users_you_follow($conn, $username);

function get_best_songs($conn) {
    $sql = fetch_best_songs();
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':offset', intval(0), PDO::PARAM_INT);
    $stmt->bindValue(':max_limit', intval(10), PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
}

function get_user_play_history($conn, $username) {
    $sql = fetch_user_play_history();
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':uname', $username, PDO::PARAM_STR);
    $stmt->bindValue(':offset', intval(0), PDO::PARAM_INT);
    $stmt->bindValue(':max_limit', intval(10), PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
}

function get_songs_by_artists_you_like($conn, $username) {
    $sql = fetch_songs_by_artist_you_like();
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':uname', $username, PDO::PARAM_STR);
    $stmt->bindValue(':offset', intval(0), PDO::PARAM_INT);
    $stmt->bindValue(':max_limit', intval(10), PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
}

function get_recent_albums($conn) {
    $sql = fetch_recent_albums();
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':offset', intval(0), PDO::PARAM_INT);
    $stmt->bindValue(':max_limit', intval(10), PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
}

function get_playlists_of_users_you_follow($conn, $username) {
    $sql = fetch_playlists_of_users_you_follow();
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':uname', $username, PDO::PARAM_STR);
    $stmt->bindValue(':offset', intval(0), PDO::PARAM_INT);
    $stmt->bindValue(':max_limit', intval(10), PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
}

if (isset($_POST['track-id-rating']) && isset($_POST['rating-value'])) {
    $rating_given = $_POST['rating-value'];
    $track_rated = $_POST['track-id-rating'];
    insert_into_ratings($conn, $username, $rating_given, $track_rated);
}

function insert_into_ratings($conn, $username, $rating_given, $track_rated) {
    $sql = insert_or_update_into_ratings_sql();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$track_rated, $username, $rating_given, $rating_given]);
}

if (isset($_POST['user_play_track']) && isset($_POST['artist-title'])) {
    $track_id = htmlspecialchars($_POST['user_play_track']);
    $artist_title = $_POST['artist-title'];
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
    </head>
    <body>
<?php require_once 'header.php'; ?>
        <div id="page-container">

            <!-- Top Songs-->
            <?php if ($best_songs): ?>
                <div id="top-songs">
                    <div id="top-songs-headers">
                        <h3>Top Songs</h3>
                        <ul id="top-songs-headers" class="row">
                            <li class="song-header-cnt col-sm-1">#</li>
                            <li class="song-header-title col-sm-4">TITLE</li>
                            <li class="song-header-rating col-sm-1">AVG. RATINGS</li>
                            <li class="song-header-duration col-sm-1">DURATION</li>
                            <li class="song-header-rate">RATE</li>
                        </ul>
                        <?php foreach ($best_songs as $i => $arr): ?>
                            <ul id ="nav-top-<?php echo $i; ?>" class="row pay-load">
                                <li class="song-header-cnt col-sm-1"><?php echo $i + 1; ?></li>
                                <form id="nav-top-<?php echo $arr['TrackId']; ?>" method="POST" action="#nav-top-<?php echo $i; ?>">
                                    <input type="hidden" name="user_play_track" id="user_play_track" value="<?php echo $arr['TrackId']; ?>"/>
                                    <input type="hidden" name="artist-title" id="artist-title" value="<?php echo $arr['ArtistTitle'];?>">
                                    <li class="song-header-title col-sm-4">
                                        <a onclick="document.getElementById('nav-top-<?php echo $arr['TrackId']; ?>').submit();">
                                            <?php echo ucwords($arr['TrackName']); ?>
                                        </a>
                                    </li>
                                </form>
                                <li class="song-header-rating col-sm-1"><?php echo number_format($arr['avg_rating'], 2, '.', ''); ?></li>
                                <li class="song-header-duration col-sm-1"><?php echo number_format(($arr['TrackDuration'] / 60000), 2, ':', ''); ?></li>
                                <form id="rating-<?php echo $arr['TrackId']; ?>" method="POST" action="#nav-<?php echo $i; ?>">
                                    <input type="hidden" value="<?php echo $arr['TrackId']; ?>" id="track-id-rating" name="track-id-rating"/>
                                    <li>
                                        <select id="rating-value" name="rating-value" onchange="document.getElementById('rating-<?php echo $arr['TrackId'] ?>').submit();">
                                            <option value="1" >1</option>
                                            <option value="2" >2</option>
                                            <option value="3" >3</option>
                                            <option value="4" >4</option>
                                            <option value="5" >5</option>
                                        </select>
                                    </li>
                                </form>
                            </ul>

                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Fav artist songs-->
            <?php if ($songs_by_artists_you_like): ?>
                <div id="fav-artist-songs">
                    <div id="fav-artist-songs-headers">
                        <h3>Songs by Your Favorite Artists</h3>
                        <ul id="fav-artist-songs-headers" class="row">
                            <li class="song-header-cnt col-sm-1">#</li>
                            <li class="song-header-title col-sm-4">TITLE</li>
                            <li class="song-header-rating col-sm-1">AVG. RATINGS</li>
                            <li class="song-header-duration col-sm-1">DURATION</li>
                            <li class="song-header-rate">RATE</li>
                        </ul>
                        <?php foreach ($songs_by_artists_you_like as $i => $arr): ?>
                            <ul id ="nav-fas-<?php echo $i; ?>" class="row pay-load">
                                <li class="song-header-cnt col-sm-1"><?php echo $i + 1; ?></li>
                                <form id="nav-fas-<?php echo $arr['TrackId']; ?>" method="POST" action="#nav-fas-<?php echo $i; ?>">
                                    <input type="hidden" name="user_play_track" id="user_play_track" value="<?php echo $arr['TrackId']; ?>"/>
                                    <input type="hidden" name="artist-title" id="artist-title" value="<?php echo $arr['ArtistTitle'];?>">
                                    <li class="song-header-title col-sm-4">
                                        <a onclick="document.getElementById('nav-fas-<?php echo $arr['TrackId']; ?>').submit();">
                                            <?php echo ucwords($arr['TrackName']); ?>
                                        </a>
                                    </li>
                                </form>
                                <li class="song-header-rating col-sm-1"><?php echo number_format($arr['avg_rating'], 2, '.', ''); ?></li>
                                <li class="song-header-duration col-sm-1"><?php echo number_format(($arr['TrackDuration'] / 60000), 2, ':', ''); ?></li>
                                <form id="rating-<?php echo $arr['TrackId']; ?>" method="POST" action="#nav-<?php echo $i; ?>">
                                    <input type="hidden" value="<?php echo $arr['TrackId']; ?>" id="track-id-rating" name="track-id-rating"/>
                                    <li>
                                        <select id="rating-value" name="rating-value" onchange="document.getElementById('rating-<?php echo $arr['TrackId'] ?>').submit();">
                                            <option value="1" >1</option>
                                            <option value="2" >2</option>
                                            <option value="3" >3</option>
                                            <option value="4" >4</option>
                                            <option value="5" >5</option>
                                        </select>
                                    </li>
                                </form>
                            </ul>

                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Recent Albums-->
            <?php if ($recent_albums): ?>
                <div id="recent-albums">
                    <div id="recent-albums-headers">
                        <h3>Recent Albums</h3>
                        <ul class="row">

                            <?php foreach ($recent_albums as $i => $arr): ?>
                                <li class="col-md-1"><a href="./album?id=<?php echo $arr['AlbumId']; ?>"><?php echo $arr['AlbumName']; ?></a></li>
                            <?php endforeach; ?>

                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Follower's playlist-->
            <?php if ($playlists_of_users_you_follow): ?>
                <div id="follower-playlist">
                    <div id="playlist-headers">
                        <h3>Playlists of you Users You Follow</h3>
                        <ul class="row">

                            <?php foreach ($playlists_of_users_you_follow as $i => $arr): ?>
                                <li class="col-md-1"><a href="./playlist.php?id=<?php echo $arr['PlaylistId']; ?>"><?php echo $arr['PlaylistName']; ?></a></li>
                            <?php endforeach; ?>

                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Play history-->
            <?php if ($user_play_history): ?>
                <div id="play-history">
                    <div id="play-history-songs-headers">
                        <h3>Your Play History</h3>
                        <ul id="play-history-songs-headers" class="row">
                            <li class="song-header-cnt col-sm-1">#</li>
                            <li class="song-header-title col-sm-4">TITLE</li>
                            <li class="song-header-rating col-sm-1">AVG. RATINGS</li>
                            <li class="song-header-duration col-sm-1">DURATION</li>
                            <li class="song-header-rate">RATE</li>
                        </ul>
                        <?php foreach ($user_play_history as $i => $arr): ?>
                            <ul id ="nav-phs-<?php echo $i; ?>" class="row pay-load">
                                <li class="song-header-cnt col-sm-1"><?php echo $i + 1; ?></li>
                                <form id="nav-phs-<?php echo $arr['TrackId']; ?>" method="POST" action="#nav-phs-<?php echo $i; ?>">
                                    <input type="hidden" name="user_play_track" id="user_play_track" value="<?php echo $arr['TrackId']; ?>"/>
                                    <input type="hidden" name="artist-title" id="artist-title" value="<?php echo $arr['ArtistTitle'];?>">
                                    <li class="song-header-title col-sm-4">
                                        <a onclick="document.getElementById('nav-phs-<?php echo $arr['TrackId']; ?>').submit();">
                                            <?php echo ucwords($arr['TrackName']); ?>
                                        </a>
                                    </li>
                                </form>
                                <li class="song-header-rating col-sm-1"><?php echo number_format($arr['avg_rating'], 2, '.', ''); ?></li>
                                <li class="song-header-duration col-sm-1"><?php echo number_format(($arr['TrackDuration'] / 60000), 2, ':', ''); ?></li>
                                <form id="rating-<?php echo $arr['TrackId']; ?>" method="POST" action="#nav-<?php echo $i; ?>">
                                    <input type="hidden" value="<?php echo $arr['TrackId']; ?>" id="track-id-rating" name="track-id-rating"/>
                                    <li>
                                        <select id="rating-value" name="rating-value" onchange="document.getElementById('rating-<?php echo $arr['TrackId'] ?>').submit();">
                                            <option value="1" >1</option>
                                            <option value="2" >2</option>
                                            <option value="3" >3</option>
                                            <option value="4" >4</option>
                                            <option value="5" >5</option>
                                        </select>
                                    </li>
                                </form>
                            </ul>

                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
        <div class="iframe-container">
            <div style="overflow: hidden;"></div>
            <iframe src='https://open.spotify.com/embed/track/<?php echo $_POST['user_play_track']; ?>' width='100%' height='100' frameborder='0' allowtransparency='true'></iframe>
        </div>

    </body>
</html>