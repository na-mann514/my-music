<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function fetch_artist_bio_details() {
    $sql = "SELECT ArtistDescription as artist_desc, count(TrackId) as track_count from artists natural join tracks where ArtistTitle = ? LIMIT 1";
    return $sql;
}

function fetch_top_songs_by_artist() {
    $sql = "SELECT t.TrackId, t.TrackName, t.TrackDuration, avg(r.Rating) as avg_rating from tracks t natural join Rating r where t.ArtistTitle = ? group by t.TrackId order by avg_rating desc limit 25";
    return $sql;
}

function does_user_like_artist() {
    $sql = "SELECT count(ltime) as rec_count from Likes where ArtistTitle = ? AND UName = ?";
    return $sql;
}

function does_user_follow_user1() {
    $sql = "SELECT count(*) as rec_count from Followers where UName = ? AND UFollowing = ? ";
    return $sql;
}

function fetch_artist_likes_count() {
    $sql = "SELECT count(UName) as like_count from Likes where ArtistTitle = ?";
    return $sql;
}

function fetch_artist_follower_count() {
    $sql = "SELECT count(UName) as follower_count from Followers where UFollowing = ?";
    return $sql;
}

function check_if_artist_exists() {
    $sql = "SELECT ArtistTitle from artists where ArtistTitle = ?";
    return $sql;
}

function fetch_all_tracks_of_artist() {
    $sql = "SELECT t.TrackId, t.TrackName, t.TrackDuration, ifnull(avg(r.rating),0) as avg_rating from tracks t left outer join Rating r on t.TrackId = r.TrackId where t.ArtistTitle= ? group by t.TrackId";
    return $sql;
}

function fetch_user_bio_details() {
    $sql = "SELECT Email as EmailId,UName from user where UName = ?";
    return $sql;
}

function fetch_user_followers_count() {
    $sql = "SELECT count(UName) as followers_count from Followers where UFollowing = ?";
    return $sql;
}

function fetch_user_followers() {
    $sql = "SELECT UName as followers from Followers where UFollowing = ?";
    return $sql;
}

function fetch_user_following_count() {
    $sql = "SELECT count(UFollowing) as following_count from Followers where UName = ?";
    return $sql;
}

function fetch_user_following() {
    $sql = "SELECT UFollowing as following from Followers where UName = ?";
    return $sql;
}

function fetch_fav_artists() {
    $sql = "SELECT ArtistTitle as fav_artists from likes where UName = ? limit 25";
    return $sql;
}

function fetch_self_playlists() {
    $sql = "SELECT PlaylistName as self_playlist from Playlist where UName = ?";
    return $sql;
}

function fetch_other_users_playlists() {
    $sql = "SELECT PlaylistName as users_playlist from Playlist where UName = ? and Is_Private='0'";
    return $sql;
}

function fetch_playlistname() {
    $sql = "SELECT PlaylistName FROM Playlist  WHERE PlaylistName= ?";
    return $sql;
}

function fetch_playlisttracks() {
    $sql = "SELECT t.TrackId, t.TrackName, t.TrackDuration, avg(r.Rating)as avg_rating,t.ArtistTitle from tracks t join Rating r where t.TrackId IN (SELECT TrackId FROM Playlist P join PlayTracks PT WHERE PlaylistName=? and P.PlaylistId=PT.PlaylistId) group by t.TrackId order by avg_rating desc limit 25";
    return $sql;
}

function fetch_artistfromtracks() {
    $sql = "select ArtistTitle from tracks where TrackId=?";
    return $sql;
}

function fetch_searchtracks() {

    $sql="SELECT t.TrackId, t.TrackName, t.ArtistTitle, t.TrackDuration, avg(rating) as avg_rating from tracks t join Rating r where TrackName like ? group by t.TrackId order by avg_rating desc LIMIT 10";
    return $sql;
}

function fetch_searchtracks1() {

    $sql="SELECT t.TrackId, t.TrackName, t.ArtistTitle, t.TrackDuration, avg(rating) as avg_rating from tracks t join Rating r where TrackName like ? group by t.TrackId order by avg_rating desc LIMIT 50";
    return $sql;
}

function fetch_searchalbums() {
    $sql = "select AlbumId,AlbumName from albums where AlbumName like ? limit 12";
    return $sql;
}

function fetch_searchalbums1() {
    $sql = "select AlbumId,AlbumName from albums where AlbumName like ?";
    return $sql;
}

function fetch_searchartists() {
    $sql = "select ArtistTitle from artists where ArtistTitle like ? limit 12";
    return $sql;
}
function fetch_searchartists1() {
    $sql = "select ArtistTitle from artists where ArtistTitle like ?";
    return $sql;
}
function insert_into_likes() {
    $sql = "INSERT INTO `Likes` (ArtistTitle, UName, ltime) values(?, ?, now())";
    return $sql;
}

function insert_into_followers() {
    $sql = "INSERT INTO `Followers` (UName, UFollowing, Ftime) values(?, ?, now())";
    return $sql;
}

function delete_from_followers() {
    $sql = "DELETE from Followers WHERE UName = ? and UFollowing = ?";
    return $sql;
}

function insert_into_play_history() {
    $sql = "INSERT INTO PlayHistory values(?, ?, ?, now())";
    return $sql;
}

function fetch_user_play_history() {
    $sql = "SELECT t.TrackId, t.TrackName, t.ArtistTitle, t.TrackDuration from tracks t join PlayHistory p on t.TrackId = p.TrackId where UName = :uname order by PTime desc LIMIT :offset , :max_limit";
    return $sql;
}

function fetch_best_songs() {
    $sql = "SELECT t.TrackId, t.TrackName, t.ArtistTitle, t.TrackDuration, avg(rating) as avg_rating from tracks t join Rating r on r.TrackId = t.TrackId group by t.TrackId order by avg_rating desc LIMIT :offset , :max_limit";
    return $sql;
}

function fetch_songs_by_artist_you_like() {
    $sql = "SELECT x.TrackId, x.TrackName, x.ArtistTitle, x.TrackDuration, ifnull(avg(r.Rating),0) as avg_rating FROM (SELECT t.TrackId, t.TrackName, t.ArtistTitle, t.TrackDuration from tracks t join Likes l on (l.ArtistTitle = t.ArtistTitle) where l.UName = :uname) as x left outer join Rating r on r.TrackId = x.TrackId group by x.TrackId order by avg_rating desc limit :offset , :max_limit";
    return $sql;
}

function fetch_recent_albums() {
    $sql = "select AlbumId, AlbumName from albums order by AlbumReleaseDate desc limit :offset , :max_limit";
    return $sql;
}

function fetch_playlists_of_users_you_follow() {
    $sql = "select PlaylistId, PlaylistName from Playlist p join Followers f on p.UName = f.UFollowing  where f.UName = :uname and Is_Private = 0 limit :offset , :max_limit";
    return $sql;
}

function login() {
$sql="SELECT * FROM login_info WHERE uname=? AND pass=?";
return $sql;
}

function insert_user() {
$sql= "INSERT into user (UName,Name,Email,City) VALUES (?,?,?,?)";
return $sql;
}

function insert_login_info() {
$sql= "INSERT into login_info (UName,Pass) VALUES (?,?)";
return $sql;
}

function album_info() {
$sql="select TrackId,TrackName,TrackDuration,ArtistTitle,AlbumName from tracks join albums where tracks.AlbumId=? and tracks.AlbumId=albums.AlbumId";
return $sql;
}

function album_name() {
$sql="select AlbumName from albums where AlbumId=?";
return $sql;
}


