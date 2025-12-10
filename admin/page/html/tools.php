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
            <?php if (current_user_can('export') && class_exists('ZipArchive')) { ?>
            <fieldset class="ry-row">
                <div class="ry-col-auto">
                    <?php RY_Toolkit()->admin->the_action_form_button('export-db', __('Export database', 'ry-toolkit'), 'button'); ?>
                </div>
                <div class="ry-col">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php esc_html_e('Single row', 'ry-toolkit'); ?></span></legend>
                        <input type="checkbox" id="single-row-1" name="single-row" value="1">
                        <?php esc_html_e('Insert one row at a time.', 'ry-toolkit'); ?>
                        <p class="description"><?php esc_html_e('Inserting data one by one would take a lot of time.', 'ry-toolkit'); ?></p>
                    </fieldset>
                </div>
                <div id="export-progress" class="ry-col-full">
                    <div class="ry-progress">
                        <div class="ry-progress-bar"></div>
                    </div>
                </div>
            </fieldset>
            <?php } ?>
            <div class="ry-row">
                <div class="ry-col-auto ry-loading">
                    <?php RY_Toolkit()->admin->the_action_form('tools', 'clear-transient', __('Clear transient option', 'ry-toolkit')); ?>
                </div>
                <div class="ry-col">
                    <p class="description">
                        <?php esc_html_e('Transient option are safe to remove. They will be automatically regenerated when require it.', 'ry-toolkit'); ?>
                        <br />
                        <?php echo esc_html(sprintf(
                            /* translators: %: Number of transient option. */
                            _n('%s transient in your database.', '%s transients in your database.', $transients, 'ry-toolkit'),
                            number_format_i18n($transients)
                        )); ?>
                    </p>
                </div>
            </div>
            <?php if ($as_counts >= 0) { ?>
            <div class="ry-row">
                <div class="ry-col-auto ry-loading">
                    <?php RY_Toolkit()->admin->the_action_form('tools', 'clear-complete-log', __('Clear Action Scheduler complete log', 'ry-toolkit')); ?>
                </div>
                <div class="ry-col">
                    <p class="description">
                        <?php echo esc_html(sprintf(
                            /* translators: %: Number of complete log. */
                            _n('%s complete log in your database.', '%s complete logs in your database.', $as_counts, 'ry-toolkit'),
                            number_format_i18n($as_counts)
                        )); ?>
                    </p>
                </div>
            </div>
            <?php } ?>
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
