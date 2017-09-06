<?php
/**
 * Plugin Name: Page Panel
 * Plugin URI: http://landonhemsley.com
 * Description: This plugin allows you to identify site pages to use as tiles in a widget mosaic. Best used as part of a website dedicated to resume or recruitment purposes.
 * Version: 1.0.0
 * Author: Landon Hemsley
 * Author URI: http://landonhemsley.com
 * License: GPL2
 */

function theme_setup_assertions(){
    if(!current_theme_supports('post_thumbnails')) add_theme_support('post-thumbnails');
    if(function_exists( 'add_image_size' ))
        add_image_size('ppanel-tile-size', 500, 500);
}
add_action('after_setup_theme','theme_setup_assertions');

function assert_ppanel_styles(){
    wp_enqueue_style('ppanel-styles', plugin_dir_url(__FILE__). 'css/ppanel-styles.css');
    wp_enqueue_script('ppanel-script', plugin_dir_url(__FILE__).'js/ppanel-script.js', array('jquery'), false, true);
}
add_action('wp_enqueue_scripts', 'assert_ppanel_styles', 1000);

function page_panel_registration(){
    add_submenu_page('plugins.php','Page Panel Plugin Settings','Page Panel Settings','activate_plugins','page-panel-slug','page_panel_html');
}
add_action('admin_menu','page_panel_registration');

function page_panel_html(){

    $plugin_options = array(
        'numTiles' => array("label" => "Number of tiles in panel", "options" => ''), 
        'numColumns' => array("label" => "Number of columns in desktop view", "options" => ''), 
    );
    
    $message = array('class' => 'updated notice is-dismissible', 'text' => 'Your settings were successfully saved.');

    $acceptable_cols = array(1,2,3,4,5,6);

    foreach($plugin_options as $key => $option){
        if(isset($_POST[$key]))
            update_option('ppanel-'.$key, $_POST[$key]);
        for($i=1; $i<=15; $i++){
            if($key == "numRows" && $i > 5) break;
            
            if($key == "numColumns" && !in_array($i, $acceptable_cols, TRUE)) continue;

            $option['options'] .= "<option value='$i'";
            if(get_option('ppanel-'.$key) == $i) $option['options'] .= " selected";
            $option['options'] .= ">$i</option>";
        }
        $plugin_options[$key] = $option;
    }

    if(!empty($_POST))
        echo sprintf("<div class='%s'><p>%s</p></div>", $message['class'], $message['text']);
?>
    <h1>Page Panel Plugin Settings</h1>
    <p>Please input the required options for the Page Panel.</p>
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


class PagePanel extends WP_Widget {

    public function __construct(){
        parent::__construct('page-panel', 'Page Panel', array(
            'description' => 'A panel that assembles the content of multiple pages into a series of simple tiles in one place',
            'classname' => ''
        ));
    }

    public function form($instance){
        echo '<p><strong>NOTE: </strong>You should go to the <a href="'.get_admin_url(null, '/plugins.php?page=page-panel-slug').'">page panel settings page</a> to set the number of tiles, rows and columns you would like to be included in the panel.</p><p><strong>ANOTHER NOTE:</strong> Only pages with thumbnails are displayed. If you don\'t see your page, make sure it has a thumbnail.</p>';
        
        $pages = get_posts(array(
            'post_type'     => 'page',
            'meta_key'      => '_thumbnail_id',
        ));

        $numTiles = get_option('ppanel-numTiles', 15);
        
                    
        for($i=1; $i<=$numTiles; $i++){
            //output a dropdown containing all the pages from the query as options
            //the option value should be the page id
            //the option text should be the page title
?>
            <div>
                <label style="padding-right:10px;" for="<?php echo $this->get_field_id("ppanel-tile-$i"); ?>">Tile No. <?php echo $i; ?></label>
                <select 
                    id="<?php echo $this->get_field_id("ppanel-tile-$i"); ?>" 
                    name="<?php echo $this->get_field_name("ppanel-tile-$i"); ?>"
                >
                    <option value="0">Select a page</option>
<?php
                foreach($pages as $page){
                    echo sprintf("<option value='%d' %s>%s</option>", 
                        $page->ID,
                        (isset($instance["ppanel-tile-$i"]) && $instance["ppanel-tile-$i"] == $page->ID) ? "selected" : "",
                        $page->post_title
                    );
                }
?>            
                </select>
            </div>
<?php
        }


    }

    public function update($new_instance){
        $toReturnInstance = array();
        for($i=1; $i<=get_option('ppanel-numTiles', 15); $i++){
            $toReturnInstance["ppanel-tile-$i"] = isset($new_instance["ppanel-tile-$i"]) ? $new_instance["ppanel-tile-$i"] : 0;
        }
        return $toReturnInstance;
    
    }

    public function widget($args, $instance){
        //get options vars
        //prepare query based on page values
        //loop through pages and output tiles

        $numTiles = get_option('ppanel-numTiles', 15);
        $regex = "/^ppanel-tile-";
        $regex .= ($numTiles >= 10) ? "([1-9]|1[0-".$numTiles % 10 . "])$/" : "[1-$numTiles]$/";
        $pageIds = array_intersect_key($instance, array_flip( preg_grep( $regex, array_keys($instance) ) ));
        $pageIds = array_filter($pageIds, function($v){ return $v != 0; });

        $col = 1;
        $counter = 0;
        $numColumns = get_option("ppanel-numColumns",4);

        echo sprintf('<div class="ppanel-container"><ul class="%s">', "ppanel-$numColumns-col");

        foreach($pageIds as $pageId){
            $tile = sprintf("<!-- %d --><li><div class='ppanel-tile-image'><span class='helper'></span><img class='modalable' data-show='#ppanel-modal-%d' src='%s' /></div></li>",
                $pageId,
                $pageId,
                get_the_post_thumbnail_url($pageId, 'ppanel-tile-size')
            );

            echo $tile;
        }

        echo '</ul></div>';

        echo '<div class="ppanel-modal-backdrop"></div>';

        $ppQuery = new WP_Query(array(
           'post_type' => 'page',
           'post__in'  => $pageIds,
           'orderby'   => 'post__in',
        ));
        while($ppQuery->have_posts()):
            $ppQuery->the_post();
?>
            <div class="ppanel-modal-wrapper" id="ppanel-modal-<?php the_ID(); ?>">
                <span class="ppanel-xish"></span>
                <div class="ppanel-text-center ppanel-modal-content">
                    <h1><?php the_title(); ?></h1>
                    <?php if(has_excerpt()): ?><h2><?php the_excerpt(); ?></h2><?php endif; ?>
                    <div class="ppanel-modal-text-body ppanel-text-left">
                        <?php echo apply_filters('the_content',get_the_content()); ?>
                    </div>
                </div>
            </div>
<?php
        endwhile; 
        wp_reset_query();
    }

}

function register_page_panel_widget(){
    register_widget('PagePanel');
}

add_action('widgets_init', 'register_page_panel_widget');


?>
