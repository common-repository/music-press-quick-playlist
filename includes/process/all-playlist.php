<?php
if (!defined('ABSPATH')) exit;  // if direct access
if (isset($_GET["task"]))
    $task = sanitize_title($_GET["task"]);
else
    $task = '';
if (isset($_GET["id"]))
    $id = intval($_GET["id"]);
else
    $id = 0;
global $wpdb;

switch ($task) {


    case 'edit':
        if ($id) {
            music_press_playlist_edit_playlist($id);
            break;
        }
    default:
        music_press_playlist_show_all_playlist();
        break;
}
function music_press_playlist_show_all_playlist()
{
    ?>
    <div class="wrap music-press-quick-all-playlist">
        <div class="all-playlist-header">
            <h1 class="wp-heading-inline"><?php echo esc_html__('Playlists', 'music-press-quick-playlist') ?></h1>

            <a href="<?php echo get_home_url(); ?>/wp-admin/admin.php?page=add-new"
               class="page-title-action"><?php echo esc_html__('Add New', 'music-press-quick-playlist'); ?>
            </a>
        </div>

        <h2 class="screen-reader-text">Filter posts list</h2>
        <form id="posts-filter" method="get">

            <h2 class="screen-reader-text">Posts list</h2>
            <table class="wp-list-table widefat fixed striped posts">
                <thead>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column">
                        <label class="screen-reader-text"
                               for="cb-select-all-1">Select All</label></td>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <a href="javascript:void(0)">
                            <span>Title</span>
                        </a>
                    </th>
                    <th scope="col" id="shortcode" class="manage-column"><?php echo esc_html__('Shortcode','music-press-quick-playlist') ?></th>
                    <th scope="col" id="number"
                        class="manage-column column-author"><?php echo esc_html__('Number of songs','music-press-quick-playlist') ?>
                    </th>
                    <th scope="col" class="manage-column column-author">
                        <span><?php echo esc_html__('Author', 'music-press-quick-playlist'); ?></span>
                    </th>
                    <th scope="col" id="date" class="manage-column music-press-column-date">
                        <span><?php echo esc_html__('Date', 'music-press-quick-playlist') ?></span>
                    </th>
                </tr>
                </thead>

                <tbody id="the-list">
                <?php
                global $wpdb;
                $mpqp_table = $wpdb->prefix . 'music_press_quick_playlist';
                $mpqp_values = "SELECT * FROM {$mpqp_table}";
                $mpqp_results = $wpdb->get_results($mpqp_values);
                ?>
                <?php
                if (isset($mpqp_results) && $mpqp_results != '' && !empty($mpqp_results)):
                    foreach ($mpqp_results as $results):
                        ?>
                        <tr id="playlist-<?php echo $results->id; ?>"
                            class="iedit author-self level-0 post-<?php echo $results->id; ?> type-post status-publish format-standard hentry">
                            <th scope="row" class="check-column">
                                <label class="screen-reader-text" for="cb-select-43">
                                    Select
                                    rererer
                                </label>
                                <div class="locked-indicator">
                                    <span class="locked-indicator-icon" aria-hidden="true"></span>
                                </div>
                            </th>
                            <td class="title column-title has-row-actions column-primary page-title"
                                data-colname="Title">
                                <div class="locked-info">
                                    <span class="locked-avatar"></span>
                                    <span class="locked-text"></span>
                                </div>
                                <strong>
                                    <a class="row-title"
                                       href="admin.php?page=all-playlist&amp;task=edit&amp;id=<?php echo esc_html($results->id); ?>"
                                       aria-label="“<?php echo esc_html($results->playlist_name); ?>” (Edit)"><?php echo esc_html($results->playlist_name); ?>
                                    </a>
                                </strong>

                                <div class="row-actions">
                                <span class="edit">
                                     <a href="admin.php?page=all-playlist&amp;task=edit&amp;id=<?php echo esc_html($results->id); ?>"
                                        aria-label="Edit “<?php echo esc_html($results->playlist_name); ?>”">
                                       <?php echo esc_html__('Edit', 'music-press-quick-playlist');
                                       ?>
                                     </a> |
                                 </span>
                                    <span href="#" id="single_delete_playlist_<?php echo esc_html($results->id); ?>"
                                          class="trash delete_single_playlist">
                                    <a href="javascript:void(0);" data-id="<?php echo esc_html($results->id); ?>">
                                        <?php echo esc_html__('Delete', 'music-press-quick-playlist'); ?>
                                    </a>
                                </span>
                                </div>
                            </td>
                            <td class="shortcode-item" data-colname="shortcode">
                                <?php
                                $mpqp_autoplay = '';
                                $mpqp_poster = '';
                                $mpqp_value_autoplay = $results->autoplay;
                                $mpqp_value_poster = $results->poster_id;
                                if ($mpqp_value_autoplay == 0) {
                                    $mpqp_autoplay = 'no';
                                } else {
                                    $mpqp_autoplay = 'yes';
                                }
                                if ($mpqp_value_poster == 0) {
                                    $mpqp_poster = 0;
                                } else {
                                    $mpqp_poster = $mpqp_value_poster;
                                }
                                ?>
                                <input type="text" onfocus="this.select();" readonly="readonly"
                                       value="[music_press_quick_playlist id='<?php echo esc_attr($results->id); ?>' autoplay='<?php echo esc_attr($mpqp_autoplay); ?>' posterID='<?php echo esc_attr($mpqp_poster); ?>']"/>

                            </td>
                            <td class="number-of-songs column-number-of-songs" data-colname="songs_number">
                                <span>
                                    <?php
                                    $mpqp_songs_string = $results->song_ids;
                                    $mpqp_songs_array = unserialize($mpqp_songs_string);
                                    if ($mpqp_songs_array != '') {
                                        $song_count = count($mpqp_songs_array);
                                        echo esc_html($song_count);
                                    } else {
                                        echo esc_html__('0', 'music-press-quick-playlist');
                                    }

                                    ?>
                                </span>
                            </td>
                            <td class="author column-author" data-colname="Author">
                                <span>
                                    <?php
                                    $mpqp_author_obj = get_user_by('id', $results->user_id);
                                    echo esc_html($mpqp_author_obj->user_nicename);
                                    ?>
                                </span>
                            </td>
                            <td class="date column-date" data-colname="Date">
                                <?php echo esc_html($results->publish_date); ?>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                else:
                ?>
                <!--    is empty playlist   -->
                <tr id="playlist-empty"
                    class="iedit author-self level-0 post-empty type-post status-publish format-standard hentry">
                    <th scope="row" class="check-column"></th>
                    <td class="title column-title has-row-actions column-primary page-title"
                        data-colname="Title">
                        <strong>
                            <p>
                                <?php echo esc_html__('No Item Found', 'music-press-quick-playlist'); ?>
                            </p>
                        </strong>
                    </td>
                    <?php
                    endif;
                    ?>
                </tbody>

                <tfoot>
                <tr>
                    <td class="manage-column column-cb check-column"><label class="screen-reader-text"
                                                                            for="cb-select-all-2">Select All</label>
                    </td>
                    <th scope="col" class="manage-column column-title column-primary sortable desc">
                        <a href="javascript:void(0)"><span>Title</span></a>
                    </th>
                    <th scope="col" id="shortcode" class="manage-column"><?php echo esc_html__('Shortcode') ?></th>
                    <th scope="col" class="manage-column column-author"><?php echo esc_html__('Number of songs') ?></th>
                    <th scope="col" class="manage-column column-author">
                        <span><?php echo esc_html__('Author', 'music-press-quick-playlist'); ?></span>
                    </th>
                    <th scope="col" class="manage-column music-press-column-date">
                        <span><?php echo esc_html__('Date', 'music-press-quick-playlist'); ?></span>
                    </th>
                </tr>
                </tfoot>

            </table>

        </form>

        <div id="ajax-response"></div>
        <br class="clear">
    </div>
    <?php
    return;
}

?>

<?php
function music_press_playlist_edit_playlist($id)
{
    global $wpdb;
    $mpqp_table = $wpdb->prefix . 'music_press_quick_playlist';
    $mpqp_values = "SELECT * FROM {$mpqp_table} WHERE  id = $id";
    $mpqp_results = $wpdb->get_results($mpqp_values);
    foreach ($mpqp_results as $results) {
        $current_name_playlist = $results->playlist_name;
        $mpqp_songs_string = $results->song_ids;
        $mpqp_songs_array = unserialize($mpqp_songs_string);
    }

    ?>
    <div class="music-press-quick-playlist-wrap music-edit-playlist">
        <div class="nofication">

            <div class="nofication__added nofication__item">
                <p><?php echo esc_html__('Playlist Added', 'music-press-quick-playlist'); ?> <i
                            class="fa fa-check-circle" aria-hidden="true"></i></p>
                <div class="clear_nofication">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </div>
            </div>
            <div class="nofication__songisnull nofication__item">
                <p>
                    <?php echo esc_html__('error songs', 'music-press-quick-playlist'); ?>
                    <i class="fa fa-smile-o" aria-hidden="true"></i>
                </p>
                <div class="clear_nofication">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </div>
            </div>
        </div>
        <div class="wrap-playlist">

            <div class="left-wrap">
                <div class="wrap">
                    <h3 class="playlist-title"><?php echo esc_html__('Edit Playlist', '') ?></h3>
                    <a href="<?php echo get_home_url(); ?>/wp-admin/admin.php?page=add-new"
                       class="page-title-action"><?php echo esc_html__('Add New', 'music-press-quick-playlist'); ?></a>
                </div>

                <div id="titlewrap">
                    <input type="text" name="playlist_title" size="30" value="<?php echo esc_attr($current_name_playlist); ?>"
                           id="playlist_title" spellcheck="true" autocomplete="off" placeholder="Enter title here">
                </div>

                <div id="filter">
                    <select name="parent" id="genre" class="postform">
                        <option selected>Genre</option>
                        <?php
                        $mpqp_args = array(
                            'post_type' => 'mp_genre',
                            'post_status' => 'publish',
                            'order' => 'title',
                            'orderby' => 'DESC',
                            'posts_per_page' => '',
                        );
                        $mpqp_query = new WP_Query($mpqp_args);
                        if ($mpqp_query->have_posts()) : while ($mpqp_query->have_posts()) : $mpqp_query->the_post();
                            ?>
                            <option class="level-0" value="<?php echo get_the_ID(); ?>"><?php the_title(); ?></option>
                        <?php
                        endwhile; endif;
                        ?>
                    </select>
                    <select name="parent" id="album" class="postform">
                        <option selected><?php echo esc_html__('Album', 'music-press-quick-playlist'); ?></option>
                        <?php
                        $mpqp_args = array(
                            'post_type' => 'mp_album',
                            'post_status' => 'publish',
                            'order' => 'title',
                            'orderby' => 'DESC',
                            'posts_per_page' => '',
                        );
                        $mpqp_query = new WP_Query($mpqp_args);
                        if ($mpqp_query->have_posts()) : while ($mpqp_query->have_posts()) : $mpqp_query->the_post();
                            ?>
                            <option class="level-0" value="<?php echo get_the_ID(); ?>"><?php the_title(); ?></option>
                        <?php
                        endwhile; endif;
                        ?>
                    </select>
                    <select name="parent" id="artist" class="postform">
                        <option selected><?php echo esc_html__('Artist', 'music-press-quick-playlist'); ?></option>
                        <?php
                        $mpqp_args = array(
                            'post_type' => 'mp_artist',
                            'post_status' => 'publish',
                            'order' => 'title',
                            'orderby' => 'DESC',
                            'posts_per_page' => '',
                        );
                        $mpqp_query = new WP_Query($mpqp_args);
                        if ($mpqp_query->have_posts()) : while ($mpqp_query->have_posts()) : $mpqp_query->the_post();
                            ?>
                            <option class="level-0" value="<?php echo get_the_ID(); ?>"><?php the_title(); ?></option>
                        <?php
                        endwhile; endif;
                        ?>
                    </select>

                    <select name="parent" id="order" class="postform">
                        <option selected value="none"><?php echo esc_html__('Order', 'music-press-quick-playlist'); ?></option>
                        <option class="level-0"
                                value="0"><?php echo esc_html__('DESC', 'music-press-quick-playlist'); ?></option>
                        <option class="level-0"
                                value="1"><?php echo esc_html__('ASC', 'music-press-quick-playlist'); ?></option>
                    </select>

                    <select name="parent" id="orderby" class="postform">
                        <option selected
                                value="none"><?php echo esc_html__('Order By', 'music-press-quick-playlist'); ?></option>
                        <option class="level-0"
                                value="0"><?php echo esc_html__('Date', 'music-press-quick-playlist'); ?></option>
                        <option class="level-0"
                                value="1"><?php echo esc_html__('Plays', 'music-press-quick-playlist'); ?></option>
                    </select>

                    <input type="text" name="playlist_title" size="30" value="" id="filter_limit" spellcheck="true"
                           autocomplete="off" placeholder="Limit">

                    <button id="filter_songs" class="button button-primary button-large">
                        <?php echo esc_html__('Filter', 'music-press-quick-playlist'); ?>
                    </button>

                </div>

                <div id="searchfield">
                    <h3 class="sbsn"><?php echo esc_html__('Search by song name:', 'music-press-quick-playlist'); ?></h3>
                    <form>
                        <input type="text" name="songautocomplete" class="biginput" id="autocomplete">
                    </form>
                </div><!-- @end #searchfield -->

                <div id="contentwrap">
                    <div class="leftcontent">
                        <h2><?php echo esc_html__('Your Songs ') ?></h2>
                        <ul class="mp-list">
                            <?php
                            $mpqp_query_args = array(
                                'post_type' => 'mp_song',
                                'post_status' => 'publish',
                                'ignore_sticky_posts' => 1,
                                'order' => 'title',
                                'orderby' => 'DESC',
                                'posts_per_page' => '',
                                'post__not_in' => $mpqp_songs_array,
                            );
                            $mpqp_songs = new WP_Query($mpqp_query_args);
                            if ($mpqp_songs->have_posts()) :
                                while ($mpqp_songs->have_posts()) : $mpqp_songs->the_post();
                                    ?>

                                    <li data-id="<?php echo get_the_ID(); ?>" class="playlist_item">
                                        <?php the_title(); ?>
                                    </li>

                                <?php
                                endwhile;
                                ?>
                            <?php endif;
                            ?>
                        </ul>
                    </div>

                    <div class="centercontent">
                        <button id="send_data" class="button button-primary button-large">
                            <?php echo esc_html__('>>','music-press-quick-playlist'); ?>
                        </button>
                        <button id="clear_data" class="button button-primary button-large">
                            <?php echo esc_html__('<<', 'music-press-quick-playlist') ?>
                        </button>
                    </div>

                    <div class="rightcontent">
                        <h3><?php echo esc_html__('Your Playlist', '') ?></h3>
                        <ul class="mp-listed" id="list-added">
                            <?php
                            if (isset($mpqp_songs_array) && $mpqp_songs_array != ''):
                                foreach ($mpqp_songs_array as $array):
                                    ?>
                                    <li data-id="<?php echo $array; ?>" class="playlist_item">
                                        <?php echo get_the_title($array); ?>
                                    </li>
                                <?php
                                endforeach;
                            endif
                            ?>
                        </ul>
                        <div id="replace_playlist" data-replace-id="<?php echo esc_attr($results->id); ?>">
                            <input name="original_publish" type="hidden" id="original_publish" value="Publish">
                            <input type="submit" name="publish" id="publish" class="button button-primary button-large"
                                   value="Update">
                        </div>
                        <div class="remove_songids">
                            <button id="clear_all" class="button button-primary button-large">
                                <?php echo esc_html__('Clear All', 'music-press-quick-playlist') ?>
                            </button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <div class="right-wrap">
                <h2 class="bacsic-option"><?php echo esc_html__('Basic Option', 'music-press-quick-playlist') ?></h2>
                <section class="option1 autoplay-opt">
                    <input type="checkbox" value="" class="mpqp-autoplay" name="check" <?php if($results->autoplay == 1): ?>checked<?php endif; ?> />
                    <label><?php echo esc_html__('Autoplay?', 'music-press-quick-playlist'); ?></label>
                </section>

                <section class="option2 poster-opt-edit">
                    <form enctype="multipart/form-data">
                        <label><?php echo esc_html__('Poster Image:', 'music-press-quick-playlist'); ?></label>
                        <div id="poster_url" data-id="<?php echo esc_attr($results->poster_id); ?>" class="show-preview-poster">
                            <?php echo wp_get_attachment_image($results->poster_id,'full'); ?>
                        </div>
                        <a href="javascript:void(0)" class="button button-primary button-large" style="display: inline-block" id="remove_poster_img"><?php echo esc_html__('Remove','music-press-quick-playlist'); ?></a>
                        <input type="file" id="posterpicture" name="upload" style="display: none">
                    </form>
                    <div id="showidposter"></div>
                </section>
            </div>

        </div>

    </div>
    <script>

        (function ($) {

            var songarray = [
                // list songs
                <?php
                $mpqp_query_args = array(
                    'post_type' => 'mp_song',
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => 1,
                    'order' => 'title',
                    'orderby' => 'DESC',
                    'posts_per_page' => '',
                );
                $mpqp_songs = new WP_Query($mpqp_query_args);
                if ($mpqp_songs->have_posts()) : ?>

                <?php
                while ($mpqp_songs->have_posts()) : $mpqp_songs->the_post();
                ?>
                {value: '<?php the_title(); ?>', data: '<?php the_ID(); ?>'},
                <?php
                endwhile;
                endif
                ?>
            ];
            $('#autocomplete').autocomplete({
                lookup: songarray,
                onSelect: function (suggestion) {
                    var thehtml = '<li data-id="' + suggestion.data + '" class="playlist_item">' + suggestion.value + '</li>';
                    var data_sugg = suggestion.data;
                    var output_eduarray = {};
                    output_eduarray = [];
                    $('#list-added').find('li').each(function () {
                        output_eduarray.push($(this).attr('data-id'));
                    });
                    console.log(jQuery.inArray( data_sugg, output_eduarray));
                    if(jQuery.inArray( data_sugg, output_eduarray ) >= 0){
                        alert('Sorry, this song has already been added to the Playlist');
                        $('#searchfield input').val('');
                    }else{
                        $('#list-added').append(thehtml);
                        $('#searchfield input').val('');
                    }

                }
            });
        })(jQuery);

    </script>

    <?php
    return;
}