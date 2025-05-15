<?php

class RY_Toolkit_Admin_Site_Health
{
    protected static $_instance = null;

    public static function instance(): RY_Toolkit_Admin_Site_Health
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        add_filter('debug_information', [$this, 'add_database_info']);
    }

    public function add_database_info($info)
    {
        global $wpdb;

        $table_info = [];
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
        foreach ($wpdb->get_results('SHOW TABLE STATUS') as $row) {
            $table_info[$row->Name] = sprintf(
                /* Translators: %1$s: Table size, %2$s: Index size, %3$s: Free size, %4$s Engine. */
                esc_html__('Data: %1$s, Index: %2$s, Free: %3$s, Engine %4$s', 'ry-toolkit'),
                number_format_i18n($row->Data_length / MB_IN_BYTES, 2) . 'MB',
                number_format_i18n($row->Index_length / MB_IN_BYTES, 2) . 'MB',
                number_format_i18n($row->Data_free / MB_IN_BYTES, 2) . 'MB',
                $row->Engine
            );
        }

        $info['wp-database']['fields']['database_table_size'] = [
            'label' => __('Table size', 'ry-toolkit'),
            'value' => $table_info,
            'private' => true,
        ];

        return $info;
    }
}
