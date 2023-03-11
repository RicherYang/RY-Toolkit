<form method="post" action="<?php echo esc_url($post_url); ?>">
    <?php
    foreach ($hidden_value as $name => $value) {
        printf(
            '<input type="hidden" name="%s" value="%s" />',
            esc_attr($name),
            esc_attr($value)
        );
    }
?>
    <?php wp_nonce_field('ry-toolkit-action'); ?>
    <?php wp_nonce_field($action, '_ry_toolkit_action_nonce', false); ?>
    <?php submit_button($submit_text, 'secondary', 'submit', false); ?>
</form>
