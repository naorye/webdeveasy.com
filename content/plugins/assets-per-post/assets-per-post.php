<?php
/*
Plugin Name: Assets Per Post
Plugin URI: http://webdeveasy.com/
Description: Let's you add multiple css and javascript files on a per-post basis as well as inline style and inline script.
Version: 1.0
Author: Naor Ye
Author URI: http://webdeveasy.com/

== Release Notes ==
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
Online: http://www.gnu.org/licenses/gpl.txt
*/

// Custom inline css per post
add_action('admin_menu', 'custom_inline_css_hooks');
add_action('save_post', 'save_custom_inline_css');
add_action('wp_head', 'insert_custom_inline_css');

function custom_inline_css_hooks() {
    add_meta_box('custom_inline_css', 'Custom inline CSS', 'custom_inline_css_input', 'post', 'normal', 'high');
    add_meta_box('custom_inline_css', 'Custom inline CSS', 'custom_inline_css_input', 'page', 'normal', 'high');
}

function custom_inline_css_input() {
    global $post;
    echo '<input type="hidden" name="custom_inline_css_noncename" id="custom_inline_css_noncename" value="'.wp_create_nonce('custom-inline-css').'" />';
    echo '<textarea name="custom_inline_css" id="custom_inline_css" rows="5" cols="30" style="width:100%;">'.get_post_meta($post->ID,'_custom_inline_css',true).'</textarea>';
}

function save_custom_inline_css($post_id) {
    if (!wp_verify_nonce($_POST['custom_inline_css_noncename'], 'custom-inline-css')) return $post_id;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    $custom_css = $_POST['custom_inline_css'];
    update_post_meta($post_id, '_custom_inline_css', $custom_css);
}

function insert_custom_inline_css() {
    if (is_page() || is_single()) {
        echo '<style type="text/css">'.get_post_meta(get_the_ID(), '_custom_inline_css', true).'</style>';
    }
}

// Custom css files per post
add_action('admin_menu', 'custom_css_files_hooks');
add_action('save_post', 'save_custom_css_files');
add_action('wp_head', 'insert_custom_css_files');
function custom_css_files_hooks() {
    add_meta_box('custom_css_files', 'Custom CSS files (separate multiple file names with a <code>, </code>)', 'custom_css_files_input', 'post', 'normal', 'high');
    add_meta_box('custom_css_files', 'Custom CSS files (separate multiple file names with a <code>, </code>)', 'custom_css_files_input', 'page', 'normal', 'high');
}

function custom_css_files_input() {
    global $post;
    echo '<input type="hidden" name="custom_css_files_noncename" id="custom_css_files_noncename" value="'.wp_create_nonce('custom-css-files').'" />';
    echo '<input type="text" name="custom_css_files" id="custom_css_files" style="width:100%;" value="'.get_post_meta($post->ID,'_custom_css_files',true).'" />';
}

function save_custom_css_files($post_id) {
    if (!wp_verify_nonce($_POST['custom_css_files_noncename'], 'custom-css-files')) return $post_id;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    $custom_css = $_POST['custom_css_files'];
    update_post_meta($post_id, '_custom_css_files', $custom_css);
}

function insert_custom_css_files() {
    if (is_page() || is_single()) {
        $custom_css = get_post_meta(get_the_ID(), '_custom_css_files', true);
        $custom_css = explode(',', $custom_css);

        foreach ($custom_css as $cc) {
            $cc = trim($cc);
            if (!$cc) continue;
            if (strpos($cc, 'http://') === 0 || strpos($cc, 'https://') === 0) {
                wp_enqueue_style(basename($cc), $cc);
            } else if (strpos($cc, '/') === 0) {
                wp_enqueue_style(basename($cc), get_bloginfo('url') . $cc);
            } else {
                wp_enqueue_style(basename($cc), get_permalink() . $cc);
            }
        }
    }
}

//Custom script per post
add_action('admin_menu', 'custom_inline_script_hooks');
add_action('save_post', 'save_custom_inline_script');
add_action('wp_head','insert_custom_inline_script');

function custom_inline_script_hooks() {
    add_meta_box('custom_inline_script', 'Custom inline script', 'custom_inline_script_input', 'post', 'normal', 'high');
    add_meta_box('custom_inline_script', 'Custom inline script', 'custom_inline_script_input', 'page', 'normal', 'high');
}

function custom_inline_script_input() {
    global $post;
    echo '<input type="hidden" name="custom_inline_script_noncename" id="custom_inline_script_noncename" value="'.wp_create_nonce('custom-inline-script').'" />';
    echo '<textarea name="custom_inline_script" id="custom_inline_script" rows="5" cols="30" style="width:100%;">'.get_post_meta($post->ID, '_custom_inline_script', true).'</textarea>';
}

function save_custom_inline_script($post_id) {
    if (!wp_verify_nonce($_POST['custom_inline_script_noncename'], 'custom-inline-script')) return $post_id;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    $custom_script = $_POST['custom_inline_script'];
    update_post_meta($post_id, '_custom_inline_script', $custom_script);
}

function insert_custom_inline_script() {
    if (is_page() || is_single()) {
        echo '<script type="text/javascript">'.get_post_meta(get_the_ID(), '_custom_inline_script', true).'</script>';
    }
}

// Custom script files per post
add_action('admin_menu', 'custom_script_files_hooks');
add_action('save_post', 'save_custom_script_files');
add_action('wp_head', 'insert_custom_script_files');
function custom_script_files_hooks() {
    add_meta_box('custom_script_files', 'Custom script files (separate multiple file names with a <code>, </code>)', 'custom_script_files_input', 'post', 'normal', 'high');
    add_meta_box('custom_script_files', 'Custom script files (separate multiple file names with a <code>, </code>)', 'custom_script_files_input', 'page', 'normal', 'high');
}

function custom_script_files_input() {
    global $post;
    echo '<input type="hidden" name="custom_script_files_noncename" id="custom_script_files_noncename" value="'.wp_create_nonce('custom-script-files').'" />';
    echo '<input type="text" name="custom_script_files" id="custom_script_files" style="width:100%;" value="'.get_post_meta($post->ID,'_custom_script_files',true).'" />';
}

function save_custom_script_files($post_id) {
    if (!wp_verify_nonce($_POST['custom_script_files_noncename'], 'custom-script-files')) return $post_id;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    $custom_script = $_POST['custom_script_files'];
    update_post_meta($post_id, '_custom_script_files', $custom_script);
}

function insert_custom_script_files() {
    if (is_page() || is_single()) {
        $custom_script = get_post_meta(get_the_ID(), '_custom_script_files', true);
        $custom_script = explode(',', $custom_script);

        foreach ($custom_script as $cc) {
            $cc = trim($cc);
            if (!$cc) continue;
            if (strpos($cc, 'http://') === 0 || strpos($cc, 'https://') === 0) {
                wp_enqueue_script(basename($cc), $cc);
            } else if (strpos($cc, '/') === 0) {
                wp_enqueue_script(basename($cc), get_bloginfo('url') . $cc);
            } else {
                wp_enqueue_script(basename($cc), get_permalink() . $cc);
            }
        }
    }
}

?>