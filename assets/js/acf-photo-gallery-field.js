(function($) {
    function initialize_field($el) {
        //$el.doStuff();
    }

    function acf_photo_gallery_remove_media(id, field) {
        $('.acf-photo-gallery-group-' + field + ' .acf-photo-gallery-mediabox-' + id).fadeOut('fast').remove();
        if ($('.acf-photo-gallery-group-' + field + ' .acf-photo-gallery-metabox-list li').length < 1) {
            $('.acf-photo-gallery-group-' + field + ' .acf-photo-gallery-metabox-list').append('<li class="acf-photo-gallery-media-box-placeholder"><span class="dashicons dashicons-format-image"></span></li>');
        }
    }

    $(document).ready(function() {
        $(".acf-photo-gallery-metabox-list").sortable({
            containment: "parent",
            placeholder: "acf-photo-gallery-sortable-placeholder",
            tolerance: 'pointer'
        }).disableSelection();
        $(document).on("click", ".acf_pgf_modal button.cancel", function(){
            $(".acf_pgf_modal").remove();
        });
    });

    function acf_photo_gallery_edit(id, url, title, caption) {
        var html;
        html = '<div id="acf-photo-gallery-metabox-edit-' + id + '" class="acf-edit-photo-gallery">';
        html += '<h3>Edit Image</h3>';
        html += '<label>URL</label><input type="text" value="' + url + '"/>';
        html += '<label><input type="checkbox" value="1"/>Open in new tab</label>';
        html += '<label>Title</label><input type="text" value="' + title + '"/>';
        html += '<label>Caption</label><textarea>' + caption + '</textarea>';
        html += '<button class="button button-primary button-large" type="button">Save Changes</button>';
        html += '<button class="button button-large button-close" type="button" data-close="' + id + '">Close</button>';
        html += '</div>';
        return html;
    }

    /**
     * @param {{ index: number, splice: number }} options
     */
    function acf_photo_gallery_html(attachment, field, options) {
        var edit_box_html, presentation_html, id, url, title, caption;
        id = attachment.id;
        url = attachment.url;
        title = attachment.title;
        caption = attachment.caption;

        var JsonField = jQuery.parseJSON(field);

        if (typeof attachment.sizes.thumbnail != 'undefined') {
            url = attachment.sizes.thumbnail.url;
        }
        edit_box_html = acf_photo_gallery_edit(id, url, title, caption);
        $('.acf-photo-gallery-group-' + JsonField.key + ' .acf-photo-gallery-metabox-edit').append(edit_box_html);
        var $list = $('.acf-photo-gallery-group-' + JsonField.key + ' .acf-photo-gallery-metabox-list');
        presentation_html = '<li class="acf-photo-gallery-mediabox acf-photo-gallery-mediabox-' + id + '" data-id="' + id + '"><a class="dashicons dashicons-dismiss" href="#" data-id="' + id + '" data-field="' + JsonField.key + '" title="Remove this photo from the gallery"></a><input type="hidden" name="' + JsonField._name + '[]" value="' + id + '"/><img src="' + url + '"/></li>';
        if (options.index) {
            var $cursor = $list.children().eq(options.index);
            $cursor.before(presentation_html);
            if (options.splice) { $cursor.remove(); }
        } else {
            $list.prepend(presentation_html);
        }
    }

    function acf_photo_gallery_add_media($el) {
        if ($('.acf-photo-gallery-metabox-add-images').length > 0) {
            if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
                $(document).on('click', '.acf-photo-gallery-metabox-add-images', function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var id = button.prev();
                    var field = button.attr('data-field');
                    var JsonField = jQuery.parseJSON(field);

                    function apgf_open_media_lib(){
                        //On click of the add images button, check if the image limit has been reached
                        var pre_selected_list = $('.acf-photo-gallery-group-' + JsonField.key + ' .acf-photo-gallery-metabox-list li.acf-photo-gallery-mediabox');
                        var images_limit = $('.acf-photo-gallery-group-' + JsonField.key + ' input[name=\'acf-photo-gallery-images_limit\']').val();
                        var gallery_limit_message = 'Your website administrator has set a limit of ' + images_limit + ' images that can be added to this gallery.';
                        if (images_limit != "" && pre_selected_list.length == images_limit) {
                            alert(gallery_limit_message)
                            return false;
                        }
                        $(document).on('click', '.media-modal-content .attachments-browser .attachments li', function() {
                            var selection_list = $('.media-modal-content .attachments-browser .attachments li[aria-checked=true]').length;
                            var check_image_limit = pre_selected_list.length + selection_list;
                            if (images_limit != "" && check_image_limit > images_limit) {
                                $(this).click();
                                alert(gallery_limit_message)
                                return false;
                            }
                        });
                        wp.media.editor.send.attachment = function(props, attachment) {
                            if(attachment){
                                const placeholder = '.acf-photo-gallery-group-' + JsonField.key + ' .acf-photo-gallery-metabox-list li.acf-photo-gallery-media-box-placeholder';
                                if ($(placeholder).length > 0) {
                                    $(placeholder).remove();
                                }
                                acf_photo_gallery_html(attachment, field, { index: 0, splice: 0 });
                            }
                        };
                        wp.media.editor.open(button, function() {});
                    }

                    if(typeof apgf_show_donation !== 'undefined' && apgf_show_donation){
                        $('.acf_pgf_modal').remove();
                        $("body").append("<div class=\"acf_pgf_modal\"><div class=\"acf_pgf_modal-content\"><div class=\"acf_pgf_modal-header\"><h2>Donation</h2></div><div class=\"acf_pgf_modal-body\"><p>The ACF Photo Gallery Field plugin is requesting donations to support its future development.</p><p><label>Would you like to donate?</label><select><option value=\"yes\">Yes, I want to donate</option><option value=\"already\">I have already donated</option><option value=\"later\">Maybe later</option><option value=\"no\">No</option></select></p></div><div class=\"acf_pgf_modal-footer\"><button type=\"button\" class=\"button button-default cancel\">Cancel</button><button type=\"button\" class=\"button button-primary submit\">Submit</button></div></div></div>");
                        $(".acf_pgf_modal").css("display", "block");
                        $(".acf_pgf_modal .acf_pgf_modal-footer button.submit").unbind().on("click", function(){
                            const val = $(".acf_pgf_modal select").val().trim();
                            if(val === "yes"){
                                window.open("https://www.buymeacoffee.com/navzme", "_blank");
                            } else {
                                $(".acf_pgf_modal").remove();
                                $.ajax({
                                    method: "GET",
                                    url: acf.get('ajaxurl'),
                                    data: {
                                        action: "apgf_update_donation",
                                        option: val,
                                        nonce: apgf_nonce
                                    }
                                  });
                                  apgf_open_media_lib();
                            }
                        });
                        $(".acf_pgf_modal .acf_pgf_modal-footer button.cancel").on("click", function(){
                            $(".acf_pgf_modal").remove();
                            apgf_open_media_lib();
                        });
                    } else {
                        apgf_open_media_lib();
                    }

                    return false;
                });
            }
        };
    }

    $(document).on('click', '.acf-photo-gallery-metabox-list .dashicons-dismiss', function() {
        var id = $(this).attr('data-id');
        var field = $(this).attr('data-field');
        if (confirm('You are about to remove this photo from the gallery. Are you sure?')) {
            acf_photo_gallery_remove_media(id, field);
        }
        return false;
    });

    $(document).on('click', '#acf-photo-gallery-metabox-edit .acf-edit-photo-gallery button.button-close', function() {
        var id;
        id = $(this).attr('data-close');
        $('#acf-photo-gallery-metabox-edit #acf-photo-gallery-metabox-edit-' + id).fadeOut('fast');
        $('.acf-gallery-backdrop').remove();
        $('body').css('overflow', 'auto');
        return false;
    });

    $(document).on('submit', '.acf-edit-photo-gallery form', function() {
        var form = $(this).serializeArray();
        var post_id = acf.get('post_id');
        var attachment_id = form.find(x => x.name === "attachment_id")?.value;
        var acf_field_name = acf.getField(form.find(x => x.name === "acf_field_key")?.value).data.name;
        form.push({name: "action", value: "acf_photo_gallery_edit_save"});
        form.push({name: "post_id", value: post_id});
        form.push({name: "nonce", value: apgf_nonce});
        form.push({name: "acf_field_name", value: acf_field_name});
        $("button[type=submit]", this).attr('disabled', true).html('Saving...');
        $('#acf-photo-gallery-metabox-edit-' + attachment_id + ' .acf-photo-gallery-edit-field').each(function(i, obj) {
            var find = form.find(x => x.name === obj.name);
            if(!find){
                form.push({name: obj.name, value: null});
            }
        });
        $.ajax({
            method: "POST",
            url: acf.get('ajaxurl'),
            data: form
        }).done(function() {
            $('.acf_pgf_modal').fadeOut().remove();
        }).fail(function(err) {
            console.log(err);
        });
        return false;
    });

    $(document).on('click', '.acf-photo-gallery-metabox-list .dashicons-edit', function() {
        var $btn = $(this);
        var attachment_id = $btn.attr('data-id');
        var acf_field_key = $btn.attr('data-acf_field_key');
        var modal = $('.acf-photo-gallery-group-' + acf_field_key + ' input[name="acf-photo-gallery-edit-modal"]').val();
        var $list = $('.acf-photo-gallery-group-' + acf_field_key + ' ul.acf-photo-gallery-metabox-list');
        var index = $('.acf-photo-gallery-group-' + acf_field_key + ' ul.acf-photo-gallery-metabox-list li').index();

        if (modal === 'Native') {
            wp.media.editor.send.attachment = function(_, attachment) {
                acf_photo_gallery_html(attachment, acf_field_key, {
                    index: index,
                    splice: 1
                });
            };

            var editor = wp.media.editor.open($btn, function() {}).state();
            editor.set('menu', false);

            var selection = editor.get('selection');
            selection.multiple = false;
            selection.reset([wp.media.attachment(attachment_id)]);

            /**
             * @param {{ id: number }} deleted
             */
            var handleDestroy = function(deleted) {
                $list.children().each(function() {
                    var $elem = $(this);
                    if ($elem.data('id') === deleted.id) {
                        remove($elem.find('.dashicons-dismiss'));
                    }
                });
            };

            var library = editor.get('library');
            library.on('destroy', handleDestroy);
            editor.on('close', function() {
                library.off('destroy', handleDestroy);
            });
            $('.acf-photo-gallery-group-' + field + ' .acf-photo-gallery-metabox-list li.acf-photo-gallery-media-box-placeholder').remove();
        } else {
            $('.acf_pgf_modal').remove();
            $("body").append("<div class=\"acf_pgf_modal\" id=\"acf-photo-gallery-metabox-edit-" + attachment_id  + "\"><div class=\"acf_pgf_modal-content\"><div class=\"acf-edit-photo-gallery\"><div class=\"acf_pgf_modal-header\"><h2>Edit Image</h2></div><div class=\"acf_pgf_modal-body\"><div class=\"acf_pgf_modal-injectable\">Loading...</div></div></div></div></div>");
            $(".acf_pgf_modal").css("display", "block");
            $.ajax({
                method: "GET",
                url: acf.get('ajaxurl'),
                data: {
                    action: "apgf_edit_model",
                    post_id: acf.get('post_id'),
                    attachment_id: attachment_id,
                    acf_field_name: acf.getField(acf_field_key).data.name,
                    acf_field_key: acf_field_key,
                    nonce: apgf_nonce
                }
            }).done(function(data) {
                $(".acf_pgf_modal .acf_pgf_modal-injectable").html(data);
            }).fail(function() {
                $(".acf_pgf_modal .acf-edit-photo-gallery").append("<div class=\"acf_pgf_modal-footer\"><button class=\"button button-large cancel\" type=\"button\">Close</button></div>");
                $(".acf_pgf_modal .acf_pgf_modal-injectable").html("There was an error. Please try again later.");
            });
        }
        return false;
    });

    if (typeof acf.add_action !== 'undefined') {
        /*
         *  ready append (ACF5)
         *
         *  These are 2 events which are fired during the page load
         *  ready = on page load similar to $(document).ready()
         *  append = on new DOM elements appended via repeater field
         *
         *  @type	event
         *  @date	20/07/13
         *
         *  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
         *  @return	n/a
         */
        acf.add_action('ready append', function($el) {
            // search $el for fields of type 'photo_gallery'
            acf.get_fields({ type: 'photo_gallery' }, $el).each(function() {
                initialize_field($(this));
                acf_photo_gallery_add_media($(this));
            });
        });
    } else {
        /*
         *  acf/setup_fields (ACF4)
         *
         *  This event is triggered when ACF adds any new elements to the DOM. 
         *
         *  @type	function
         *  @since	1.0.0
         *  @date	01/01/12
         *
         *  @param	event		e: an event object. This can be ignored
         *  @param	Element		postbox: An element which contains the new HTML
         *
         *  @return	n/a
         */
        $(document).on('acf/setup_fields', function(e, postbox) {
            $(postbox).find('.field[data-field_type="photo_gallery"]').each(function() {
                initialize_field($(this));
                acf_photo_gallery_add_media($(this));
            });
        });
    }

})(jQuery);