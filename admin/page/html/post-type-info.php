<?php

function ry_toolkit_get_cap_key_name($key)
{
    static $key_name = [];
    if (empty($key_name)) {
        $key_name = [
            'read' => _x('Read', 'cap key', 'ry-toolkit'),
            'read_private_posts' => _x('Read private posts', 'cap key', 'ry-toolkit'),
            'edit_post' => _x('Edit post', 'cap key', 'ry-toolkit'),
            'read_post' => _x('Read post', 'cap key', 'ry-toolkit'),
            'delete_post' => _x('Delete post', 'cap key', 'ry-toolkit'),
            'create_posts' => _x('Create posts ', 'cap key', 'ry-toolkit'),
            'publish_posts' => _x('Publish posts', 'cap key', 'ry-toolkit'),
            'edit_posts' => _x('Edit posts', 'cap key', 'ry-toolkit'),
            'edit_others_posts' => _x('Edit others posts', 'cap key', 'ry-toolkit'),
            'edit_private_posts' => _x('Edit private posts', 'cap key', 'ry-toolkit'),
            'edit_published_posts' => _x('Edit published posts', 'cap key', 'ry-toolkit'),
            'delete_posts' => _x('Delete posts', 'cap key', 'ry-toolkit'),
            'delete_others_posts' => _x('Delete others posts', 'cap key', 'ry-toolkit'),
            'delete_private_posts' => _x('Delete private posts', 'cap key', 'ry-toolkit'),
            'delete_published_posts' => _x('Delete published posts', 'cap key', 'ry-toolkit'),
        ];
    }

    return $key_name[$key] ?? $key;
}

function ry_toolkit_get_support_key_name($key)
{
    static $key_name = [];
    if (empty($key_name)) {
        $key_name = [
            'title' => _x('Title', 'support key', 'ry-toolkit'),
            'editor' => _x('Editor', 'support key', 'ry-toolkit'),
            'comments' => _x('Comments', 'support key', 'ry-toolkit'),
            'revisions' => _x('Revisions', 'support key', 'ry-toolkit'),
            'trackbacks' => _x('Trackbacks', 'support key', 'ry-toolkit'),
            'author' => _x('Author', 'support key', 'ry-toolkit'),
            'excerpt' => _x('Excerpt', 'support key', 'ry-toolkit'),
            'page-attributes' => _x('Page attributes', 'support key', 'ry-toolkit'),
            'thumbnail' => _x('Thumbnail', 'support key', 'ry-toolkit'),
            'custom-fields' => _x('Custom fields', 'support key', 'ry-toolkit'),
            'post-formats' => _x('Post formats', 'support key', 'ry-toolkit'),
            'autosave' => _x('Autosave', 'support key', 'ry-toolkit'),
        ];
    }

    return $key_name[$key] ?? $key;
}

function ry_toolkit_get_label_key_name($key)
{
    static $key_name = [];
    if (empty($key_name)) {
        $key_name = [
            'name' => _x('Name', 'label key', 'ry-toolkit'),
            'singular_name' => _x('Singular name', 'label key', 'ry-toolkit'),
            'add_new' => _x('Add new', 'label key', 'ry-toolkit'),
            'add_new_item' => _x('Add new item', 'label key', 'ry-toolkit'),
            'edit_item' => _x('Edit item', 'label key', 'ry-toolkit'),
            'new_item' => _x('New item', 'label key', 'ry-toolkit'),
            'view_item' => _x('View item', 'label key', 'ry-toolkit'),
            'view_items' => _x('View items', 'label key', 'ry-toolkit'),
            'search_items' => _x('Search items', 'label key', 'ry-toolkit'),
            'not_found' => _x('Not found', 'label key', 'ry-toolkit'),
            'not_found_in_trash' => _x('Not found in trash', 'label key', 'ry-toolkit'),
            'parent_item_colon' => _x('Parent item colon', 'label key', 'ry-toolkit'),
            'all_items' => _x('All items', 'label key', 'ry-toolkit'),
            'archives' => _x('Archives', 'label key', 'ry-toolkit'),
            'attributes' => _x('Attributes', 'label key', 'ry-toolkit'),
            'insert_into_item' => _x('Insert into item', 'label key', 'ry-toolkit'),
            'uploaded_to_this_item' => _x('Uploaded to this item', 'label key', 'ry-toolkit'),
            'featured_image' => _x('Featured image', 'label key', 'ry-toolkit'),
            'set_featured_image' => _x('Set featured image', 'label key', 'ry-toolkit'),
            'remove_featured_image' => _x('Remove featured image', 'label key', 'ry-toolkit'),
            'use_featured_image' => _x('Use featured image', 'label key', 'ry-toolkit'),
            'menu_name' => _x('Menu name', 'label key', 'ry-toolkit'),
            'filter_items_list' => _x('Filter items list', 'label key', 'ry-toolkit'),
            'filter_by_date' => _x('Filter by date', 'label key', 'ry-toolkit'),
            'items_list_navigation' => _x('Items list navigation', 'label key', 'ry-toolkit'),
            'items_list' => _x('Items list', 'label key', 'ry-toolkit'),
            'item_published' => _x('Item published', 'label key', 'ry-toolkit'),
            'item_published_privately' => _x('Item published privately', 'label key', 'ry-toolkit'),
            'item_reverted_to_draft' => _x('Item reverted to draft', 'label key', 'ry-toolkit'),
            'item_trashed' => _x('Item trashed', 'label key', 'ry-toolkit'),
            'item_scheduled' => _x('Item scheduled', 'label key', 'ry-toolkit'),
            'item_updated' => _x('Item updated', 'label key', 'ry-toolkit'),
            'item_link' => _x('Item link', 'label key', 'ry-toolkit'),
            'item_link_description' => _x('Item link description', 'label key', 'ry-toolkit'),
            'name_admin_bar' => _x('Name admin bar', 'label key', 'ry-toolkit'),
        ];
    }

    return $key_name[$key] ?? $key;
}
?>

<div class="ry-row ry-row-posttypeinfo">
    <div class="ry-col">
        <table class="info-table">
            <tr>
                <th scope="row"><?php esc_html_e('Description', 'ry-toolkit'); ?></th>
                <td>
                    <?php echo esc_html($post_type->description); ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Hierarchical', 'ry-toolkit'); ?></th>
                <td>
                    <?php $post_type->hierarchical ? esc_html_e('Yes', 'ry-toolkit') : esc_html_e('No', 'ry-toolkit'); ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Archive', 'ry-toolkit'); ?></th>
                <td>
                    <?php $post_type->has_archive ? esc_html_e('Yes', 'ry-toolkit') : esc_html_e('No', 'ry-toolkit'); ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Supports', 'ry-toolkit'); ?></th>
                <td>
                    <?php foreach ($post_type->supports as $key => $is_support) { ?>
                    <?php echo esc_html(ry_toolkit_get_support_key_name($key)); ?>,
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Export', 'ry-toolkit'); ?></th>
                <td>
                    <?php $post_type->can_export ? esc_html_e('Yes', 'ry-toolkit') : esc_html_e('No', 'ry-toolkit'); ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Delete with user', 'ry-toolkit'); ?></th>
                <td>
                    <?php $post_type->delete_with_user ? esc_html_e('Yes', 'ry-toolkit') : esc_html_e('No', 'ry-toolkit'); ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Capabilities', 'ry-toolkit'); ?></th>
                <td>
                    <table class="widefat striped">
                        <?php foreach ($post_type->cap as $key => $capability) { ?>
                        <tr>
                            <th><?php echo esc_html(ry_toolkit_get_cap_key_name($key)); ?></th>
                            <td>
                                <?php echo esc_html($capability); ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Visibility', 'ry-toolkit'); ?></th>
                <td>
                    <table class="widefat striped">
                        <tr>
                            <th scope="row"><?php esc_html_e('Public', 'ry-toolkit'); ?></th>
                            <td><?php $post_type->public ? esc_html_e('Yes', 'ry-toolkit') : esc_html_e('No', 'ry-toolkit'); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Exclude from search', 'ry-toolkit'); ?></th>
                            <td><?php $post_type->exclude_from_search ? esc_html_e('Yes', 'ry-toolkit') : esc_html_e('No', 'ry-toolkit'); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Show in UI', 'ry-toolkit'); ?></th>
                            <td><?php $post_type->show_ui ? esc_html_e('Yes', 'ry-toolkit') : esc_html_e('No', 'ry-toolkit'); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Show in menu', 'ry-toolkit'); ?></th>
                            <td>
                                <?php $post_type->show_in_menu ? esc_html_e('Yes', 'ry-toolkit') : esc_html_e('No', 'ry-toolkit'); ?>
                                <?php if ($post_type->show_in_menu) { ?>
                                <p><?php esc_html_e('Menu position: ', 'ry-toolkit'); ?><?php echo esc_html($post_type->menu_position); ?></p>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Show in admin bar', 'ry-toolkit'); ?></th>
                            <td><?php $post_type->show_in_admin_bar ? esc_html_e('Yes', 'ry-toolkit') : esc_html_e('No', 'ry-toolkit'); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Show in navigation menus', 'ry-toolkit'); ?></th>
                            <td><?php $post_type->show_in_nav_menus ? esc_html_e('Yes', 'ry-toolkit') : esc_html_e('No', 'ry-toolkit'); ?></td>
                        </tr>
                    </table>
            </tr>
        </table>
    </div>
    <div class="ry-col">
        <h2><?php esc_html_e('Labels', 'ry-toolkit'); ?></h2>
        <table class="widefat striped">
            <?php foreach ($post_type->labels as $key => $label) { ?>
            <tr>
                <td><?php echo esc_html(ry_toolkit_get_label_key_name($key)); ?></td>
                <td>
                    <?php echo esc_html($label); ?>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>
