<?php

final class RY_Toolkit_Admin_Page_Tools extends RY_Toolkit_Admin_Page
{
    public const TRANSIENT_KEYS = ['_transient_', '_site_transient_'];

    protected static $page_type = 'tools';

    public static function init_page(): void
    {
        add_filter('ry-toolkit/menu_list', [__CLASS__, 'add_menu'], 5);
        add_action('admin_post_ry-toolkit-action', [__CLASS__, 'admin_post_action']);
    }

    public static function add_menu($menu_list)
    {
        $menu_list[] = [
            'name' => __('Tools', 'ry-toolkit'),
            'slug' => 'ry-toolkit-tools',
            'function' => [__CLASS__, 'pre_show_page'],
        ];

        return $menu_list;
    }

    protected function do_init(): void {}

    public function show_page(): void
    {
        global $wpdb;

        $transients = 0;
        foreach (self::TRANSIENT_KEYS as $transient_key) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
            $transients += (int) $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(option_id) FROM {$wpdb->options} WHERE option_name LIKE %s",
                $wpdb->esc_like($transient_key) . '%'
            ));
        }

        $as_counts = -1;
        if (class_exists('ActionScheduler')) {
            $store = ActionScheduler::store();
            if ($store instanceof ActionScheduler_DBStore) {
                $as_counts = $store->action_counts()['complete'] ?? 0;
            }
        }

        wp_localize_script('ry-toolkit-tools', 'RyToolkitToolsParams', [
            'exportUrl' => current_user_can('export') ? RY_Toolkit()->admin->the_action_link('tools', 'export-db') : '',
        ]);
        wp_enqueue_script('ry-toolkit-tools');

        echo '<div class="wrap"><h1>' . esc_html__('Tools', 'ry-toolkit') . '</h1>';

        include RY_TOOLKIT_PLUGIN_DIR . 'admin/page/html/tools.php';

        echo '</div>';
    }

    protected function analyze_tables(): string
    {
        global $wpdb;

        check_ajax_referer('ry-toolkit-action/analyze-tables', '_ry_toolkit_nonce');

        $start = time();

        $analyzed_table = get_transient('ry_analyzed_table');
        if (!is_array($analyzed_table)) {
            $analyzed_table = [];
        }

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
        $tables = $wpdb->get_col('SHOW TABLES');
        sort($tables);
        foreach ($tables as $table_name) {
            if (isset($analyzed_table[$table_name])) {
                continue;
            }

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching , WordPress.DB.PreparedSQL.InterpolatedNotPrepared , PluginCheck.Security.DirectDB.UnescapedDBParameter
            $wpdb->query("ANALYZE TABLE `$table_name`");
            $analyzed_table[$table_name] = true;
            set_transient('ry_analyzed_table', $analyzed_table, 600);

            if (time() - $start > 9) {
                return RY_Toolkit()->admin->the_action_link('tools', 'analyze-tables', [
                    '_wp_http_referer' => urlencode(sanitize_url(wp_unslash($_REQUEST['_wp_http_referer'] ?? ''))), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                ]);
            }
        }

        delete_transient('ry_analyzed_table');
        RY_Toolkit()->admin->add_notice('success', __('Database tables analyzed successfully.', 'ry-toolkit'));

        return '';
    }

    protected function optimize_tables(): string
    {
        global $wpdb;

        check_ajax_referer('ry-toolkit-action/optimize-tables', '_ry_toolkit_nonce');

        $start = time();

        $optimized_table = get_transient('ry_optimized_table');
        if (!is_array($optimized_table)) {
            $optimized_table = [];
        }

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
        $tables = $wpdb->get_col('SHOW TABLES');
        sort($tables);
        foreach ($tables as $table_name) {
            if (isset($optimized_table[$table_name])) {
                continue;
            }

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching , WordPress.DB.PreparedSQL.InterpolatedNotPrepared , PluginCheck.Security.DirectDB.UnescapedDBParameter
            $wpdb->query("OPTIMIZE TABLE `$table_name`");
            $optimized_table[$table_name] = true;
            set_transient('ry_optimized_table', $optimized_table, 600);

            if (time() - $start > 9) {
                return RY_Toolkit()->admin->the_action_link('tools', 'optimize-tables', [
                    '_wp_http_referer' => urlencode(sanitize_url(wp_unslash($_REQUEST['_wp_http_referer'] ?? ''))), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                ]);
            }
        }

        delete_transient('ry_optimized_table');
        RY_Toolkit()->admin->add_notice('success', __('Database tables optimized successfully.', 'ry-toolkit'));

        return '';
    }

    protected function export_db()
    {
        global $wpdb;

        if (wp_doing_ajax()) {
            wp_die(-1, 403);
        }
        if (!current_user_can('export')) {
            wp_die(-1, 403);
        }
        if (!class_exists('ZipArchive')) {
            wp_die(-1, 403);
        }

        check_ajax_referer('ry-toolkit-action/export-db', '_ry_toolkit_nonce');

        $start = time();

        $export_data = get_transient('ry_export_data');
        if (!is_array($export_data)) {
            $export_data = [];

            $export_data['single_row'] = isset($_POST['single-row']);

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
            $export_data['table'] = $wpdb->get_col('SHOW TABLES');
            sort($export_data['table']);
            $export_data['table'] = array_fill_keys($export_data['table'], false);

            $export_data['exported'] = 0;
            $export_data['total'] = 0;
            foreach ($export_data['table'] as $table_name => $status) {
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
                $export_data['total'] += $wpdb->get_var("SELECT COUNT(*) FROM `{$table_name}`");
            }
            $export_data['file'] = wp_tempnam('ry-toolkit-download');

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
            $db_version = $wpdb->get_var('SELECT VERSION()');
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
            $current_db = $wpdb->get_var('SELECT DATABASE()');
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
            $db_charset = $wpdb->get_var('SELECT @@character_set_database');
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
            $db_collation = $wpdb->get_var('SELECT @@collation_database');

            $buffer = "-- ----------------------------------------\n";
            $buffer .= "-- WordPress database export\n";
            $buffer .= '-- Date: ' . date('Y-m-d H:i:s') . "\n";
            $buffer .= "-- Server: {$db_version}\n";
            $buffer .= "-- Database: {$current_db}\n";
            $buffer .= "-- Character Set: {$db_charset}\n";
            $buffer .= "-- Collation: {$db_collation}\n";
            $buffer .= "-- ----------------------------------------\n\n";
            $buffer .= "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n";
            $buffer .= "/*!40101 SET NAMES utf8mb4 */;\n";
            $buffer .= "/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;\n";
            $buffer .= "/*!40103 SET TIME_ZONE='+00:00' */;\n";
            $buffer .= "/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;\n";
            $buffer .= "/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;\n";
            $buffer .= "/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;\n";
            $buffer .= "/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;\n";
            file_put_contents($export_data['file'], $buffer);
        }

        $batch_row_size = 5000;
        $batch_sql_size = $export_data['single_row'] ? 1 : 16 * KB_IN_BYTES;
        foreach ($export_data['table'] as $table_name => $status) {
            if ($status === true) {
                continue;
            }

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
            $table_rows = (int) $wpdb->get_var("SELECT COUNT(*) FROM `{$table_name}`");

            if ($export_data['table'][$table_name] === false) {
                $export_data['table'][$table_name] = 0;

                // 寫入資料表分隔符
                $buffer = "\n";
                $buffer .= "-- ----------------------------------------\n";
                $buffer .= "-- Schema for `{$table_name}` \n";
                $buffer .= "-- ----------------------------------------\n\n";

                // 取得建立資料表的語法
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
                $create_table = $wpdb->get_var("SHOW CREATE TABLE `{$table_name}`", 1, 0);
                if ($create_table) {
                    $buffer .= "DROP TABLE IF EXISTS `{$table_name}`;\n";
                    $buffer .= $create_table . ";\n\n";
                }

                if ($table_rows === 0) {
                    $buffer .= "-- ----------------------------------------\n";
                    $buffer .= "-- Data for `{$table_name}` is empty \n";
                    $buffer .= "-- ----------------------------------------\n\n";
                } else {
                    $buffer .= "-- ----------------------------------------\n";
                    $buffer .= "-- Data for `{$table_name}` \n";
                    $buffer .= "-- ----------------------------------------\n\n";
                    $buffer .= "LOCK TABLES `{$table_name}` WRITE;\n";
                    $buffer .= "/*!40000 ALTER TABLE `{$table_name}` DISABLE KEYS */;\n";
                }
                file_put_contents($export_data['file'], $buffer, FILE_APPEND);
            }

            if ($table_rows > 0) {
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
                $columns = $wpdb->get_results("SHOW COLUMNS FROM `{$table_name}`");
                $column_types = [];
                foreach ($columns as $column) {
                    $type_parts = explode('(', $column->Type);
                    $column_types[$column->Field] = strtoupper($type_parts[0]);
                }

                while (true) {
                    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
                    $rows = $wpdb->get_results($wpdb->prepare(
                        "SELECT * FROM `{$table_name}` LIMIT %d, %d",
                        $export_data['table'][$table_name],
                        $batch_row_size
                    ), ARRAY_A);

                    if (empty($rows)) {
                        break;
                    }

                    $buffer = '';
                    $insert_sql = 'INSERT INTO `' . $table_name . '` VALUES ';
                    foreach ($rows as $row) {
                        $values = [];
                        foreach ($row as $column_name => $value) {
                            if (is_null($value)) {
                                $values[] = 'NULL';
                            } elseif ($value === '') {
                                $values[] = "''";
                            } else {
                                switch (strtoupper($column_types[$column_name])) {
                                    case 'TINYINT':
                                    case 'SMALLINT':
                                    case 'MEDIUMINT':
                                    case 'INT':
                                    case 'INTEGER':
                                    case 'BIGINT':
                                    case 'FLOAT':
                                    case 'DOUBLE':
                                    case 'DECIMAL':
                                    case 'NUMERIC':
                                        $values[] = (string) $value;
                                        break;
                                    case 'BIT':
                                        $values[] = "b'" . decbin($value) . "'";
                                        break;
                                    case 'TINYBLOB':
                                    case 'BLOB':
                                    case 'MEDIUMBLOB':
                                    case 'LONGBLOB':
                                    case 'BINARY':
                                    case 'VARBINARY':
                                    case 'POINT':
                                    case 'LINESTRING':
                                    case 'POLYGON':
                                    case 'GEOMETRY':
                                    case 'MULTIPOINT':
                                    case 'MULTILINESTRING':
                                    case 'MULTIPOLYGON':
                                    case 'GEOMETRYCOLLECTION':
                                        $values[] = '_binary 0x' . bin2hex($value);
                                        break;
                                    default:
                                        $values[] = "'" . $wpdb->_real_escape($value) . "'";
                                        break;
                                }
                            }
                        }
                        $insert_sql .= '(' . implode(',', $values) . ')';

                        if (strlen($insert_sql) < $batch_sql_size) {
                            $insert_sql .= ",\n ";
                            continue;
                        }
                        $buffer .= $insert_sql . ";\n";
                        $insert_sql = 'INSERT INTO `' . $table_name . '` VALUES ';
                    }
                    if ($insert_sql !== 'INSERT INTO `' . $table_name . '` VALUES ') {
                        if (str_ends_with($insert_sql, ' ')) {
                            $insert_sql = substr($insert_sql, 0, -3);
                        }
                        $buffer .= $insert_sql . ";\n";
                    }

                    $export_data['exported'] += count($rows);
                    $export_data['table'][$table_name] += count($rows);
                    file_put_contents($export_data['file'], $buffer, FILE_APPEND);

                    if (time() - $start >= 1) {
                        set_transient('ry_export_data', $export_data, 600);
                        wp_send_json_success([
                            'continue' => true,
                            'progress' => round($export_data['exported'] / $export_data['total'] * 100, 2),
                            'url' => RY_Toolkit()->admin->the_action_link('tools', 'export-db'),
                        ]);
                    }
                }

                $buffer = "/*!40000 ALTER TABLE `{$table_name}` ENABLE KEYS */;\n";
                $buffer .= "UNLOCK TABLES;\n";
                file_put_contents($export_data['file'], $buffer, FILE_APPEND);
            }

            $export_data['table'][$table_name] = true;

            if (time() - $start >= 1) {
                set_transient('ry_export_data', $export_data, 600);
                wp_send_json_success([
                    'continue' => true,
                    'progress' => round($export_data['exported'] / $export_data['total'] * 100, 2),
                    'url' => RY_Toolkit()->admin->the_action_link('tools', 'export-db'),
                ]);
            }
        }

        $buffer = "/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;\n";
        $buffer .= "/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;\n";
        $buffer .= "/*!40014 SET UNIQUE_CHECKS=IFNULL(@OLD_UNIQUE_CHECKS, 1) */;\n";
        $buffer .= "/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;\n";
        $buffer .= "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n";
        $buffer .= "/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;\n\n";

        file_put_contents($export_data['file'], $buffer, FILE_APPEND);

        wp_send_json_success([
            'continue' => false,
            'url' => RY_Toolkit()->admin->the_action_link('tools', 'export-db-zip'),
        ]);
    }

    protected function export_db_zip()
    {
        if (current_user_can('export') && class_exists('ZipArchive')) {
            $export_data = get_transient('ry_export_data');
            if (is_array($export_data) && isset($export_data['file'])) {
                $tmp_zip_file = $export_data['file'] . '-zip';
                $zip = new ZipArchive();
                if ($zip->open($tmp_zip_file, ZipArchive::CREATE) === true) {
                    $zip->addFile($export_data['file'], 'export-db.sql');
                    $zip->close();

                    delete_transient('ry_export_data');

                    ob_end_clean();
                    readfile($tmp_zip_file);
                    wp_delete_file($export_data['file']);
                    wp_delete_file($tmp_zip_file);
                    exit;
                }
            }
        }

        return '';
    }

    protected function clear_transient(): string
    {
        global $wpdb;

        check_ajax_referer('ry-toolkit-action/clear-transient', '_ry_toolkit_nonce');

        foreach (self::TRANSIENT_KEYS as $transient_key) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
            $transients = $wpdb->get_col($wpdb->prepare(
                "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s",
                $wpdb->esc_like($transient_key) . '%'
            ));
            if ($transients) {
                foreach ($transients as $transient) {
                    if ($transient_key === '_transient_') {
                        delete_transient(str_replace('_transient_', '', $transient));
                    } else {
                        delete_site_transient(str_replace('_site_transient_', '', $transient));
                    }
                }
            }
        }
        RY_Toolkit()->admin->add_notice('success', __('Clear transient option successfully.', 'ry-toolkit'));

        return '';
    }

    protected function clear_complete_log(): string
    {
        global $wpdb;

        check_ajax_referer('ry-toolkit-action/clear-complete-log', '_ry_toolkit_nonce');

        $wpdb->query("DELETE FROM {$wpdb->actionscheduler_actions} WHERE `status`='complete'");
        $wpdb->query("DELETE FROM {$wpdb->actionscheduler_logs} WHERE action_id NOT IN (SELECT action_id FROM {$wpdb->actionscheduler_actions})");

        RY_Toolkit()->admin->add_notice('success', __('Clear complete log successfully.', 'ry-toolkit'));

        return '';
    }
}

RY_Toolkit_Admin_Page_Tools::init_page();
