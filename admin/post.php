<?php

class RY_Toolkit_Admin_Post
{
    protected static $_instance = null;

    public static function instance(): RY_Toolkit_Admin_Post
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        add_filter('manage_pages_columns', [$this, 'add_columns'], 11, 2);
        add_filter('manage_posts_columns', [$this, 'add_columns'], 11, 2);

        add_filter('manage_pages_custom_column', [$this, 'show_columns'], 10, 2);
        add_filter('manage_posts_custom_column', [$this, 'show_columns'], 10, 2);
    }

    public function add_columns($columns, $post_type = 'page')
    {
        $post_types = RY_Toolkit::get_option('show_thumbnails', []);
        if (isset($post_types[$post_type])) {
            $add_index = array_search('title', array_keys($columns));
            $pre_array = array_splice($columns, 0, $add_index);
            return array_merge($pre_array, [
                'ry-thumbnail' => get_post_type_labels(get_post_type_object($post_type))->featured_image,
            ], $columns);
        }

        return $columns;
    }

    public function show_columns($column_name, $post_ID)
    {
        if ($column_name === 'ry-thumbnail') {
            echo get_the_post_thumbnail($post_ID, [40, 60]);
        }
    }
}
