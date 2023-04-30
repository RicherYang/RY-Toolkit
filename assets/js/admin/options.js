jQuery(function ($) {
    'use strict';

    if (document.getElementById('ry-toolkit-options-frontend') !== null) {
        let disableAll = $('#RY_Toolkit_disable_feed_link-all'),
            disableComment = $('#RY_Toolkit_disable_feed_link-comments'),
            check_disabled = function () {
                if (disableAll.prop('checked')) {
                    $('#disable-feed-link label').hide()
                        .next('br').hide();
                    disableAll.closest('label').show()
                        .next('br').show();
                } else {
                    $('#disable-feed-link label').show()
                        .next('br').show();
                    if (disableComment.prop('checked')) {
                        $('#RY_Toolkit_disable_feed_link-postcomments').closest('label').hide()
                            .next('br').hide();
                    } else {
                        $('#RY_Toolkit_disable_feed_link-postcomments').closest('label').show()
                            .next('br').show();
                    }
                }
            };
        check_disabled();
        disableAll.on('change', check_disabled);
        disableComment.on('change', check_disabled);
    }

    if (document.getElementById('ry-toolkit-options-sitemap') !== null) {
        let sitemapPosts = $('#RY_Toolkit_sitemap_disable_provider-posts'),
            sitemapTaxonomies = $('#RY_Toolkit_sitemap_disable_provider-taxonomies'),
            check_disabled = function () {
                if (sitemapPosts.prop('checked')) {
                    $('.sitemap-posts-options').hide();
                } else {
                    $('.sitemap-posts-options').show();
                }
                if (sitemapTaxonomies.prop('checked')) {
                    $('.sitemap-taxonomies-options').hide();
                } else {
                    $('.sitemap-taxonomies-options').show();
                }
            };
        check_disabled();
        sitemapPosts.on('change', check_disabled);
        sitemapTaxonomies.on('change', check_disabled);
    }
});
