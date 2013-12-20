<?php
/**
 * Plugin Name: Cardiff Soccer Field Status
 * Plugin URI: http://github.com/crindt/wp-field-status
 * Description: Simple plugin to store Cardiff Soccer Field Status in wp config variables.
 * Version: 0.1
 * Author: Craig Rindt
 * Author URI: http://github.com/crindt
 * License: GPL2
 */

add_action('admin_menu', 'field_status_admin_add_page');
function field_status_admin_add_page() {
    add_options_page('Cardiff Soccer Field Status Page', 'Cardiff Soccer Field Status Menu', 'manage_options', 'field_status', 'field_status_options_page');
}

function otag($t, $attr = array()) {
    if ( $t == '' ) return '';
    $attrs = '';
    foreach( $attr as $attrn => $attrv ) {
        $attrs = $attrs . ' ' . $attrn . '= "' . $attrv . '"';
    }
    return '<' . $t . $attrs.'>';
}
function ctag($t) {
    if ( $t == '' ) return '';
    return '</' . $t . '>';
}

function echo_status( $fdata, $fmt ) {
    $ret = '';
    $ret .= otag($fmt['ul']);
    foreach( $fdata as $skey => $data ) {
        $ret .= otag($fmt['li']).ucfirst($skey).': <span title="'.$data['comment'].'" class="'.strtolower($data['status']).'">'.$data['status'].'</span>'.ctag($fmt['li']);
    }
    $ret .= ctag($fmt['ul']);
    return $ret;
}

//[field_status]
function field_status_shortcode( $atts ){
    extract( shortcode_atts( array(
        'field' => '',
        'fmt' => 'ul'
    ), $atts ) );

    # set up list formatting per user request.  fmt=>brian uses <dt> tags.  otherwise, we go with <ul> 
    $fmt = array();
    switch( $atts['fmt'] ) {
    case "brian":
        $fmt['dl'] = 'div';
        $fmt['dt'] = 'dl';
        $fmt['dd'] = '';
        $fmt['ul'] = '';
        $fmt['li'] = 'dt';
        break;
    default:       // default to a ul
        $fmt['dl'] = 'dl';
        $fmt['dt'] = 'dt';
        $fmt['dd'] = 'dd';
        $fmt['ul'] = 'ul';
        $fmt['li'] = 'li';
    }


    $options = get_option('field_status_options');
    $o2 = get_option('field_names');

    if ( $atts['field'] == '' ) { # echo all fields
        $ret .=  otag($fmt['dl'],array("class" => "f-wrap"));
        foreach( $options as $fkey => $val ) {
            $ret .= otag($fmt['dt']).$o2[$fkey].ctag($fmt['dt']);
            $ret .= otag($fmt['dd']);
            $ret .= echo_status($val, $fmt);
            $ret .= ctag($fmt['dd']);
        }
        $ret .=  ctag($fmt['dl']);

    } else {                      # echo specified field
        $ret .= echo_status($options[$atts['field']], $fmt);
    }
    return $ret;
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
