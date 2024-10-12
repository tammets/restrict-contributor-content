<?php
/*
Plugin Name: Restrict Contributor Content
Description: Restrict contributors from creating new content except H5P content.
Version: 1.0
Author: Priit Tammets
*/

// Restrict contributor capabilities
function restrict_contributor_content() {
    if (!current_user_can('edit_h5p_content')) {
        // Get the current user's role
        $user = wp_get_current_user();
        if (in_array('contributor', (array) $user->roles)) {
            // Remove capabilities to create new posts/pages
            remove_menu_page('edit.php'); // Posts
            remove_menu_page('edit.php?post_type=page'); // Pages
        }
    }
}
add_action('admin_menu', 'restrict_contributor_content');

// Allow contributors to edit and create H5P content
function allow_h5p_for_contributors() {
    $role = get_role('contributor');
    if ($role) {
        // Add the necessary capability to create and edit H5P content
        $role->add_cap('edit_h5p_content');
    }
}
add_action('init', 'allow_h5p_for_contributors');