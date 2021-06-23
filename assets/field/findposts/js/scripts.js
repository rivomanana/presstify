/* globals tify, attachMediaBoxL10n */

"use strict";

let scripts;

/**
 * @name : Findpost
 * @description : Pop-in de récupération de post
 * @usage :
 * 1 - Appeler le script :
 tify_enqueue_findposts( );
 *
 * 2 - Ajouter au lien d'appel de la modale l'instruction :
 onclick=\"findPosts.open( 'target', '[id de la cible]' ); return false;"
 *
 * 3 - Personnalisation de l'action à la soumission depuis la modale (exemple) :
 jQuery(document).ready( function($){
        $( '#find-posts-submit' ).click(function(e) {
            e.preventDefault();
            var $checked = $( '#find-posts-response .found-posts .found-radio > input:checked' );
            
            if( $checked.length )
                $.post( tify.ajax_url, { action : 'tify_get_post_permalink', post_id : $checked.val() }, function( resp ){
                    $( $( '#affected' ).val() ).val( resp );
                    findPosts.close();
                });
            else 
                findPosts.close();        
            
            return false;
        });
    });
 */
(function ($) {
    scripts = {
        open: function (af_name, af_val) {
            let overlay = $('.ui-find-overlay');

            if (overlay.length === 0) {
                $('body').append('<div class="ui-find-overlay"></div>');
                scripts.overlay();
            }

            overlay.show();

            if (af_name && af_val) {
                $('#affected').attr('name', af_name).val(af_val);
            }

            $('#find-posts').show();

            $('#find-posts-input').focus().keyup(function (event) {
                if (event.which === 27) {
                    scripts.close();
                } // close on Escape
            });

            // Pull some results up by default
            scripts.send();

            return false;
        },

        close: function () {
            $('#find-posts-response').html('');
            $('#find-posts').hide();
            $('.ui-find-overlay').hide();
        },

        overlay: function () {
            $('.ui-find-overlay').on('click', function () {
                scripts.close();
            });
        },

        send: function () {
            let post = {
                    ps: $('#find-posts-input').val(),
                    action: $('[name="found_action"]', '#find-posts').length ? $('[name="found_action"]', '#find-posts').val() : 'find_posts',
                    query_args: $('[name="query_args"]', '#find-posts').length ? JSON.parse(decodeURIComponent($('[name="query_args"]', '#find-posts').val())) : {},
                    _ajax_nonce: $('#_ajax_nonce').val()
                },
                post_type = $('[name="post_type"]', '#find-posts').val() ?
                    $('[name="post_type"]', '#find-posts').val() : 'any',
                $response = $('#find-posts-response'),
                $spinner = $('.find-box-search .spinner');


            post.query_args = $.extend(post.query_args, {post_type: post_type});
            $spinner.show();

            $.ajax(tify.ajax_url, {
                type: 'POST',
                data: post,
                dataType: 'json'
            }).always(function () {
                $spinner.hide();
            }).done(function (x) {
                if (!x.success) {
                    $response.text(tify.findpostsl10n.error);
                }

                $response.html(x.data);
            }).fail(function () {
                $response.text(tify.findpostsl10n.error);
            });
        }
    };

    $(document).ready(function () {
        $('#find-posts-submit').on('click', function (event) {
            if (!$('#find-posts-response input[type="radio"]:checked').length) {
                event.preventDefault();
            }
        });
        $('#find-posts .find-box-search :input').keypress(function (event) {
            if (13 === event.which) {
                scripts.send();
                return false;
            }
        });
        $('#find-posts-search').on('click', scripts.send);
        $('#find-posts-post_type').on('change', scripts.send);
        $('#find-posts-close').on('click', scripts.close);
        $('#doaction, #doaction2').on('click',function (event) {
            $('select[name^="action"]').each(function () {
                if ($(this).val() === 'attach') {
                    event.preventDefault();
                    scripts.open();
                }
            });
        });

        // Enable whole row to be clicked
        $('.find-box-inside').on('click', 'tr', function () {
            $(this).find('.found-radio input').prop('checked', true);
        });
    });
})(jQuery);

jQuery(document).ready(function ($) {
    $('#find-posts-submit').on('click', function (e) {
        e.preventDefault();

        let $checked = $('#find-posts-response .found-posts .found-radio > input:checked');

        if ($checked.length) {
            $.post(tify.ajax_url, {action: 'field_findposts_post_permalink', post_id: $checked.val()}, function (resp) {
                $($('#affected').val()).val(resp);
                scripts.close();
            });
        } else {
            scripts.close();
        }
        return false;
    });

    $(document).on('click', '[data-control="findposts"] > button', function() {
        scripts.open('target', '#' + $('.tiFyField-findposts', $(this).closest('[data-control="findposts"]')).attr('id'));
    });
});
