<table class="widefat striped ry-tooltable">
    <tr>
        <th>
            <strong><?php esc_html_e('Database', 'ry-toolkit'); ?></strong>

        </th>
        <td>
            <div class="ry-row">
                <div class="ry-col-auto ry-loading">
                    <?php RY_Toolkit()->admin->the_action_form('tools', 'analyze-tables', __('Analyze tables', 'ry-toolkit')); ?>
                </div>
                <div class="ry-col-auto ry-loading">
                    <?php RY_Toolkit()->admin->the_action_form('tools', 'optimize-tables', __('Optimize tables', 'ry-toolkit')); ?>
                </div>
            </div>
            <div class="ry-row">
                <div class="ry-col-auto ry-loading">
                    <?php RY_Toolkit()->admin->the_action_form('tools', 'clear-transient', __('Clear transient option', 'ry-toolkit')); ?>
                </div>
                <div class="ry-col">
                    <p class="description">
                        <?php esc_html_e('Transient option are safe to remove. They will be automatically regenerated when require it.', 'ry-toolkit'); ?>
                        <br />
                        <?php echo esc_html(sprintf( /* translators: %: Number of transient option. */
                            _n('%s transient in your database.', '%s transients in your database.', $transients, 'ry-toolkit'), number_format_i18n($transients))); ?>
                    </p>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th>
            <strong><?php esc_html_e('PHP OPcache', 'ry-toolkit'); ?></strong>

        </th>
        <td>
            <div class="ry-row">
                <div class="ry-col-auto ry-loading">
                    <?php RY_Toolkit()->admin->the_action_form('opcache', 'flush-opcache', __('Flush PHP OPcache', 'ry-toolkit')); ?>
                </div>
                <div class="ry-col">
                    <p class="description">
                        <?php esc_html_e('Set all cached files under the WordPress root directory to be invalid.', 'ry-toolkit'); ?>
                    </p>
                </div>
            </div>
            <div class="ry-row">
                <div class="ry-col-auto ry-loading">
                    <?php RY_Toolkit()->admin->the_action_form('opcache', 'restart-opcache', __('Restart PHP OPcache', 'ry-toolkit')); ?>
                </div>
            </div>
        </td>
    </tr>

    <?php do_action('ry-toolkit/tools_table'); ?>

</table>
