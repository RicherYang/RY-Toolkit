<table id="ry-toolkit-options-wp-rocket." class="form-table ry-toolkit-options">
    <tr>
        <th scope="row"><?php esc_html_e('.htaccess', 'ry-toolkit'); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e('.htaccess', 'ry-toolkit'); ?></span></legend>
                <?php foreach ($htaccess_name as $key => $name) {
                    RY_Toolkit_Admin::the_bool_option_checkbox('wp_rocket_htaccess', sprintf(
                        /* translators: %1$s: rule key %2$s: rule description */
                        __('Disable rules "%1$s" (%2$s)', 'ry-toolkit'),
                        esc_attr($key),
                        $name
                    ), $key);
                    echo '<br />';
                } ?>
            </fieldset>
        </td>
    </tr>
    </tbody>
</table>
