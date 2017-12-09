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
