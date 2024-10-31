<?php
if (!defined('ABSPATH')) exit;  // if direct access
function music_press_pro_get_songs_from_playlist($mpqp_playlistID)
{
    global $wpdb;
    $table = $wpdb->prefix . 'music_press_quick_playlist';
    $mpqp_playlist_values = "SELECT * FROM {$table} WHERE id = $mpqp_playlistID";
    $mpqp_playlist_results = $wpdb->get_results($mpqp_playlist_values);
    $mpqp_song_arr = array();
    if (isset($mpqp_playlist_results) && $mpqp_playlist_results != ''):
        foreach ($mpqp_playlist_results as $results):

            $songs_string = $results->song_ids;
            $mpqp_song_arr = unserialize($songs_string);

        endforeach;
        return $mpqp_song_arr;
    endif;
}
