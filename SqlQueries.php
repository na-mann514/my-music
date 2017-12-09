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
<<<<<<< Updated upstream
    $sql = "SELECT ArtistTitle as fav_artists from likes where UName = ? limit 25";
=======
    $sql = "SELECT ArtistTitle as fav_artists from likez where UName = ? limit 25";
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
    $sql = "select t.TrackName,t.TrackId from tracks t where t.TrackName like % ? %";
    return $sql;
}

function fetch_searchartists() {
    $sql = "select ArtistTitle from artists where ArtistTitle like %?%";
    return $sql;
}

function fetch_searchsuggestions() {
    $sql = "select ArtistTitle from artists where ArtistDescription like %?%";
    return $sql;
}

function fetch_searchalbums() {
    $sql = "select AlbumName from albums where AlbumName like %?%";
    return $sql;
}

function fetch_searchplaylists() {
    $sql = "select PlaylistName from Playlist where PlaylistName like %?%";

    return $sql;
}


