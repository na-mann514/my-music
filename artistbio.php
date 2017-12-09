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

if(isset($_GET['aname']))
    $artist_title = htmlspecialchars($_GET['aname']);
//$username = $_SESSION['username'];
$username = 'dj';

$artist_info = fetch_artist_details($conn, $artist_title, $username);

function fetch_artist_details($conn, $artist_title, $username) {
    $artist_info['artist_title'] = $artist_title;
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = check_if_artist_exists();
    $stmt = $conn->prepare($sql);
    $stmt->execute([$artist_title]);
    $rowCount = $stmt->rowCount();
    
    if ($rowCount > 0) {
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
        if($stmt->rowCount() > 0) {
            $artist_info['top_songs'] = $rows;
        }
        else {
           $sql = fetch_all_tracks_of_artist();
           $stmt = $conn->prepare($sql);
           $stmt->execute([$artist_title]);
           $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
           $artist_info['all_songs'] = $rows;
        }
        
        

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
    } else {
        $artist_info['error']['message'] = "No such Artist found!";
    }
    return $artist_info;
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
    <body>

        <div id="page-container">
            
            <?php if (isset($artist_info['error'])): ?>
                <div class="alert alert-danger alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?php echo $artist_info['error']['message']; ?>
                </div>
            <?php endif; ?>

            <?php if (!isset($artist_info['error'])): ?>
                
                <!-- Displaying Artist Info -->
                <div id="artist-bio" class="row">
                    <div id="artist-image" class="col-sm-5">
                        <img title="<?php echo ucwords($artist_info['artist_title']); ?> image" alt="<?php ucwords($artist_info['artist_title']) ?>" src="artist-images/download.png">
                    </div>
                    
                    <div id="summary-and-bio" class="col-sm-7">
                    <!-- Displaying Artist Summary -->
                        <div id="artist-summary">
                            <h1><?php echo ucwords($artist_info['artist_title']); ?> Songs</h1>
                            <p><?php echo $artist_info['track_count']; ?> Tracks | <?php echo $artist_info['like_count'] ?> Likes | <?php echo $artist_info['follower_count'] ?> Followers</p>
                        </div>
                    <!-- Displaying Artist Summary -->
                    
                        <?php if($artist_info['artist_desc']):?>
                        <div id="artist-desc">
                            <h2>Bio</h2>
                            <p id = "artist-desc-txt"><?php echo ucwords($artist_info['artist_desc']); ?></p>
                        </div>
                        <?php endif;?>
                        
                        <div id="" class="row">
                            <div class="col-sm-3">
                                <form action="likeUnlikeArtistAction.php" method="post" class="artist-like-form">
                                    <input type="hidden" value="<?php echo $artist_title?>" id="artist_name" name="artist_title"/>
                                    <?php if($artist_info['does_like'] == 1):?>
                                        <input type="checkbox" class="" id="like-check" name="like-check" checked> Like
                                    <?php else: ?>
                                        <input type="checkbox" class="" id="like-check" name="like-check"> Like
                                    <?php endif; ?>
                                        <input type="hidden" name="destination" value="<?php echo $_SERVER["REQUEST_URI"]; ?>"/>
                                        <button type="submit"  class="form-sbmt-btn btn btn-default">Submit</button>
                                </form>
                            </div>
                        </div>
                        <?php if(isset($_GET['success'])):?>
                                <div class="col-sm-4" id="success-msg">
                                    <?php if($artist_info['does_like'] == 1):?>
                                            <p class="alert alert-success">You have Liked <?php echo $artist_title;?></p>
                                        <?php else:?>
                                            <p class="alert alert-info">You have unliked <?php echo $artist_title;?></p>
                                    <?php endif;?>     
                                </div>
                        <?php endif;?> 
                    </div>
                </div>    
                <!-- Displaying Artist Info -->
                
                <!-- Displaying Top songs -->
                <?php if($artist_info['top_songs']):?>
                <div id = "top-songs">
                    <h3>Top Songs</h3>
                    <ul id="top-songs-headers" class="row">
                        <li class="song-header-cnt col-sm-1">#</li>
                        <li class="song-header-title col-sm-5">TITLE</li>
                        <li class="song-header-rating col-sm-1">RATINGS</li>
                        <li class="song-header-duration">DURATION</li>
                    </ul>
                    <?php foreach ($artist_info['top_songs'] as $i => $arr): ?>
                        <ul id ="pay-load">
                            <li class="song-header-cnt col-sm-1"><?php echo $i + 1; ?></li>
                            <li class="song-header-title col-sm-5"><?php echo ucwords($arr['TrackName']); ?></li>
                            <li class="song-header-rating col-sm-1"><?php echo number_format($arr['avg_rating'],2,'.',''); ?></li>
                            <li class="song-header-duration"><?php echo number_format(($arr['TrackDuration'] / 60000), 2, ':', ''); ?></li>
                        </ul>

                    <?php endforeach; ?>
                </div>
                <?php endif;?>
                <!-- Displaying Top songs -->
                
                <!-- Displaying All songs -->
                <?php if($artist_info['all_songs']):?>
                <div id = "top-songs">
                    <h3>All Songs</h3>
                    <ul id="top-songs-headers" class="row">
                        <li class="song-header-cnt col-sm-1">#</li>
                        <li class="song-header-title col-sm-4">TITLE</li>
                        <li class="song-header-rating col-sm-1">AVG. RATINGS</li>
                        <li class="song-header-duration col-sm-1">DURATION</li>
                        <li class="song-header-rate">RATE</li>
                    </ul>
                    <?php foreach ($artist_info['all_songs'] as $i => $arr): ?>
                        <ul id ="pay-load" class="row">
                            <li class="song-header-cnt col-sm-1"><?php echo $i + 1; ?></li>
                            <li class="song-header-title col-sm-4"><?php echo ucwords($arr['TrackName']); ?></li>
                            <li class="song-header-rating col-sm-1"><?php echo number_format($arr['avg_rating'],2,'.',''); ?></li>
                            <li class="song-header-duration col-sm-1"><?php echo number_format(($arr['TrackDuration'] / 60000), 2, ':', ''); ?></li>
                            <li>
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
                <?php endif;?>
                <!-- Displaying All songs -->
                
            <?php endif; ?>
        </div>
    </body>
</html>
