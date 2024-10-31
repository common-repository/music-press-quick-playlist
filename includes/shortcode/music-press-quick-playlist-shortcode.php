<?php
if (!defined('ABSPATH')) {
    exit;
}

function music_press_quick_playlist_create_shortcode($mpqp_args)
{
    wp_enqueue_style('music-press-jplayer-blue');
    wp_enqueue_script('music-press-jquery.jplayer');
    wp_enqueue_script('music-press-jquery.jplayerlist');
    $mpqp_autoplay = $mpqp_args['autoplay'];
    $mpqp_poster = $mpqp_args['posterid'];
    $mpqp_playlistid = $mpqp_args['id'];
    $mpqp_song_arr = music_press_pro_get_songs_from_playlist($mpqp_playlistid);
    $mpqp_jplayerid1 = 'jquery_jplayer_N_'.$mpqp_playlistid;
    $mpqp_jplayerid2 = 'jp_container_N_'.$mpqp_playlistid;
    ?>
    <div class="mp_playlist all">
    <div id="<?php echo esc_attr($mpqp_jplayerid2); ?>" class="jp-video jp-video-270p" role="application" aria-label="media player">
        <div class="jp-type-playlist">
            <div class="jp-playlist-head">
                <div id="<?php echo esc_attr($mpqp_jplayerid1); ?>" class="jp-jplayer"></div>
                <div class="jp-gui">
                    <div class="jp-video-play">
                        <button class="jp-video-play-icon" role="button" tabindex="0"><i class="play fa fa-play"></i></button>
                    </div>
                    <div class="jp-interface">
                        <div class="jp-controls">
                            <button class="jp-stop" role="button" tabindex="0"><i class="fa fa-stop"></i></button>
                            <button class="jp-previous" role="button" tabindex="0"><i class="fa fa-step-backward"></i>
                            </button>
                            <button class="jp-play" role="button" tabindex="0"><i class="play fa fa-play"></i><i class="pause fa fa-pause"></i></button>
                            <button class="jp-next" role="button" tabindex="0"><i class="fa fa-step-forward"></i>
                            </button>
                        </div>
                        <div class="jp-progress">
                            <div class="jp-seek-bar">
                                <div class="jp-play-bar"></div>
                            </div>
                        </div>
                        <div class="jp-timer">
                            <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
                            <span class="jp-line">/</span>
                            <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
                        </div>
                        <div class="jp-toggles">
                            <button class="jp-repeat" role="button" tabindex="0"><i class="fa fa-repeat"></i></button>
                            <button class="jp-shuffle" role="button" tabindex="0"><i class="fa fa-random"></i></button>
                            <button class="jp-full-screen" role="button" tabindex="0"><i class="fa fa-arrows-alt"></i></button>
                        </div>
                        <div class="jp-volume-controls">
                            <button class="jp-mute" role="button" tabindex="0"><i class="off fa fa-volume-off"></i><i class="down fa fa-volume-down"></i></button>
                            <div class="jp-volume-bar">
                                <div class="jp-volume-bar-value"></div>
                            </div>
                            <button class="jp-volume-max" role="button" tabindex="0"><i class="fa fa-volume-up"></i></button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="jp-details">
                <div class="jp-title" aria-label="title">&nbsp;</div>
            </div>
            <div class="jp-playlist">
                <ul>
                    <!-- The method Playlist.displayPlaylist() uses this unordered list -->
                    <li>&nbsp;</li>
                </ul>
            </div>
            <div class="jp-no-solution">
                <span>Update Required</span>
                To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
            </div>
        </div>
    </div>
</div>

    <script type="text/javascript">
        //<![CDATA[
        jQuery(document).ready(function () {

            new jPlayerPlaylist({
                jPlayer: "#<?php echo esc_attr($mpqp_jplayerid1); ?>",
                cssSelectorAncestor: "#<?php echo esc_attr($mpqp_jplayerid2); ?>"
            }, [
                <?php if ( $mpqp_song_arr ) :
                $mpqp_mp_count = 1;
                foreach ($mpqp_song_arr as $mpqp_song){
                $mpqp_mp_count_item = $mpqp_mp_count;
                if( $mpqp_mp_count < 10 ){
                    $mpqp_mp_count_item = '0'.$mpqp_mp_count;
                }
                $mpqp_file_type = get_field('song_type',$mpqp_song);
                if($mpqp_file_type=='video'){
                    if(get_field('song_video',$mpqp_song)){
                        $mpqp_file = get_field('song_video',$mpqp_song);
                    }
                    if(get_field('song_video_cover',$mpqp_song)){
                        $mpqp_file = get_field('song_video_cover',$mpqp_song);
                    }
                    if($mpqp_file){
                        $mpqp_url = wp_get_attachment_url( $mpqp_file );
                    }else{
                        $mpqp_url = get_field('song_video_link',$mpqp_song);
                    }

                }else{
                    if(get_field('song_audio',$mpqp_song)){
                        $mpqp_file = get_field('song_audio',$mpqp_song);
                    }
                    if(get_field('song_audio_cover',$mpqp_song)){
                        $mpqp_file = get_field('song_audio_cover',$mpqp_song);
                    }
                    if($mpqp_file){
                        $mpqp_url = wp_get_attachment_url( $mpqp_file );
                    }else{
                        $mpqp_url = get_field('song_audio_link',$mpqp_song);
                    }
                }
                $mpqp_artists = get_field('song_artist',$mpqp_song);
                $mpqp_bands = get_field('song_band',$mpqp_song);
                if($mpqp_artists != null){
                    $mpqp_count = count($mpqp_artists);
                    $i=1;
                    $mpqp_song_artist='';
                    foreach ($mpqp_artists as $mpqp_artist){
                        if($i== $mpqp_count){
                            $mpqp_song_artist .= get_the_title($mpqp_artist);
                        }else{
                            $mpqp_song_artist .= get_the_title($mpqp_artist). esc_html__(', ', 'music-press-quick-playlist');
                        }
                        $i++;
                    }
                }else{
                    if($mpqp_bands != ''){
                        $mpqp_count = count($mpqp_bands);
                        $i=1;
                        $mpqp_song_artist='';
                        foreach ($mpqp_bands as $mpqp_band){
                            if($i== $mpqp_count){
                                $mpqp_song_artist .= get_the_title($mpqp_band);
                            }else{
                                $mpqp_song_artist .= get_the_title($mpqp_band). esc_html__(', ', 'music-press-quick-playlist');
                            }
                            $i++;
                        }
                    }else{
                        $mpqp_song_artist = esc_html__('No Artist','music-press-quick-playlist');
                    }
                }
                ?>
                {
                    title:  "<?php echo '<span>'. esc_html($mpqp_mp_count_item) .'.<span></span></span>'.esc_attr(get_the_title($mpqp_song));?>",
                    <?php if(isset($mpqp_song_artist) && $mpqp_song_artist != ''): ?>
                    artist:"<?php echo esc_attr($mpqp_song_artist);?>",
                    <?php endif; ?>
                    <?php if($mpqp_file_type=='video'){?>
                    m4v: "<?php echo esc_url($mpqp_url);?>",
                    <?php }else{ ?>
                    mp3: "<?php echo esc_url($mpqp_url);?>",
                    songID:"<?php echo esc_attr($mpqp_song);?>",
                    <?php }?>
                    icondownload:"fa-download",
                    urldl:"<?php echo esc_url(get_permalink($mpqp_file));?>",
                    file_id:"<?php echo esc_attr($mpqp_file);?>",
                    <?php if($mpqp_poster != 0): ?>
                    poster: "<?php  echo esc_url(wp_get_attachment_url( $mpqp_poster )); ?>"
                    <?php endif; ?>
                },
                <?php
                $mpqp_mp_count ++;
                } endif;?>
            ], {
                <?php if($mpqp_autoplay == 'yes'){
                    ?>playlistOptions: {
                    autoPlay: true
                },
                <?php }?>
                swfPath: "<?php echo esc_url(MUSIC_PRESS_PRO_PLUGIN_URL.'/assets/js');?>",
                supplied: "webmv, ogv, m4v, oga, mp3",
                size: {
                    width: "100%",
                    height: "auto",
                    cssClass: "album-all"
                },
                useStateClassSkin: true,
                autoBlur: false,
                smoothPlayBar: true,
                keyEnabled: true,
                audioFullScreen: true
            });
        });
        //]]>
    </script>

<?php
}

add_shortcode('music_press_quick_playlist', 'music_press_quick_playlist_create_shortcode');