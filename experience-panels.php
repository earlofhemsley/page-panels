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

    $plugin_options = array(
        'numTiles' => array("label" => "Number of tiles in panel", "options" => ''), 
        'numRows' => array("label" => "Number of rows in the panel", "options" => ''),
        'numColumnsMobile' => array("label" => "Number of columns in mobile view", "options" => ''), 
        'numColumnsDesktop' => array("label" => "Number of columns in desktop view", "options" => ''),
    );
    
    $message = array('class' => 'updated notice is-dismissible', 'text' => 'Your settings were successfully saved.');

    foreach($plugin_options as $key => $option){
        if(isset($_POST[$key]))
            update_option('epanel-'.$key, $_POST[$key]);
        for($i=1; $i<=15; $i++){
            if($key == "numRows" && $i > 5) break;
            if(($key == "numColumnsDesktop" || $key == "numColumnsMobile") && $i > 12) break;
            $option['options'] .= "<option value='$i'";
            if(get_option('epanel-'.$key) == $i) $option['options'] .= " selected";
            $option['options'] .= ">$i</option>";
        }
        $plugin_options[$key] = $option;
    }

    if(!empty($_POST))
        echo sprintf("<div class='%s'><p>%s</p></div>", $message['class'], $message['text']);
?>
    <h1>Experience Panel Plugin Settings</h1>
    <p>Please input the required options for the Experience Panel.</p>
    <form method="POST">
        <div>
            <dl>
<?php
                foreach($plugin_options as $key => $option){
                    echo "<dt>".$option["label"]."</dt>";
                    echo "<dd><select name='$key'>".$option["options"]."</select></dd>";
                } 
?>
            </dl>
        </div>
        <button type="submit">Save Settings</button>
    </form>

<?php
    
    //TODO: Import new stylesheets as part of the plugin

}


class ExperiencePanel extends WP_Widget {}



?>
