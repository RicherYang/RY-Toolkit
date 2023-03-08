jQuery(function ($) {
    let section = $('#disable-feed-link'),
        disableAll = section.find('#RY_disable_feed_link-all'),
        disableComment = section.find('#RY_disable_feed_link-comments'),
        check_all_disabled = function () {
            if (disableAll.prop('checked')) {
                section.find('label').hide();
                disableAll.closest('label').show();
            } else {
                section.find('label').show();
            }
        },
        check_comments_disabled = function () {
            if (disableComment.prop('checked')) {
                section.find('#RY_disable_feed_link-postcomments').closest('label').hide()
                    .next('br').hide();
            } else {
                section.find('#RY_disable_feed_link-postcomments').closest('label').show()
                    .next('br').show();
            }
        };
    check_all_disabled();
    check_comments_disabled();
    disableAll.on('change', check_all_disabled);
    disableComment.on('change', check_comments_disabled);
});
