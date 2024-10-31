<?php

if ( ! defined('ABSPATH')) exit;  // if direct access 


class MusicPressQuickPlayList_Settings  {
	
	public function __construct(){

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
    }
	
	public function admin_menu() {
        add_menu_page(
            'Music Playlist',
            'Music Playlist',
            'manage_options',
            'music-press-quick-playlist',
            array( $this, 'music_press_playlist_page_options' ), // Callback, leave empty
            MUSIC_PRESS_QUICK_PLAYLIST_PLUGIN_URL . '/assets/images/music-press-playlist.png',
            11 // Position
        );


        //add_dashboard_page( '', '', 'manage_options', 'qa-setup', '' );
        add_submenu_page( 'music-press-quick-playlist', __( 'All Playlists', 'music-press-quick-playlist' ), __( 'All Playlists', 'music-press-quick-playlist' ), 'manage_options', 'all-playlist', array( $this, 'music_press_playlist_all_playlist' ) );

		//add_dashboard_page( '', '', 'manage_options', 'qa-setup', '' );
		add_submenu_page( 'music-press-quick-playlist', __( 'Add New', 'music-press-quick-playlist' ), __( 'Add New', 'music-press-quick-playlist' ), 'manage_options', 'add-new', array( $this, 'music_press_playlist_add' ) );

        remove_submenu_page('music-press-quick-playlist','music-press-quick-playlist');

//		do_action( 'qa_action_admin_menus' );
		
	}
	
	public function music_press_playlist_page_options(){
		include( MUSIC_PRESS_QUICK_PLAYLIST_PLUGIN_DIR. 'includes/process/setting.php' );
	}

	public function music_press_playlist_add(){
		include( MUSIC_PRESS_QUICK_PLAYLIST_PLUGIN_DIR. 'includes/process/add-new.php' );
	}
    public function music_press_playlist_all_playlist(){
        include( MUSIC_PRESS_QUICK_PLAYLIST_PLUGIN_DIR. 'includes/process/all-playlist.php' );
    }

} new MusicPressQuickPlayList_Settings();

