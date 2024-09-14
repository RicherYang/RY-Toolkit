import $ from 'jquery';

$(function () {
    if (null !== document.getElementById('ry-toolkit-options-frontend')) {
        $('#ry-toolkit-options-frontend').on('change', 'input[type="checkbox"]', function () {
            const $option = $(this);
            if ($option.attr('id').substring(0, 28) == 'RY_Toolkit_disable_feed_link') {
                switch ($option.attr('id').substring(29)) {
                    case 'all':
                        if ($option.prop('checked')) {
                            $option.closest('fieldset').find('label').hide();
                            $option.closest('label').show();
                        } else {
                            $option.closest('fieldset').find('label').show();
                            $option.closest('fieldset').find('input:checked').trigger('change');
                        }
                        break;
                    case 'comments':
                        if ($option.prop('checked')) {
                            $('#RY_Toolkit_disable_feed_link-postcomments').closest('label').hide();
                        } else {
                            $('#RY_Toolkit_disable_feed_link-postcomments').closest('label').show();
                        }
                        break;
                }
            }
        });
        $('#ry-toolkit-options-frontend input[type="checkbox"]').trigger('change');
    }

    if (null !== document.getElementById('ry-toolkit-options-sitemap')) {
        $('#ry-toolkit-options-sitemap').on('change', 'input[type="checkbox"]', function () {
            const $option = $(this);
            if ($option.attr('id').substring(0, 35) == 'RY_Toolkit_sitemap_disable_provider') {
                switch ($option.attr('id').substring(36)) {
                    case 'posts':
                        if ($option.prop('checked')) {
                            $('.sitemap-posts-options').hide();
                            $('.sitemap-skip-page-options').hide();
                        } else {
                            $('.sitemap-posts-options').show();
                            $('input[name^="RY_Toolkit_sitemap_disable_post_type"]').trigger('change');
                        }
                        break;
                    case 'taxonomies':
                        if ($option.prop('checked')) {
                            $('.sitemap-taxonomies-options').hide();
                        } else {
                            $('.sitemap-taxonomies-options').show();
                        }
                        break;
                }
            }
            if ($option.attr('id').substring(0, 36) == 'RY_Toolkit_sitemap_disable_post_type') {
                switch ($option.attr('id').substring(37)) {
                    case 'page':
                        if ($option.prop('checked')) {
                            $('.sitemap-skip-page-options').hide();
                        } else {
                            $('.sitemap-skip-page-options').show();
                        }
                        break;
                }
            }
        });
        $('#ry-toolkit-options-sitemap input[type="checkbox"]').trigger('change');
    }
});
