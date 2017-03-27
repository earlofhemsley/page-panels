<?php
/**
 * Plugin Name: Experience Panel
 * Plugin URI: http://landonhemsley.com
 * Description: This plugin allows you to identify site pages to use as tiles in a widget mosaic. Best used as part of a website dedicated to resume or recruitment purposes.
 * Version: 1.0.0
 * Author: Landon Hemsley
 * Author URI: http://landonhemsley.com
 * License: GPL2
 */

add_action('admin_menu','experience_panel_registration');

function experience_panel_registration(){
    add_submenu_page('plugins.php','Experience Panel Plugin Settings','Experience Panel','activate_plugins','experience-panel-slug','experience_panel_html');
}

function experience_panel_html(){
    echo "Hello World";
}


class ExperiencePanel extends WP_Widget {}



?>
