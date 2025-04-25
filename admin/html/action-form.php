<form method="post" action="<?php echo esc_url($post_url); ?>">
    <?php foreach ($hidden_value as $name => $value) {
        echo '<input type="hidden" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '" />';
    } ?>
    <?php wp_nonce_field('ry-toolkit-action'); ?>
    <?php wp_nonce_field('ry-toolkit-action/' . $action, '_ry_toolkit_nonce', false); ?>
    <?php submit_button($submit_text, 'secondary', 'submit', false); ?>
</form>
