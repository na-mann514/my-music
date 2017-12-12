<?php
session_start();
require_once 'DbConnection.php';
require_once 'SqlQueries.php';

$db_conn = new DBConnection();
$conn = $db_conn->getDBConnection();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$keyword = $_POST['keyword'];
$keyword1 = '%'.$keyword.'%';
$_SESSION['keyword1']=$keyword1;

$search_tracks = get_search_tracks($conn, $keyword1);
$search_artists = get_search_artists($conn, $keyword1);
$search_albums = get_search_albums($conn, $keyword1);

function get_search_tracks($conn, $keyword1) {
    $sql = fetch_searchtracks();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$keyword1]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    return $rows;
}
function get_search_artists($conn, $keyword1) {
    $sql = fetch_searchartists();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$keyword1]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
}
function get_search_albums($conn, $keyword1) {
    $sql = fetch_searchalbums();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$keyword1]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    return $rows;
}

if (isset($_POST['track-id-rating']) && isset($_POST['rating-value'])) {
    $rating_given = $_POST['rating-value'];
    $track_rated = $_POST['track-id-rating'];
    $username = $_SESSION['username'];
    insert_into_ratings($conn, $username, $rating_given, $track_rated);
}

function insert_into_ratings($conn, $username, $rating_given, $track_rated) {
    $sql = insert_or_update_into_ratings_sql();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$track_rated, $username, $rating_given, $rating_given]);
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
    <body><?php require_once 'header.php'; ?>

        <div id="page-container">
            
            <!-- Search Tracks-->
            <?php if($search_tracks):?>
            <div id="top-songs">
                <div id="top-songs-headers">
                    <h3>Songs        | <a href="searchbox1.php">View All</a></h3>
                    <ul id="top-songs-headers" class="row">
                
                        <li class="song-header-title col-sm-4">TRACK NAME</li>
                        <li class="song-header-rating col-sm-1">AVG. RATINGS</li>
                        <li class="song-header-duration col-sm-1">DURATION</li>
                        <li class="song-header-rate">RATE</li>
                    </ul>
                    <?php foreach ($search_tracks as $i => $arr): ?>
                        <ul id ="nav-top-<?php echo $i; ?>" class="row pay-load">
                            
                            <form id="nav-top-<?php echo $arr['TrackId']; ?>" method="POST" action="#nav-top-<?php echo $i; ?>">
                                <input type="hidden" name="user_play_track" id="user_play_track" value="<?php echo $arr['TrackId']; ?>"/>
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
            <?php endif;?>
            
            <!-- Search artists-->
            <?php if($search_artists):?>
            <div id="fav-artist-songs">
                <div id="fav-artist-songs-headers">
                    <h3>Artists                 | <a href="searchbox2.php">View All</a></h3>
                    <ul id="fav-artist-songs-headers" class="row">
                    </ul>
                    <?php foreach ($search_artists as $i => $arr): ?>
                        <ul id ="nav-fas-<?php echo $i; ?>" class="row pay-load">
                           
                            <?php $temp1= ucwords($arr['ArtistTitle']); ?>
                            <li class="song-header-title col-sm-10"><a href="artistbio.php?aname=<?php echo $temp1; ?>"> <?php echo ucwords($arr['ArtistTitle']); ?></a></li>
                        </ul>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif;?>
            
            <!-- Recent Albums-->
            <?php if($search_albums):?>
            <div id="recent-albums">
                <div id="recent-albums-headers">
                    <h3> Albums                       | <a href="searchbox3.php">View All</a></h3>
                    <ul class="row">

                        <?php foreach ($search_albums as $i => $arr): ?>
                             <?php $temp7= ucwords($arr['AlbumName']); ?>
                            <li class="col-md-5"><a href="artistbio.php?aname=<?php echo $temp7; ?>"><?php echo $arr['AlbumName']; ?></a></li>
                        <?php endforeach; ?>

                    </ul>
                </div>
            </div>
            <?php endif;?>
            
            
            
        </div>
        <div class="iframe-container">
            <div style="overflow: hidden;"></div>
            <iframe src='https://open.spotify.com/embed/track/<?php echo $_POST['user_play_track']; ?>' width='100%' height='100' frameborder='0' allowtransparency='true'></iframe>
        </div>

    </body>
</html>