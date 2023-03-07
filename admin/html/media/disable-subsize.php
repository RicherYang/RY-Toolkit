<input name="<?php echo esc_attr(RY::get_option_name('disable_subsize')); ?>[]" type="checkbox" id="ry_disable_subsize_<?php echo esc_attr($size_name); ?>" value="<?php echo esc_attr($size_name); ?>" <?php checked(in_array($size_name, $disable_subsize)); ?>/>
<label for="ry_disable_subsize_<?php echo esc_attr($size_name); ?>"><?php echo esc_html($show_size_name); ?> ( <?php echo esc_html($size_data['width']); ?> * <?php echo esc_html($size_data['height']); ?> )</label>
<br />
