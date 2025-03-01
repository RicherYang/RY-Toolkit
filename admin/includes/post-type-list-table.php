<?php

class RY_Toolkit_Post_Type_List_Table extends WP_List_Table
{
    protected $orderby;

    protected $order;

    private $wp_core_name = [
        'post',
        'page',
        'attachment',
        'revision',
        'nav_menu_item',
        'custom_css',
        'customize_changeset',
        'oembed_cache',
        'user_request',
        'wp_block',
        'wp_template',
        'wp_template_part',
        'wp_global_styles',
        'wp_navigation',
        'wp_font_family',
        'wp_font_face',
    ];

    public function __construct()
    {
        parent::__construct([
            'singular' => 'ry_toolkit_post_type',
            'plural' => 'ry_toolkit_post_types',
            'ajax' => false,
        ]);

        $this->orderby = strtolower(wp_unslash($_GET['orderby'] ?? ''));
        $this->order = ('desc' === strtolower(wp_unslash($_GET['order'] ?? ''))) ? 'desc' : 'asc';
    }

    public function prepare_items()
    {
        $all_items = get_post_types([], 'objects');
        usort($all_items, [$this, 'sort_post_types']);

        $per_page = $this->get_items_per_page('ry_toolkit_post_type_per_page');

        $offset = ($this->get_pagenum() - 1) * $per_page;
        $this->items = array_slice($all_items, $offset, $per_page);

        $this->_column_headers = [$this->get_columns(), [], $this->get_sortable_columns()];

        $this->set_pagination_args([
            'total_items' => count($all_items),
            'per_page' => $per_page,
        ]);
    }

    public function get_columns()
    {
        return [
            'name' => __('Name', 'ry-toolkit'),
            'label' => __('Label', 'ry-toolkit'),
            'description' => __('Description', 'ry-toolkit'),
            'public' => __('Public', 'ry-toolkit'),
            'show_in_rest' => __('Show in REST', 'ry-toolkit'),
            'capability_type' => __('Capability type', 'ry-toolkit'),
        ];
    }

    protected function get_sortable_columns()
    {
        return [
            'name' => ['name', 'asc'],
            'label' => ['label', 'asc'],
            'public' => ['public', 'asc'],
            'show_in_rest' => ['show_in_rest', 'asc'],
            'capability_type' => ['capability_type', 'asc'],
        ];
    }

    protected function column_name($post_type): void
    {
        printf(
            '<a href="%s">%s</a>',
            esc_url(add_query_arg([
                'page' => 'ry-toolkit-posttype',
                'type' => $post_type->name,
            ], admin_url('admin.php'))),
            esc_html($post_type->name)
        );

        if (in_array($post_type->name, $this->wp_core_name)) {
            echo '<span class="dashicons dashicons-wordpress" aria-hidden="true" style="font-size:.95rem;padding:5px;height:10px"></span>';
        }
    }

    protected function column_label($post_type): void
    {
        echo esc_html($post_type->label);
    }

    protected function column_description($post_type): void
    {
        echo esc_html($post_type->description);
    }

    protected function column_public($post_type): void
    {
        echo $post_type->public ? esc_html__('Yes', 'ry-toolkit') : esc_html__('No', 'ry-toolkit');
    }

    protected function column_show_in_rest($post_type): void
    {
        echo $post_type->show_in_rest ? esc_html__('Yes', 'ry-toolkit') : esc_html__('No', 'ry-toolkit');
    }

    protected function column_capability_type($post_type): void
    {
        echo esc_html($post_type->capability_type);
    }

    private function sort_post_types($a, $b): int
    {
        if (isset($a->{$this->orderby})) {
            if ('asc' === $this->order) {
                return strcasecmp($a->{$this->orderby}, $b->{$this->orderby});
            } else {
                return strcasecmp($b->{$this->orderby}, $a->{$this->orderby});
            }
        }

        return 0;
    }
}
