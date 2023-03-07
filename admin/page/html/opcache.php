<h3><?php esc_html_e('OPcache status', 'ry-tools'); ?></h3>

<div class="ry-row ry-row-opcache">
    <div class="ry-col">
        <table class="widefat striped">
            <thead>
                <tr>
                    <th colspan="2"><?php esc_html_e('OPcache statistics', 'ry-tools'); ?></th>
                </tr>
            </thead>
            <tr>
                <td><?php esc_html_e('Start time', 'ry-tools'); ?></td>
                <td>
                    <?php echo wp_date('Y-m-d H:i:s', $opcache_status['opcache_statistics']['start_time']); ?>
                </td>
            </tr>
            <?php if ($opcache_status['opcache_statistics']['last_restart_time'] > 0) { ?>
            <tr>
                <td><?php esc_html_e('Last restart time', 'ry-tools'); ?></td>
                <td>
                    <?php echo wp_date('Y-m-d H:i:s', $opcache_status['opcache_statistics']['last_restart_time']); ?>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td><?php esc_html_e('Number of cached files', 'ry-tools'); ?></td>
                <td>
                    <?php echo number_format_i18n($opcache_status['opcache_statistics']['num_cached_scripts']); ?>
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('Number of hits', 'ry-tools'); ?></td>
                <td>
                    <?php echo number_format_i18n($opcache_status['opcache_statistics']['hits']); ?>
                    ( <?php echo round($opcache_status['opcache_statistics']['hits'] / ($opcache_total['hit'] ?: 1) * 100, 2); ?>% )
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('Number of misses', 'ry-tools'); ?></td>
                <td>
                    <?php echo number_format_i18n($opcache_status['opcache_statistics']['misses']); ?>
                    ( <?php echo round($opcache_status['opcache_statistics']['misses'] / ($opcache_total['hit'] ?: 1) * 100, 2); ?>% )
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('blacklist hits', 'ry-tools'); ?></td>
                <td>
                    <?php echo number_format_i18n($opcache_status['opcache_statistics']['blacklist_misses']); ?>
                    ( <?php echo round($opcache_status['opcache_statistics']['blacklist_misses'] / ($opcache_total['hit'] ?: 1) * 100, 2); ?>% )
                </td>
            </tr>
        </table>
    </div>

    <div class="ry-col">
        <table class="widefat striped">
            <thead>
                <tr>
                    <th colspan="2"><?php esc_html_e('Memory usage', 'ry-tools'); ?></th>
                </tr>
            </thead>
            <tr>
                <td><?php esc_html_e('Total memory', 'ry-tools'); ?></td>
                <td>
                    <?php echo size_format($opcache_total['memory'], 0); ?>
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('Used memory', 'ry-tools'); ?></td>
                <td>
                    <?php echo size_format($opcache_status['memory_usage']['used_memory'], 2); ?>
                    ( <?php echo round($opcache_status['memory_usage']['used_memory'] / ($opcache_total['memory'] ?: 1) * 100, 2); ?>% )
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('Free memory', 'ry-tools'); ?></td>
                <td>
                    <?php echo size_format($opcache_status['memory_usage']['free_memory'], 2); ?>
                    ( <?php echo round($opcache_status['memory_usage']['free_memory'] / ($opcache_total['memory'] ?: 1) * 100, 2); ?>% )
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('Wasted memory', 'ry-tools'); ?></td>
                <td>
                    <?php echo size_format($opcache_status['memory_usage']['wasted_memory'], 2); ?>
                    ( <?php echo round($opcache_status['memory_usage']['wasted_memory'] / ($opcache_total['memory'] ?: 1) * 100, 2); ?>% )
                </td>
            </tr>
        </table>
    </div>

    <div class="ry-col">
        <table class="widefat striped">
            <thead>
                <tr>
                    <th colspan="2"><?php esc_html_e('Interned strings usage', 'ry-tools'); ?></th>
                </tr>
            </thead>
            <tr>
                <td><?php esc_html_e('Buffer size', 'ry-tools'); ?></td>
                <td>
                    <?php echo size_format($opcache_status['interned_strings_usage']['buffer_size']); ?>
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('Used buffer', 'ry-tools'); ?></td>
                <td>
                    <?php echo size_format($opcache_status['interned_strings_usage']['used_memory'], 2); ?>
                    ( <?php echo round($opcache_status['interned_strings_usage']['used_memory'] / ($opcache_total['buffer'] ?: 1) * 100, 2); ?>% )
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('Free buffer', 'ry-tools'); ?></td>
                <td>
                    <?php echo size_format($opcache_status['interned_strings_usage']['free_memory'], 2); ?>
                    ( <?php echo round($opcache_status['interned_strings_usage']['free_memory'] / ($opcache_total['buffer'] ?: 1) * 100, 2); ?>% )
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('Number of strings', 'ry-tools'); ?></td>
                <td>
                    <?php echo number_format_i18n($opcache_status['interned_strings_usage']['number_of_strings']); ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="ry-col">
        <table class="widefat striped">
            <thead>
                <tr>
                    <th colspan="2"><?php esc_html_e('Restart info', 'ry-tools'); ?></th>
                </tr>
            </thead>
            <tr>
                <td><?php esc_html_e('Out of memory', 'ry-tools'); ?></td>
                <td>
                    <?php echo number_format_i18n($opcache_status['opcache_statistics']['oom_restarts']); ?>
                    ( <?php echo round($opcache_status['opcache_statistics']['oom_restarts'] / ($opcache_total['restart'] ?: 1) * 100, 2); ?>% )
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('Out of file', 'ry-tools'); ?></td>
                <td>
                    <?php echo number_format_i18n($opcache_status['opcache_statistics']['hash_restarts']); ?>
                    ( <?php echo round($opcache_status['opcache_statistics']['hash_restarts'] / ($opcache_total['restart'] ?: 1) * 100, 2); ?>% )
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('manual', 'ry-tools'); ?></td>
                <td>
                    <?php echo number_format_i18n($opcache_status['opcache_statistics']['manual_restarts']); ?>
                    ( <?php echo round($opcache_status['opcache_statistics']['manual_restarts'] / ($opcache_total['restart'] ?: 1) * 100, 2); ?>% )
                </td>
            </tr>
        </table>
    </div>
</div>

<h3><?php esc_html_e('OPcache tool', 'ry-tools'); ?></h3>

<table class="widefat striped ry-tooltable">
    <tr>
        <th>
            <strong><?php esc_html_e('PHP OPcache', 'ry-tools'); ?></strong>

        </th>
        <td>
            <div class="ry-row">
                <div class="ry-col-auto">
                    <?php RY()->admin->the_action_form('opcache', 'flush-opcache', __('Flush PHP OPcache', 'ry-tools')); ?>
                </div>
                <div class="ry-col">
                    <p class="description">
                        <?php esc_html_e('Set all cached files under the WordPress root directory to be invalid.', 'ry-tools'); ?>
                    </p>
                </div>
            </div>
            <div class="ry-row">
                <div class="ry-col-auto">
                    <?php RY()->admin->the_action_form('opcache', 'restart-opcache', __('Restart PHP OPcache', 'ry-tools')); ?>
                </div>
            </div>
        </td>
    </tr>
</table>
