jQuery(function ($) {
    'use strict';

    let section = $('#disable-feed-link'),
        disableAll = section.find('#RY_disable_feed_link-all'),
        disableComment = section.find('#RY_disable_feed_link-comments'),
        check_disabled = function () {
            if (disableAll.prop('checked')) {
                section.find('label').hide()
                    .next('br').hide();
                disableAll.closest('label').show()
                    .next('br').show();
            } else {
                section.find('label').show()
                    .next('br').show();
                if (disableComment.prop('checked')) {
                    section.find('#RY_disable_feed_link-postcomments').closest('label').hide()
                        .next('br').hide();
                } else {
                    section.find('#RY_disable_feed_link-postcomments').closest('label').show()
                        .next('br').show();
                }
            }
        };
    check_disabled();
    disableAll.on('change', check_disabled);
    disableComment.on('change', check_disabled);
});
