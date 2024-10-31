<?php
if (!defined('ABSPATH')) exit;  // if direct access
/*
Plugin Name: Music Press Quick Playlist
Plugin URI: http://wordpress.templaza.net/plugins/music-press/album/sing-me-to-sleep/
Description: Create playlist for Music Press Plugin.
Version: 1.0
Author: hungkvdotcom, templaza, tuyennv
Author URI: http://templaza.com/
License: GPLv2 or later
*/

if (class_exists('Music_Press_Pro')) {
    class MusicPressQuickPlayList
    {

        public function __construct()
        {

            $this->music_press_quick_playlist_define_constants();
            $this->music_press_quick_playlist_declare_classes();
            $this->music_press_quick_playlist_admin_process();
            $this->music_press_quick_playlist_declare_shortcodes();
            $this->music_press_quick_playlist_loading_script();
            register_activation_hook(__FILE__, array($this, 'music_press_quick_playlist_activation'));
            add_action('plugins_loaded', array($this, 'music_press_playlist_textdomain'));
            include('includes/music-press-quick-playlist-core-functions.php');

        }

        static function music_press_quick_playlist_template_path()
        {
            return apply_filters('music_press_quick_playlist_template_path', 'music-press-quick-playlist/');

        }

        public function music_press_quick_playlist_activation()
        {
            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();
            $table = $wpdb->prefix . 'music_press_quick_playlist';

            $sql = "CREATE TABLE IF NOT EXISTS " . $table . " (
			id int(100) NOT NULL AUTO_INCREMENT,
			playlist_name varchar(200) NOT NULL,
			slug varchar(200) NOT NULL,
			song_ids char(255) NOT NULL,			    
            user_id int(100) NOT NULL,
            publish_date datetime NOT NULL,
            autoplay int NOT NULL,	
            poster_id int NOT NULL,	
			UNIQUE KEY id (id)
		) $charset_collate";


            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        public function music_press_playlist_textdomain()
        {

            load_plugin_textdomain('music-press-quick-playlist', false, plugin_basename(dirname(__FILE__)) . '/languages/');
        }

        public function music_press_quick_playlist_declare_classes()
        {

            require_once(MUSIC_PRESS_QUICK_PLAYLIST_PLUGIN_DIR . 'includes/classes/class-settings.php');

        }

        public function music_press_quick_playlist_declare_shortcodes()
        {

            require_once(MUSIC_PRESS_QUICK_PLAYLIST_PLUGIN_DIR . 'includes/shortcode/music-press-quick-playlist-shortcode.php');

        }

        public function music_press_quick_playlist_admin_process()
        {

            require_once(MUSIC_PRESS_QUICK_PLAYLIST_PLUGIN_DIR . 'includes/admin/process/ajax_process.php');

        }

        public function music_press_quick_playlist_loading_script()
        {

            add_action('admin_enqueue_scripts', array($this, 'music_press_quick_playlist_admin_scripts'));
        }

        public function music_press_quick_playlist_admin_scripts()
        {

            wp_enqueue_script('jquery');

//            wp_localize_script('music_press_quick_playlist_ajax', 'music_press_quick_playlist_ajax', array('music_press_quick_playlist_ajaxurl' => admin_url('admin-ajax.php')));

            wp_enqueue_style('music_press_member_admin_style', MUSIC_PRESS_QUICK_PLAYLIST_PLUGIN_URL . 'assets/css/admin.css');

            wp_register_script('music-press-jquery.jplayer', MUSIC_PRESS_PRO_PLUGIN_URL . '/assets/js/jquery.jplayer.js', array(), false, true);
            wp_register_script('music-press-jquery.jplayerlist', MUSIC_PRESS_PRO_PLUGIN_URL . '/assets/js/jplayer.playlist.js', array(), false, true);
            wp_register_script('music-press-js', MUSIC_PRESS_PRO_PLUGIN_URL . '/assets/js/music_press.js', array(), false, true);


            wp_register_style('music-press-jplayer-blue', MUSIC_PRESS_PRO_PLUGIN_URL . '/assets/css/playlist/blue.monday/css/jplayer.blue.monday.min.css', false);


            /* Start admin ajax js */
            wp_enqueue_script('ajaxprocess', plugins_url('/assets/js/ajaxprocess.js', __FILE__), array('jquery'));
            wp_enqueue_script('ajaxprocess');

            /*  Autocomplete js   */
            wp_enqueue_script('jquery.autocomplete.min', plugins_url('/assets/js/jquery.autocomplete.min.js', __FILE__), array('jquery'));
            wp_enqueue_script('jquery.autocomplete.min');

            $admin_url = admin_url('admin-ajax.php');
            $music_press_quick_playlist_ajax = array('url' => $admin_url);
            wp_localize_script('ajaxprocess', 'mpqp_url_init', $music_press_quick_playlist_ajax);
            /* End event category ajax js */

        }

        public function music_press_quick_playlist_define_constants()
        {

            $this->define('MUSIC_PRESS_QUICK_PLAYLIST_PLUGIN_URL', plugins_url('/', __FILE__));
            $this->define('MUSIC_PRESS_QUICK_PLAYLIST_PLUGIN_DIR', plugin_dir_path(__FILE__));

        }

        private function define($name, $value)
        {
            if ($name && $value)
                if (!defined($name)) {
                    define($name, $value);
                }
        }

    }

    new MusicPressQuickPlayList();
}
