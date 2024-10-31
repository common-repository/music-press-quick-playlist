(function ($) {
    $(document).ready(function () {
        $('.leftcontent').delegate('.playlist_item','click',function () {
            $(this).toggleClass('selected');
        });
        $('.rightcontent .mp-listed').delegate('.playlist_item','click',function () {
            $(this).toggleClass('selected');
        });
        $('#send_data').click(function () {
            $('.leftcontent .mp-list li.selected').appendTo('.mp-listed');
            $('.rightcontent .mp-listed li.selected').removeClass('selected');
        });
        $('#clear_data').click(function () {
            var li_select = $('.rightcontent .mp-listed li.selected');
            li_select.appendTo('.mp-list');
            $('.leftcontent .mp-list li.selected').removeClass('selected');
        });

        $('#clear_all').click(function () {
            $('.rightcontent .mp-listed li').appendTo('.mp-list');
            $('.leftcontent .mp-list li.selected').removeClass('selected');
        });

        $('.clear_nofication').click(function () {
            $('.nofication__item').slideUp("slow");
        });

        //Edit process
        var poster_edit_show_url    =   $('#poster_url'),
            poster_edit_insert      =   $('.poster-opt-edit #posterpicture');
        $('#remove_poster_img').on("click",function () {
            poster_edit_show_url.css('display','none');
            poster_edit_insert.css('display','block');
            $(this).css('display','none');
        });

        // Nofication process
        var nofication_songnull = $(".nofication__songisnull");
        var nofication_added = $(".nofication__added");
        $('.music-press-quick-playlist-wrap #msqp-publishing-action #publish').click(function () {
            var playlist_title = $("#playlist_title").val(),
                playlist_poster = $(".poster-id").attr('data-id-poster');
            var opt_autoplay = '';
            if($('.mpqp-autoplay').is(':checked')){
                opt_autoplay = 1;
            }else{
                opt_autoplay = 0;
            }

            var output_eduarray = {};
            output_eduarray = [];
            $('#list-added').find('li').each(function () {
                output_eduarray.push($(this).attr('data-id'));
            });

            if (playlist_title != '' && $('#list-added').children().length >= 1) {
                $.ajax({
                    type: "POST",
                    url: mpqp_url_init.url,
                    data: {
                        action: 'mpqp_playlist',
                        music_press_quick_playlist_title: playlist_title,
                        music_press_quick_playlist_ids: output_eduarray,
                        autoplay: opt_autoplay,
                        poster: playlist_poster
                    },
                    context: this,
                    beforeSend: function () {
                    },
                    success: function (data) {
                        nofication_added.show();
                        setTimeout("window.location.href='admin.php?page=all-playlist';",500);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('The following error occured: ' + textStatus, errorThrown);
                    }
                });
                return false;
            } else {
                if ($("#playlist_title").val() == '') {
                    $("#playlist_title").addClass('warning');
                }
                if ($("#list-added li").length < 1) {
                    nofication_songnull.slideDown('slow');
                }
            }

        });

        //  Filter project
        $('.music-press-quick-playlist-wrap #filter_songs').unbind().click(function () {
            var musicpressquickplaylist_ajaxcontent = $('.mp-list'),
                musicpressquickplaylist_filter_genre = $('.music-press-quick-playlist-wrap #filter select#genre').find(":selected").val(),
                musicpressquickplaylist_filter_album = $('.music-press-quick-playlist-wrap #filter select#album').find(":selected").val(),
                musicpressquickplaylist_filter_artist = $('.music-press-quick-playlist-wrap #filter select#artist').find(":selected").val(),
                musicpressquickplaylist_filter_limit = $('#filter_limit').val(),
                musicpressquickplaylist_filter_order = $('select#order').val(),
                musicpressquickplaylist_filter_orderby = $('select#orderby').val();

                var input_eduarray = {};
                input_eduarray = [];
                $('#list-added').find('li').each(function () {
                    input_eduarray.push($(this).attr('data-id'));
                });

            $.ajax({
                type: "POST",
                url: mpqp_url_init.url,
                data: {
                    action: 'mpqp_filter',
                    filter_genre: musicpressquickplaylist_filter_genre,
                    filter_album: musicpressquickplaylist_filter_album,
                    filter_artist: musicpressquickplaylist_filter_artist,
                    filter_limit: musicpressquickplaylist_filter_limit,
                    filter_order: musicpressquickplaylist_filter_order,
                    filter_orderby: musicpressquickplaylist_filter_orderby,
                    filter_added: input_eduarray
                },
                context: this,
                beforeSend: function () {
                },
                success: function (data) {
                    musicpressquickplaylist_ajaxcontent.empty();
                        musicpressquickplaylist_ajaxcontent.append(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('The following error occured: ' + textStatus, errorThrown);
                }
            });
            return false;
        });

        //  Delete single playlist
        $('.delete_single_playlist a').off("click").on("click",function () {
            if (confirm("Do you want to delete")){
                var get_id_delete = $(this).attr('data-id');
                $.ajax({
                    type: "POST",
                    url: mpqp_url_init.url,
                    data: {
                        action: 'music_press_single_delete',
                        id_delete: get_id_delete,
                    },
                    context: this,
                    beforeSend: function () {

                    },
                    success: function (data) {

                        location.reload();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('The following error occured: ' + textStatus, errorThrown);
                    }
                });
                return false;
            }


        });

        //  Edit playlist
        $('#replace_playlist #publish').off("click").on("click",function () {
            var get_id_replace = $('#replace_playlist').attr('data-replace-id'),
                replace_get_title = $('#playlist_title').val();
            songidsarray = [];
            $('#list-added').find('li').each(function () {
                songidsarray.push($(this).attr('data-id'));
            });
            var opt_autoplay = '';
            if($('.mpqp-autoplay').is(':checked')){
                opt_autoplay = 1;
            }else{
                opt_autoplay = 0;
            }
            var old_poster_edit_id = $('#poster_url').attr('data-id');
            if ( $('#showidposter').children().length > 0 ) {
                var poster_edit_id =  $('.poster-id').attr('data-id-poster');
            }
            if(songidsarray != ''){
                $.ajax({
                    type: "POST",
                    url: mpqp_url_init.url,
                    data: {
                        action: 'music_press_replace',
                        id_replace: get_id_replace,
                        replace_title: replace_get_title,
                        replace_songids: songidsarray,
                        autoplay: opt_autoplay,
                        poster_id: poster_edit_id,
                        old_poster_id: old_poster_edit_id

                    },
                    context: this,
                    beforeSend: function () {

                    },
                    success: function (data) {
                        alert('playlist has been updated');
                        location.reload();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('The following error occured: ' + textStatus, errorThrown);
                    }
                });
            }else{
                nofication_songnull.show();
            }

            return false;
        });

    });
    $('#posterpicture').live('change',(function (e) {

        //  show preview image

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#preview_image').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $('#preview_image').css('display','block');
        readURL(this);
        var file_data = $('#posterpicture').prop('files')[0];

        var form_data = new FormData();

        form_data.append('file', file_data);
        form_data.append('action', 'mpqp_image_save');

        $.ajax({
            url: mpqp_url_init.url,
            action: 'mpqp_image_save',
            type: 'post',
            contentType: false,
            processData: false,
            data: form_data,
            success: function (data) {
                $('#showidposter').append(data);
            },
            error: function (response) {
                console.log('error');
            }

        });
    }));

})(jQuery);