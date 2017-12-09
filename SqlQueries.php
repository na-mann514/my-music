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

function fetch_artist_likes_count() {
    $sql = "SELECT count(UName) as like_count from Likes where ArtistTitle = ?";
    return $sql;
}

function fetch_artist_follower_count() {
    $sql = "SELECT count(UName) as follower_count from Followers where UFollowing = ?";
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
    $sql = "SELECT PlaylistName,UName FROM Playlist  WHERE UName= ?";
    return $sql;
}

function fetch_playlisttracks() {
    $sql = "SELECT TrackId FROM Playlist P join PlayTracks PT WHERE UName=? and P.PlaylistId=PT.PlaylistId ";
    return $sql;
}

function fetch_searchtracks() {
    $sql = "select t.TrackName,t.TrackId from tracks t where t.TrackName like '%?%'";
    return $sql;
}

function fetch_searchalbums() {
    $sql = "select t.TrackName,t.TrackId from tracks t where t.TrackName like '%?%'";
    return $sql;
}

function fetch_searchplaylists() {
    $sql = "select t.TrackName,t.TrackId from tracks t where t.TrackName like '%?%'";
    return $sql;
}


