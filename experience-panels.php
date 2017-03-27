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
    
    //number of tiles to put in the panel
    //number of rows in the panel
    //number of columns in mobile (xs)
    //number of columns on desktop (sm and up)

    $numTilesOptions = "";
    $numRowsOptions = "";
    $numColumnsMobile = "";
    $numColumnsDesktop = "";

    for($i=1; $i<=15; $i++){
        $numTilesOptions .= "<option value='$i'>$i</option>";
        if($i<=5) $numRowsOptions .= "<option value='$i'>$i</option>";
        if($i<=12){ 
            $numColumnsMobile .= "<option value='$i'>$i</option>";
            $numColumnsDesktop .= "<option value='$i'>$i</option>";
        }
    }

    $plugin_options = array(
        'numTiles' => array("label" => "Number of tiles in panel", "options" => $numTilesOptions), 
        'numRows' => array("label" => "Number of rows in the panel", "options" => $numRowsOptions),
        'numColumnsMobile' => array("label" => "Number of columns in mobile view", "options" => $numColumnsMobile), 
        'numColumnsDesktop' => array("label" => "Number of columns in desktop view", "options" => $numColumnsDesktop),
    );
?>
    <h2>Experience Panel Plugin Settings</h2>
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
