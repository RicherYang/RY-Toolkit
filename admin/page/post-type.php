<?php

final class RY_Toolkit_Admin_Page_PostType extends RY_Toolkit_Admin_Page
{
    protected static $page_type = 'posttype';

    protected $post_type;

    public static function init_page(): void
    {
        add_filter('ry-toolkit/menu_list', [__CLASS__, 'add_menu']);
        add_action('ry-toolkit/add_page-ry-toolkit-posttype', [__CLASS__, 'set_page_load']);
    }

    public static function add_menu($menu_list)
    {
        $menu_list[] = [
            'name' => __('Post type', 'ry-toolkit'),
            'slug' => 'ry-toolkit-posttype',
            'function' => [__CLASS__, 'pre_show_page'],
            'position' => 0,
        ];

        return $menu_list;
    }

    protected function do_init(): void
    {
        if (isset($_GET['type'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $post_type = wp_unslash($_GET['type']); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            $this->post_type = get_post_type_object($post_type);
            if (!$this->post_type) {
                wp_die(esc_html__('The post type does not exist.', 'ry-toolkit'));
            }
            return;
        }

        include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
        include_once RY_TOOLKIT_PLUGIN_DIR . 'admin/includes/post-type-list-table.php';

        add_screen_option('per_page', [
            'default' => 15,
            'option' => 'ry_toolkit_post_type_per_page',
        ]);

        add_filter('set_screen_option_ry_toolkit_post_type_per_page', [$this, 'set_screen_option'], 10, 3);
        set_screen_options();
    }

    public function show_page(): void
    {
        if (!empty($this->post_type)) {
            $wp_roles = wp_roles();
            $post_type = $this->post_type;
            $post_type->supports = get_all_post_type_supports($post_type->name);
            $post_type->taxonomies = get_object_taxonomies($post_type->name, 'objects');

            /* translators: %s: post type name */
            echo '<div class="wrap"><h1>' . esc_html(sprintf(__('Post type "%s"', 'ry-toolkit'), $post_type->name)) . '</h1>';

            include RY_TOOLKIT_PLUGIN_DIR . 'admin/page/html/post-type-info.php';

            echo '</div>';
            return;
        }

        $list_table = new RY_Toolkit_Post_Type_List_Table();
        $list_table->prepare_items();

        echo '<div class="wrap"><h1>' . esc_html__('Post type', 'ry-toolkit') . '</h1>';

        include RY_TOOLKIT_PLUGIN_DIR . 'admin/page/html/post-type.php';

        echo '</div>';
    }
}

RY_Toolkit_Admin_Page_PostType::init_page();
