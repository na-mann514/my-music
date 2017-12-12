<?php
session_start();

require_once 'DbConnection.php';
require_once 'SqlQueries.php';

$db_conn = new DBConnection();
$conn = $db_conn->getDBConnection();
//$artist_title='Maroon';
//$artist_title = htmlspecialchars($_GET['aname']);
$username = $_SESSION['username'];
//$username = htmlspecialchars($_GET['uname']);
//$username = 'dj';
$PlaylistId=$_GET['id'];


$playlist_info = fetch_playlist_details($conn, $PlaylistId);

function fetch_playlist_details($conn, $PlaylistId) {
    $playlist_info = array();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = fetch_playlistname();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$PlaylistId]);
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $playlist_info['PlaylistName'] = $rows['PlaylistName'];

    $sql = fetch_playlisttracks();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$PlaylistId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $playlist_info['playlist_tracks'] = $rows;
    print_r($playlist_info);
    return $playlist_info;
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
    <body><?php require_once 'header.php'; ?>

        <div id="page-container">
            
            <?php if (isset($playlist_info['error'])): ?>
                <div class="alert alert-danger alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?php echo $playlist_info['error']['message']; ?>
                </div>
            <?php endif; ?>

            <?php if (!isset($playlist_info['error'])): ?>
                
                <!-- Displaying Artist Info -->
                <div id="artist-bio" class="row">
                    
                    
                    <div id="summary-and-bio" class="col-sm-7">
                   
                        <div id="artist-summary">
                            <h1>Playlist Name:<?php echo ucwords($playlist_info['PlaylistName']); ?> </h1>
                            
                         
                        </div>
                    
                        
                        <div id="" class="row">
                            <div class="col-sm-3">
                            </div>
                        </div>
                    </div>
                </div>   
                
        
                
                <?php if($playlist_info['playlist_tracks']):?>
                <div id = "top-songs">
                    <h3>Songs:</h3>
                             <ul id="top-songs-headers" class="row">
                            <li class="song-header-cnt col-sm-1">#</li>
                            <li class="song-header-title col-sm-4">TITLE</li>
                            <li class="song-header-rating col-sm-1">AVG. RATINGS</li>
                            <li class="song-header-duration col-sm-1">DURATION</li>
                            <li class="song-header-rate col-sm-4">RATE</li>
                        </ul>
                        <?php foreach ($playlist_info['playlist_tracks'] as $i => $arr): ?>
                            <ul id ="nav-<?php echo $i; ?>" class="row pay-load">
                                <li class="song-header-cnt col-sm-1"><?php echo $i + 1; ?></li>
                                <form id="nav-<?php echo $arr['TrackId']; ?>" method="POST" action="#nav-<?php echo $i;?>">
                                    <input type="hidden" name="user_play_track" id="user_play_track" value="<?php echo $arr['TrackId']; ?>"/>
                                    <input type="hidden" name="user_play_artist" id="user_play_artist" value="<?php echo $arr['ArtistTitle']; ?>"/>
                                    <li class="song-header-title col-sm-4">
                                        <a onclick="document.getElementById('nav-<?php echo $arr['TrackId']; ?>').submit();">
                                            <?php echo ucwords($arr['TrackName']); ?>
                                        </a>
                                    </li>
                                </form>
                                <li class="song-header-rating col-sm-1"><?php echo number_format($arr['avg_rating'], 2, '.', ''); ?></li>
                                <li class="song-header-duration col-sm-1"><?php echo number_format(($arr['TrackDuration'] / 60000), 2, ':', ''); ?></li>
                                <li class="song-header-rating col-sm-2" >
                                    <fieldset class="rating">
                                        <input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="Rocks!">5 stars</label>
                                        <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="Pretty good">4 stars</label>
                                        <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="Meh">3 stars</label>
                                        <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="Kinda bad">2 stars</label>
                                        <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="Sucks big time">1 star</label>
                                    </fieldset>
                                </li>
                            </ul>

                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
        
                <!-- Displaying Top songs -->
               
                
            <?php endif; ?> 
        </div>

         <div class="iframe-container">
            <div style="overflow: hidden;"></div>

            <iframe src='https://open.spotify.com/embed/track/<?php echo $_POST['user_play_track']; ?>' width='100%' height='100' frameborder='0' allowtransparency='true'></iframe>
        </div>
    </body>
</html>

