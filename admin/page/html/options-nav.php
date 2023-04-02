<nav class="nav-tab-wrapper wp-clearfix">
    <?php
    foreach ($type_list as $option_type => $option_name) {
        printf(
            '<a href="%1$s" class="nav-tab %2$s">%3$s</a>',
            esc_url(add_query_arg(['type' => $option_type], admin_url('admin.php?page=ry-toolkit-options'))),
            $show_type == $option_type ? 'nav-tab-active' : '',
            esc_html($option_name)
        );
    }
    ?>
</nav>
