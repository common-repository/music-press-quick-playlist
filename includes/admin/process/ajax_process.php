<?php
if (!defined('ABSPATH')) exit;  // if direct access
add_action('wp_ajax_mpqp_image_save', 'mpqp_image_save');
add_action('wp_ajax_nopriv_mpqp_image_save', 'mpqp_image_save');
function mpqp_image_save()
{

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    $mpqp_upload_directory = wp_upload_dir();
    $mpqp_upload_dir = $mpqp_upload_directory['path'];
    $mpqp_upload_overrides = array('test_form' => false);
    $mpqp_files = $_FILES['file'];
    $mpqp_pid = '';
    if ($mpqp_files['name']) {
        $mpqp_uploadedfile = array(
            'name' => $mpqp_files['name'],
            'type' => $mpqp_files['type'],
            'tmp_name' => $mpqp_files['tmp_name'],
            'error' => $mpqp_files['error'],
            'size' => $mpqp_files['size']
        );
    }
    $mpqp_movefile = wp_handle_upload($mpqp_uploadedfile, $mpqp_upload_overrides);

    if ($mpqp_movefile && !isset($mpqp_movefile['error'])) {
        $mpqp_attachment = array(
            'guid' => $mpqp_movefile['url'],
            'post_mime_type' => $mpqp_movefile['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($mpqp_upload_dir . '/' . $mpqp_files['name'])),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $mpqp_attach_id = wp_insert_attachment($mpqp_attachment, $mpqp_upload_dir . '/' . $mpqp_files['name'], $mpqp_pid);
        $mpqp_attach_data = wp_generate_attachment_metadata($mpqp_attach_id, $mpqp_upload_dir . '/' . $mpqp_files['name']);
        wp_update_attachment_metadata($mpqp_attach_id, $mpqp_attach_data);
        set_post_thumbnail($mpqp_pid, $mpqp_attach_id);

    }
    ?>
    <div class="poster-id" data-id-poster="<?php echo esc_attr($mpqp_attach_id); ?>" style="display: none"></div>
    <?php
    exit;
}

/** Ajax Post */
add_action('wp_ajax_nopriv_mpqp_playlist', 'mpqp_playlist_init');
add_action('wp_ajax_mpqp_playlist', 'mpqp_playlist_init');
function mpqp_playlist_init()
{
    if(isset($_POST)){
        $mpqp_get_title = sanitize_text_field($_POST['music_press_quick_playlist_title']);
        $mpqp_get_slug = sanitize_title($mpqp_get_title);
        $mpqp_get_ids = array_map('absint',$_POST['music_press_quick_playlist_ids']);
        $mpqp_get_songids = serialize($mpqp_get_ids);
        $mpqp_get_poster = intval($_POST['poster']);
        $mpqp_user = get_current_user_id();
        $mpqp_now_time = current_time('mysql');
        $mpqp_autoplay = intval($_POST['autoplay']);
    }

    global $wpdb;

    $mpqp_table = $wpdb->prefix . 'music_press_quick_playlist';

    $mpqp_query = "SELECT * FROM {$mpqp_table} WHERE status = 1";

    $mpqp_data = array(
        'playlist_name' => $mpqp_get_title,
        'slug' => $mpqp_get_slug,
        'song_ids' => $mpqp_get_songids,
        'user_id' => $mpqp_user,
        'publish_date' => $mpqp_now_time,
        'autoplay' => $mpqp_autoplay,
        'poster_id' => $mpqp_get_poster
    );

    $mpqp_where = array('%s', '%s', '%s', '%d');

    $mpqp_info = $wpdb->insert($mpqp_table, (array)$mpqp_data, (array)$mpqp_where);

    exit;

}

// Filter Process
/** Ajax Post */
add_action('wp_ajax_nopriv_mpqp_filter', 'mpqp_filter_init');
add_action('wp_ajax_mpqp_filter', 'mpqp_filter_init');
function mpqp_filter_init()
{
    $mpqp_filter_genreID = sanitize_text_field($_POST['filter_genre']);
    $mpqp_filter_albumID = sanitize_text_field($_POST['filter_album']);
    $mpqp_filter_artistID = sanitize_text_field($_POST['filter_artist']);
    $mpqp_filter_limit = intval($_POST['filter_limit']);
    $mpqp_filter_order =  sanitize_text_field($_POST['filter_order']);
    $mpqp_filter_orderby = sanitize_text_field($_POST['filter_orderby']);
    $mpqp_filter_added = array_map('absint',$_POST['filter_added']);
    $mpqp_filter_finish_genreID = '';
    $mpqp_filter_finish_albumID = '';
    $mpqp_filter_finish_artistID = '';
    $mpqp_filter_finish_limit = '';
    $mpqp_filter_finish_order = 'DESC';
    $mpqp_filter_finish_orderby = 'title';
    $mpqp_filter_finish_metakey = '';

    if ($mpqp_filter_genreID != 'Genre') {
        $mpqp_filter_finish_genreID = $mpqp_filter_genreID;
    }
    if ($mpqp_filter_albumID != 'Album') {
        $mpqp_filter_finish_albumID = $mpqp_filter_albumID;
    }
    if ($mpqp_filter_artistID != 'Artist') {
        $mpqp_filter_finish_artistID = $mpqp_filter_artistID;
    }
    if ($mpqp_filter_limit != '') {
        $mpqp_filter_finish_limit = $mpqp_filter_limit;
    }
    if ($mpqp_filter_order != 'none') {
        if ($mpqp_filter_order == 0) {
            $mpqp_filter_finish_order = 'DESC';
        } else {
            $mpqp_filter_finish_order = 'ASC';
        }
    }
    if ($mpqp_filter_orderby != 'none') {
        if ($mpqp_filter_orderby == 0) {
            $mpqp_filter_finish_orderby = 'date';
        } else {
            $mpqp_filter_finish_order = 'meta_value_num';
            $mpqp_filter_finish_metakey = 'mp_count_play';
        }
    }
    $mpqp_filterquery_args = array(
        'post_type' => 'mp_song',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'song_genre',
                'value' => $mpqp_filter_finish_genreID,
                'compare' => 'LIKE',
            ),
            array(
                'key' => 'song_album',
                'value' => $mpqp_filter_finish_albumID,
                'compare' => 'LIKE',
            ),
            array(
                'key' => 'song_artist',
                'value' => $mpqp_filter_finish_artistID,
                'compare' => 'LIKE',
            ),
            array(
                'key' => 'song_type',
                'value' => 'audio'
            )
        ),
        'post__not_in' => $mpqp_filter_added,
        'ignore_sticky_posts' => 1,
        'order' => $mpqp_filter_finish_order,
        'orderby' => $mpqp_filter_finish_orderby,
        'meta_key' => $mpqp_filter_finish_metakey,
        'posts_per_page' => $mpqp_filter_finish_limit,

    );
    $mpqp_filter_songs = new WP_Query($mpqp_filterquery_args);
    if ($mpqp_filter_songs->have_posts()) :
        while ($mpqp_filter_songs->have_posts()) : $mpqp_filter_songs->the_post();
            ?>

            <li data-id="<?php echo get_the_ID(); ?>" class="playlist_item">
                <?php the_title(); ?>
            </li>

        <?php
        endwhile;
    endif;
    exit;
}

add_action('wp_ajax_music_press_single_delete', 'music_press_single_delete_init');
add_action('wp_ajax_nopriv_music_press_single_delete', 'music_press_single_delete_init');
function music_press_single_delete_init()
{

    $mpqp_deleteid = $_POST['id_delete'];
    global $wpdb;

    $mpqp_table = $wpdb->prefix . 'music_press_quick_playlist';

    $mpqp_query = "SELECT * FROM {$mpqp_table} WHERE status = 1";

    $mpqp_where = array(
        'id' => $mpqp_deleteid
    );

    $mpqp_format = array('%d');

    $mpqp_info = $wpdb->delete($mpqp_table, (array)$mpqp_where, (array)$mpqp_format);

    exit;
}

add_action('wp_ajax_music_press_replace', 'music_press_replace_init');
add_action('wp_ajax_nopriv_music_press_replace', 'music_press_replace_init');
function music_press_replace_init()
{

    $mpqp_replaceid = intval($_POST['id_replace']);
    $mpqp_replace_title = sanitize_text_field($_POST['replace_title']);
    $mpqp_replace_slug = sanitize_title($mpqp_replace_title);
    $mpqp_replace_songids = array_map('absint',$_POST['replace_songids']);
    $mpqp_update_songids = serialize($mpqp_replace_songids);
    $mpqp_user = get_current_user_id();
    $mpqp_now_time = current_time('mysql');
    $mpqp_replace_autoplay = intval($_POST['autoplay']);
    $mpqp_replace_poster_id = intval($_POST['poster_id']);
    $mpqp_replace_old_poster_id = intval($_POST['old_poster_id']);
    $mpqp_update_poster_id = '';
    if($mpqp_replace_poster_id != ''){
        $mpqp_update_poster_id .= $mpqp_replace_poster_id;
    }else{
        $mpqp_update_poster_id .= $mpqp_replace_old_poster_id;
    }

    //check remove id
     if($mpqp_replace_poster_id != ''){
         wp_delete_attachment( $mpqp_replace_old_poster_id, true );
     }

    global $wpdb;

    $mpqp_table = $wpdb->prefix . 'music_press_quick_playlist';

    $mpqp_query = "SELECT * FROM {$mpqp_table} WHERE status = 1";

    $mpqp_data = array(
        'playlist_name' => $mpqp_replace_title,
        'slug' => $mpqp_replace_slug,
        'song_ids' => $mpqp_update_songids,
        'user_id' => $mpqp_user,
        'publish_date' => $mpqp_now_time,
        'autoplay' => $mpqp_replace_autoplay,
        'poster_id' => $mpqp_update_poster_id
    );

    $mpqp_where = array(
        'id' => $mpqp_replaceid
    );

    $mpqp_info = $wpdb->update($mpqp_table, (array)$mpqp_data, (array)$mpqp_where);

    exit;
}


