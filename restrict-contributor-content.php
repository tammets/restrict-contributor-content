<?php
/*
Plugin Name: Restrict Contributor Content
Description: Restrict contributors from creating new content except H5P content.
Version: 1.3
Author: Priit Tammets
*/

// Remove post and comment capabilities from contributors
function modify_contributor_capabilities() {
    $role = get_role('contributor');
    if ($role) {
        // Remove the ability to edit and create posts
        $role->remove_cap('edit_posts');
        $role->remove_cap('edit_others_posts');
        $role->remove_cap('publish_posts');
        $role->remove_cap('delete_posts');

        // Remove the ability to manage comments
        $role->remove_cap('edit_comments');
        $role->remove_cap('moderate_comments');

        // Ensure contributors can still create and edit H5P content
        $role->add_cap('edit_h5p_content');
    }
}
add_action('init', 'modify_contributor_capabilities');

// Hide Posts, Pages, and Comments from the admin menu for contributors
function hide_menus_for_contributors() {
    if (current_user_can('contributor')) {
        // Remove menu items from the admin sidebar
        remove_menu_page('edit.php'); // Posts
        remove_menu_page('edit-comments.php'); // Comments
        remove_menu_page('edit.php?post_type=page'); // Pages
    }
}
add_action('admin_menu', 'hide_menus_for_contributors', 999);

// Hide "Add New" from the admin bar for contributors
function hide_admin_bar_items_for_contributors($wp_admin_bar) {
    if (current_user_can('contributor')) {
        // Remove the "New" dropdown options from the admin bar
        $wp_admin_bar->remove_node('new-post');
        $wp_admin_bar->remove_node('new-page');
        $wp_admin_bar->remove_node('comments');
    }
}
add_action('admin_bar_menu', 'hide_admin_bar_items_for_contributors', 999);

// Prevent direct access to post or page creation for contributors
function prevent_post_creation_for_contributors() {
    if (current_user_can('contributor')) {
        global $pagenow;
        if ($pagenow == 'post-new.php' || $pagenow == 'page-new.php') {
            wp_redirect(admin_url());
            exit;
        }
    }
}
add_action('admin_init', 'prevent_post_creation_for_contributors');

