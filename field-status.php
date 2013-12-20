<?php
/**
 * Plugin Name: Cardiff Soccer Field Status
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: A brief description of the Plugin.
 * Version: The Plugin's Version Number, e.g.: 1.0
 * Author: Name Of The Plugin Author
 * Author URI: http://URI_Of_The_Plugin_Author
 * License: A "Slug" license name e.g. GPL2
 */

add_action('admin_menu', 'field_status_admin_add_page');
function field_status_admin_add_page() {
    add_options_page('Cardiff Soccer Field Status Page', 'Cardiff Soccer Field Status Menu', 'manage_options', 'field_status', 'field_status_options_page');
}

//[foobar]
function field_status_shortcode( $atts ){
    extract( shortcode_atts( array(
        'foo' => 'something',
        'bar' => 'something else',
    ), $atts ) );
    $options = get_option('field_status_options');
    $o2 = get_option('field_names');
    echo '<dl class="fields">';
    foreach( $options as $fkey => $val ) {
        echo '<dt>'.$o2[$fkey].'</dt>';
        echo '<dd><ul>';
        foreach( $val as $skey => $data ) {
            echo '<li>'.ucfirst($skey).': <span title="'.$data['comment'].'" class="'.strtolower($data['status']).'">'.$data['status'].'</span></li>';
        }
        echo '</ul></dd>';
    }
    echo '</dl>';
}
add_shortcode( 'field_status', 'field_status_shortcode' );


// display the admin options page
function field_status_options_page() {
    echo "<div>";
    echo "<h2>Cardiff Soccer Field Status</h2>";
    echo "<form action='options.php' method='post'>";
    echo settings_fields('field_status_options');
    echo do_settings_sections('field_status');
 
    echo "<input name='Submit' type='submit' value='Save Changes' />";
    echo "</form></div>";
}

// add the admin settings and such
add_action('admin_init', 'field_status_admin_init');

function field_status_admin_init(){
    register_setting( 'field_status_options', 'field_status_options', 'field_status_options_validate' );
    add_settings_section('field_status_main', 'Field Status', 'field_status_section_text', 'field_status');
    add_field_settings('berkich',array('South','North'),'Cardiff Elementary (Berkich)');
    add_field_settings('ada',array('East','West'),'Ada Harris Elementary');
    add_field_settings('csp',array('Upper','Lower'),'Cardiff Sports Park (Lake)');
#    add_settings_field('field_status_berkich_south', 'Berkich South', 'field_status_berkich_south_bool', 'field_status', 'field_status_main');
#    add_settings_field('field_status_berkich_north', 'Berkich North', 'field_status_berkich_north_bool', 'field_status', 'field_status_main');
#    add_settings_field('field_status_ada_east', 'Ada East', 'field_status_ada_east_bool', 'field_status', 'field_status_main');
#    add_settings_field('field_status_ada_west', 'Ada West', 'field_status_ada_west_bool', 'field_status', 'field_status_main');
#    add_settings_field('field_status_csp_upper', 'CSP (Lake) Upper', 'field_status_csp_upper_bool', 'field_status', 'field_status_main');
#    add_settings_field('field_status_csp_lower', 'CSP (Lake) Lower', 'field_status_csp_lower_bool', 'field_status', 'field_status_main');
}

function add_field_settings($tag, $subs, $name) {
    $options = get_option('field_status_options');
    $o2 = get_option('field_names');
    $o2[$tag] = $name;
    update_option('field_names', $o2);
    update_option('field_status_options',$options);
    foreach($subs as $sub) {
        add_settings_field('field_status_'.$tag.'_'.$sub, $name.' '.$sub,'field_status_'.$tag.'_'.$sub.'_bool','field_status','field_status_main');
    }
}

function field_status_section_text() {
    echo '<p>Configure fields as open or closed.</p>';
}

function field_status_bool($name,$sub,$options) {
    echo "<div>";
    echo "<input type='radio' id='field_status_{$name}_bool' name='field_status_options[".$name."][".$sub."][status]' value='Open' ".($options[$name][$sub]['status']=='Open'?'CHECKED':'').">Open</input>";
    echo "&nbsp;";
    echo "<input type='radio' id='field_status_{$name}_bool' name='field_status_options[".$name."][".$sub."][status]' value='Closed' ".($options[$name][$sub]['status']=='Closed'?'CHECKED':'').">Closed</input>";
    echo "<input id='field_status_{$name}_comment' name='field_status_options[".$name."][".$sub."][comment]' length=40 value='".$options[$name][$sub]["comment"]."'></input>";
    echo "</div>";
}

function field_status_berkich_south_bool() {
    $options = get_option('field_status_options');
    field_status_bool('berkich', 'south', $options);
}
function field_status_berkich_north_bool() {
    $options = get_option('field_status_options');
    field_status_bool('berkich', 'north', $options);
}

function field_status_ada_east_bool() {
    $options = get_option('field_status_options');
    field_status_bool('ada', 'east', $options);
}
function field_status_ada_west_bool() {
    $options = get_option('field_status_options');
    field_status_bool('ada', 'west', $options);
}

function field_status_csp_upper_bool() {
    $options = get_option('field_status_options');
    field_status_bool('csp', 'upper', $options);
}
function field_status_csp_lower_bool() {
    $options = get_option('field_status_options');
    field_status_bool('csp', 'lower', $options);
}


// validate our options
function field_status_options_validate($input) {
    return $input;
}
