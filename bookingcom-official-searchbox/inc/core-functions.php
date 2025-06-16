<?php
/**
 * Core Function scripts
 * 
 * @since 2.2.4
 *
 * @package Booking Official Searchbox
 */

defined( 'ABSPATH' ) || exit;

register_activation_hook( BOS_PLUGIN_DIR_URL, 'bos_searchbox_install' );
function bos_searchbox_install( ) {
    //this install defaults values
    $bos_searchbox_options = array(
        'plugin_ver' => BOS_PLUGIN_VERSION //plugin version 
    );
    update_option( 'bos_searchbox_options', $bos_searchbox_options );
}

// Add a menu for our option page
add_action( 'admin_menu', 'bos_searchbox_add_page' );
function bos_searchbox_add_page( ) {
    add_menu_page( 
        esc_html__('Booking.com Search Box settings', 'bookingcom-official-searchbox'), // Page title on browser bar 
        esc_html__('Booking.com Search Box', 'bookingcom-official-searchbox'), // menu item text
        'manage_options', // only administrators can open this
        'bos_searchbox', // unique name of settings page
        'bos_searchbox_option_page' //call to function which creates the form
    );

    add_action( 'admin_enqueue_scripts', 'bos_load_styles' );
}


/* Localization and internazionalization */
add_action( 'plugins_loaded', 'bos_searchbox_init' );
function bos_searchbox_init( ) {
    load_plugin_textdomain( 'bookingcom-official-searchbox', false, dirname( BOS_PLUGIN_MAIN_FILE ) . '/languages/' );
}

function bos_searchbox_retrieve_all_user_options( ) {
    // Retrieve all user options from DB
    $user_options = get_option( 'bos_searchbox_user_options' );
    return $user_options;
}

function bos_admin_notices_action() {
    $screen = get_current_screen();
    if ( in_array( $screen->parent_base, array( 'bos_searchbox' ) ) ) {
        settings_errors();
    }
}
add_action( 'admin_notices', 'bos_admin_notices_action' );

function bos_load_styles() {
    bos_register_scripts();
}

if ( ! function_exists( 'bos_searchbox_settings_link' ) ) :

    function bos_searchbox_settings_link( $actions ) {
        $settings_link = '<a href="' . admin_url( 'options-general.php?page=bos_searchbox' ) . '">' . esc_html__( 'Settings', 'bookingcom-official-searchbox' ) . '</a>';
        array_unshift( $actions, $settings_link );
        return $actions;
    }

endif;
add_filter( 'plugin_action_links_' . BOS_PLUGIN_MAIN_FILE, 'bos_searchbox_settings_link' );

// Register and define the settings
add_action( 'admin_init', 'bos_searchbox_admin_init' );
function bos_searchbox_admin_init( ) {
    register_setting( 'bos_searchbox_settings', 'bos_searchbox_user_options', array('sanitize_callback' => 'bos_searchbox_validate_options', 'show_in_rest' => true) );
    $tab_main = (!isset($_GET['tab']) || isset($_GET['tab']) && $_GET['tab'] === 'tab_main') ? '' : ' bos-hide';
    $tab_dest = isset($_GET['tab']) && $_GET['tab'] === 'tab_destination' ? '' : ' bos-hide';
    $tab_colours = isset($_GET['tab']) && $_GET['tab'] === 'tab_colours' ? '' : ' bos-hide';
    $tab_cal = isset($_GET['tab']) && $_GET['tab'] === 'tab_calendar' ? '' : ' bos-hide';
    $tab_sb_text = isset($_GET['tab']) && $_GET['tab'] === 'tab_searchbox_text' ? '' : ' bos-hide';
    add_settings_section( //Main settings 
        'bos_section_main', //id
        '', //title
        'bos_searchbox_section_main', //callback
        'bos_searchbox', //page
        array(
            'before_section' => '<div class="tab-content'.$tab_main.'" id="bos_main_tab">',
            'after_section' => '</div>'
        )
    );
    add_settings_section( //Destination
        'bos_section_destination', //id
        '', //title
        '', //callback
        'bos_searchbox', //page
        array(
            'before_section' => '<div class="tab-content'.$tab_dest.'" id="bos_dest_tab">',
            'after_section' => '</div>'
        )
    );
    add_settings_section( //Color settings
        'bos_section_colours',
        '', 
        '', 
        'bos_searchbox',
        array(
            'before_section' => '<div class="tab-content'.$tab_colours.'" id="bos_colours_tab">',
            'after_section' => '</div>'
        )
    );
    add_settings_section( // Calendar settings
        'bos_section_calendar',
        '', 
        '', 
        'bos_searchbox',
        array(
            'before_section' => '<div class="tab-content'.$tab_cal.'" id="bos_calendar_tab">',
            'after_section' => '</div>'
        )
    );
    add_settings_section( //Wording settings
        'bos_section_searchbox_text',
        '', 
        '', 
        'bos_searchbox',
        array(
            'before_section' => '<div class="tab-content'.$tab_sb_text.'" id="bos_searchbox_text_tab">',
            'after_section' => '</div>'
        )
    );
    $arrayFields = bos_searchbox_settings_fields_array();
    foreach ( $arrayFields as $field ) {
        add_settings_field( 'bos_searchbox_' . $field[ 0 ], //id
            esc_html__( $field[ 2 ], 'bookingcom-official-searchbox' ), //title
            'bos_searchbox_settings_field', //callback
            'bos_searchbox', //page
            'bos_section_' . $field[ 7 ], //section
            $args = array(
                $field[ 0 ], // id
                $field[ 1 ], // type
                $field[ 3 ], // description
                $field[ 4 ], // max input length
                $field[ 5 ], // input size
                $field[ 8 ], // placeholder
                $field[ 9 ], // before text
                $field[ 10 ], // after text
                $field[ 11 ], // hint
                $field[ 12 ], // group
                'class' => preg_match('#(logodim|logopos)#', $field[ 0 ]) ? $field[ 0 ] . '_wrapper hidden' : $field[ 0 ]
            ) //args
        );
    } //$arrayFields as $field
}

function bos_searchbox_section_main( ) {
    echo '<span id="bos_ajax_nonce" class="hidden" style="visibility: hidden;">' . wp_create_nonce( 'bos_ajax_nonce' ) . '</span>';
}

// Display and fill general fields
function bos_searchbox_settings_field( $args ) {
    // get options value from the database        
    $options      = bos_searchbox_retrieve_all_user_options();
    $fields_array = $args[ 0 ];
    $fields_value = '';
    if ( !empty( $options[ $fields_array ] ) ) {
        $fields_value = $options[ $fields_array ]; // if user eneterd values fields_value
    } //!empty( $options[ $fields_array ] )
    // echo the fields
    if ( $args[ 1 ] == 'text' ) {
        echo '<input name="bos_searchbox_user_options[' . esc_attr($fields_array) . ']" id="' . esc_attr($args[ 0 ]) . '" type="' . esc_attr($args[ 1 ]) . '" ';
        if ( !empty( $args[ 3 ] ) ) {
            echo ' maxlength="' . esc_attr($args[ 3 ]) . '" ';
        } //!empty( $args[ 3 ] )
        if ( !empty( $args[ 4 ] ) ) {
            echo ' size="' . esc_attr($args[ 4 ]) . '" ';
        } //!empty( $args[ 4 ] )
        if ( !empty( $args[ 5 ] ) ) {
            echo ' placeholder="' . esc_attr($args[ 5 ]) . '" ';
        } //!empty( $args[ 5 ] )
        // Color scheme default values in case no custom values
        if ( $args[ 0 ] == 'bgcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
            $fields_value = BOS_BGCOLOR;
        } //$args[ 0 ] == 'bgcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
        if ( $args[ 0 ] == 'textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
            $fields_value = BOS_TEXTCOLOR;
        } //$args[ 0 ] == 'textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
        if ( $args[ 0 ] == 'dest_bgcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
            $fields_value = BOS_DEST_BGCOLOR;
        } //$args[ 0 ] == 'dest_bgcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
        if ( $args[ 0 ] == 'headline_textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
            $fields_value = BOS_HEADLINE_TEXTCOLOR;
        } //$args[ 0 ] == 'headline_textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
        if ( $args[ 0 ] == 'dest_textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
            $fields_value = BOS_DEST_TEXTCOLOR;
        } //$args[ 0 ] == 'dest_textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
        if ( $args[ 0 ] == 'date_textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
            $fields_value = BOS_DATE_TEXTCOLOR;
        } //$args[ 0 ] == 'date_textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
        if ( $args[ 0 ] == 'date_bgcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
            $fields_value = BOS_DATES_BGCOLOR;
        } //$args[ 0 ] == 'date_bgcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
        if ( $args[ 0 ] == 'flexdate_textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
            $fields_value = BOS_FLEXDATE_TEXTCOLOR;
        } //$args[ 0 ] == 'flexdate_textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
        if ( $args[ 0 ] == 'submit_bgcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
            $fields_value = BOS_SUBMIT_BGCOLOR;
        } //$args[ 0 ] == 'submit_bgcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
        if ( $args[ 0 ] == 'submit_bordercolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
            $fields_value = BOS_SUBMIT_BORDERCOLOR;
        } //$args[ 0 ] == 'submit_bordercolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
        if ( $args[ 0 ] == 'submit_textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
            $fields_value = BOS_SUBMIT_TEXTCOLOR;
        } //$args[ 0 ] == 'submit_textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
        if ( $args[ 0 ] == 'calendar_selected_bgcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
            $fields_value = BOS_CALENDAR_SELECTED_DATE_BGCOLOR;
        } //$args[ 0 ] == 'calendar_selected_bgcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
        if ( $args[ 0 ] == 'calendar_selected_textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
            $fields_value = BOS_CALENDAR_SELECTED_DATE_TEXTCOLOR;
        } //$args[ 0 ] == 'calendar_selected_textcolor' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
        if ( $args[ 0 ] == 'calendar_daynames_color' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
            $fields_value = BOS_CALENDAR_DAYNAMES_COLOR;
        } //$args[ 0 ] == 'calendar_daynames_color' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )

        echo 'value="' . esc_html($fields_value) . '" />&nbsp;' . wp_kses_post( __( $args[ 2 ], 'bookingcom-official-searchbox' ) );
        if ( $args[ 0 ] == 'dest_id' ) {
            echo '<div id="bos_info_box" style="display: none;padding: 1em; background-color:#FFFFE0;border:1px solid  #E6DB55; margin:10px 0 10px;">';
            echo wp_kses_post( __( 'For more info on your destination ID and destination type, login to the <a href="https://spadmin.booking.com/partner/login-v3.html" target="_blank">Partner Center</a>. Check "Marketplace --> All products" and search for "Affiliate links" card.', 'bookingcom-official-searchbox' ) );
            echo '</div>';
        } //$args[ 0 ] == 'dest_id'
    } // $args[ 1 ] == 'text'
    elseif ( $args[ 1 ] == 'switch' ) {
        $checked_value = 'checked="checked"';
        if (!empty($fields_value)) {
            $checked_value = checked( 1, esc_attr($fields_value), false );
        }
        echo '<div class="bos_toggleSection"><div class="bos_toggleButtonHandles">';
        echo '<div class="bos_beforeToggleLabel">' . esc_attr($args[ 6 ]) . '</div>';
        echo '<div class="bos_toggleButtonSwitch"><label class="bos_toggleButton">';
        echo '<input type="checkbox" name="bos_searchbox_user_options[' . esc_attr($fields_array) . ']" id="' . esc_attr($args[ 0 ]) . '" ' . $checked_value . '>';
        echo '<span class="bos_slider round"></span></label></div>';
        echo '<div class="bos_afterToggleLabel">' . esc_attr($args[ 7 ]) . '</div>';
        echo '</div>';
        echo '<div class="bos_toggleContent"><p class="description">' . esc_attr($args[ 8 ]) . '</p></div>';
        echo '</div>';
    }
    elseif ( $args[ 1 ] == 'number' ) {
        echo '<input name="bos_searchbox_user_options[' . esc_attr($fields_array) . ']" id="' . esc_attr($args[ 0 ]) . '" type="' . esc_attr($args[ 1 ]) . '" ';
        if ( $args[ 0 ] == 'headline_textsize' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
            $fields_value = BOS_HEADLINE_SIZE;
        } //$args[ 0 ] == 'headline_textsize' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )
        elseif ($args[ 0 ] == 'fields_border_radius' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )) {
            $fields_value = BOS_FIELDS_BORDER_RADIUS;
        }
        elseif ($args[ 0 ] == 'sb_border_radius' && ( empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' )) {
            $fields_value = BOS_SB_BORDER_RADIUS;
        }
        elseif ( $args[ 0 ] == 'aid' && ( $fields_value == BOS_DEFAULT_AID || empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' ) ) {
            $fields_value = '';
        } //$args[ 0 ] == 'aid' && ( $fields_value == BOS_DEFAULT_AID || empty( $fields_value ) || $fields_value == '' || $fields_value == ' ' || !is_numeric( $fields_value ) )
        echo 'value="' . esc_html($fields_value) . '" />&nbsp;' . wp_kses_post( __( $args[ 2 ], 'bookingcom-official-searchbox' ) );
    } // $args[ 1 ] == 'number'
    elseif ( $args[ 1 ] == 'checkbox' ) {
        if ( $args[ 0 ] == 'calendar' ) {
            if ( empty( $fields_value ) ) {
                $fields_value = BOS_CALENDAR;
            } // default value
        } //$args[ 0 ] == 'calendar'
        else if ( $args[ 0 ] == 'flexible_dates' ) {
            if ( empty( $fields_value ) ) {
                $fields_value = BOS_FLEXIBLE_DATES;
            } // default values
        } //$args[ 0 ] == 'flexible_dates'
        else if ( $args[ 0 ] == 'show_weeknumbers' ) {
            if ( empty( $fields_value ) ) {
                $fields_value = BOS_SHOW_WEEKNUMBERS;
            }
        }
        echo '<input name="bos_searchbox_user_options[' . esc_attr($fields_array) . ']" id="' . esc_attr($args[ 0 ]) . '" type="' . esc_attr($args[ 1 ]) . '"  ' . checked( 1, esc_attr($fields_value), false ) . ' />';
    } //$args[ 1 ] == 'checkbox'
    elseif ( $args[ 1 ] == 'radio' ) {
        if ( $args[ 0 ] == 'month_format' ) {
            if ( empty( $fields_value ) ) {
                $fields_value = BOS_MONTH_FORMAT;
            } // default values
            //if( empty( $fields_value ) ) { $fields_value = 'short' ; }// set defaults value
            echo '<input name="bos_searchbox_user_options[' . esc_attr($fields_array) . ']" class="' . esc_attr($args[ 0 ]) . '" type="' . esc_attr($args[ 1 ]) . '"  value="long" ' . checked( 'long', esc_attr($fields_value), false ) . ' />&nbsp;' . esc_html__( 'long', 'bookingcom-official-searchbox' );
            echo '&nbsp;<input name="bos_searchbox_user_options[' . esc_attr($fields_array) . ']" class="' . esc_attr($args[ 0 ]) . '" type="' . esc_attr($args[ 1 ]) . '"  value="short" ' . checked( 'short', esc_attr($fields_value), false ) . ' />&nbsp;' . esc_html__( 'short', 'bookingcom-official-searchbox' );
        } // $args[ 0 ] == 'month_format'
        if ( $args[ 0 ] == 'logodim' ) {
            //if( empty( $fields_value ) ) { $fields_value = 'blue_150x25' ; }// set defaults value
            $bgcolor = !empty($options[ 'bgcolor' ]) ? $options[ 'bgcolor' ] : BOS_BGCOLOR; // default values
            if ( empty( $fields_value ) ) {
                $fields_value = BOS_LOGODIM;
            } // default values
            echo '<span id="bos_img_blue_logo" class="bos_logo_dim_box" style="background: ' . esc_attr($bgcolor) . ';"><img  src="' . BOS_PLUGIN_ASSETS . '/images/booking_logo_blue_150x25.png" alt="Booking.com logo" /></span>';
            echo '<input name="bos_searchbox_user_options[' . esc_attr($fields_array) . ']" class="' . esc_attr($args[ 0 ]) . '" type="' . esc_attr($args[ 1 ]) . '"  value="blue_150x25"  ' . checked( 'blue_150x25', esc_attr($fields_value), false ) . ' />&nbsp;( 150x25 )&nbsp;';
            echo '<input name="bos_searchbox_user_options[' . esc_attr($fields_array) . ']" class="' . esc_attr($args[ 0 ]) . '" type="' . esc_attr($args[ 1 ]) . '"  value="blue_200x33"  ' . checked( 'blue_200x33', esc_attr($fields_value), false ) . ' />&nbsp;( 200x33 )&nbsp;';
            echo '<input name="bos_searchbox_user_options[' . esc_attr($fields_array) . ']" class="' . esc_attr($args[ 0 ]) . '" type="' . esc_attr($args[ 1 ]) . '"  value="blue_300x50" ' . checked( 'blue_300x50', esc_attr($fields_value), false ) . ' />&nbsp;( 300x50 )&nbsp;';
            echo '<br /><br />';
            echo '<span id="bos_img_white_logo" class="bos_logo_dim_box" style="background: ' . esc_attr($bgcolor) . ';"><img src="' . BOS_PLUGIN_ASSETS . '/images/booking_logo_white_150x25.png" alt="Booking.com logo" /></span>';
            echo '<input name="bos_searchbox_user_options[' . esc_attr($fields_array) . ']" class="' . esc_attr($args[ 0 ]) . '" type="' . esc_attr($args[ 1 ]) . '"  value="white_150x25" ' . checked( 'white_150x25', esc_attr($fields_value), false ) . ' />&nbsp;( 150x25 )&nbsp;';
            echo '<input name="bos_searchbox_user_options[' . esc_attr($fields_array) . ']" class="' . esc_attr($args[ 0 ]) . '" type="' . esc_attr($args[ 1 ]) . '"  value="white_200x33" ' . checked( 'white_200x33', esc_attr($fields_value), false ) . ' />&nbsp;( 200x33 )&nbsp;';
            echo '<input name="bos_searchbox_user_options[' . esc_attr($fields_array) . ']" class="' . esc_attr($args[ 0 ]) . '" type="' . esc_attr($args[ 1 ]) . '"  value="white_300x50" ' . checked( 'white_300x50', esc_attr($fields_value), false ) . ' />&nbsp;( 300x50 )&nbsp;';
        } // $args[ 0 ] == 'logodim'            
    } // $args[ 1 ] == 'radio'      
    elseif ( $args[ 1 ] == 'select' ) {
        if ( $args[ 0 ] == 'logopos' ) {
            echo '<select name="bos_searchbox_user_options[' . esc_attr($fields_array) . ']" id="' . esc_attr($args[ 0 ]) . '" >';
            echo '<option value="left" ' . selected( 'left', esc_attr($fields_value), false ) . ' >' . esc_html__( 'Left', 'bookingcom-official-searchbox' ) . '</option>';
            echo '<option value="center" ' . selected( 'center', esc_attr($fields_value), false ) . ' >' . esc_html__( 'Centre', 'bookingcom-official-searchbox' ) . '</option>';
            echo '<option value="right" ' . selected( 'right', esc_attr($fields_value), false ) . ' >' . esc_html__( 'Right', 'bookingcom-official-searchbox' ) . '</option>';
            echo '</select>';
        } // $args[ 0 ] == 'logopos'
        if ( $args[ 0 ] == 'buttonpos' ) {
            if ( empty( $fields_value ) ) {
                $fields_value = BOS_BUTTONPOS;
            } //empty( $fields_value )
            echo '<select name="bos_searchbox_user_options[' . esc_attr($fields_array) . ']" id="' . esc_attr($args[ 0 ]) . '" >';
            echo '<option value="left" ' . selected( 'left', esc_attr($fields_value), false ) . ' >' . esc_html__( 'Left', 'bookingcom-official-searchbox' ) . '</option>';
            echo '<option value="center" ' . selected( 'center', esc_attr($fields_value), false ) . ' >' . esc_html__( 'Centre', 'bookingcom-official-searchbox' ) . '</option>';
            echo '<option value="right" ' . selected( 'right', esc_attr($fields_value), false ) . ' >' . esc_html__( 'Right', 'bookingcom-official-searchbox' ) . '</option>';
            echo '</select>&nbsp;' . wp_kses_post( __( $args[ 2 ], 'bookingcom-official-searchbox' ) );
        } // $args[ 0 ] == 'buttonpos'
        if ( $args[ 0 ] == 'dest_type' ) {
            echo '<select name="bos_searchbox_user_options[' . esc_attr($fields_array) . ']" id="' . esc_attr($args[ 0 ]) . '" >';
            echo '<option value="select" ' . selected( 'select', esc_attr($fields_value), false ) . ' >' . esc_html__( 'select...', 'bookingcom-official-searchbox' ) . '</option>';
            echo '<option value="city" ' . selected( 'city', esc_attr($fields_value), false ) . ' >' . esc_html__( 'city', 'bookingcom-official-searchbox' ) . '</option>';
            echo '<option value="landmark" ' . selected( 'landmark', esc_attr($fields_value), false ) . ' >' . esc_html__( 'landmark', 'bookingcom-official-searchbox' ) . '</option>';
            //echo '<option value="district" ' . selected( 'district', $fields_value, false ) . ' >' . esc_html__( 'district' , BOS_TEXT_DOMAIN) . '</option>' ;
            echo '<option value="region" ' . selected( 'region', esc_attr($fields_value), false ) . ' >' . esc_html__( 'region', 'bookingcom-official-searchbox' ) . '</option>';
            echo '<option value="airport" ' . selected( 'airport', esc_attr($fields_value), false ) . ' >' . esc_html__( 'airport', 'bookingcom-official-searchbox' ) . '</option>';
            echo '</select>';
        } //$args[ 0 ] == 'dest_type'
    } // $args[ 1 ] == 'select'
}

function bos_searchbox_validate_options( $input ) {
    $valid = array();

    if (isset($input['display_in_custom_post_types']) && !empty($input['display_in_custom_post_types'])) {
        if (preg_match( '/^[a-zA-Z0-9-_,]+$/', $input['display_in_custom_post_types'] )) {
            $valid['display_in_custom_post_types'] = sanitize_text_field($input['display_in_custom_post_types']);
        } else {
            add_settings_error( 'bos_searchbox_user_options', //setting
                'bos_searchbox_display_in_custom_post_types_error', //code added to tag #id            
                'Custom Post Type: ' . esc_html__( 'Use only alphanumeric strings and commas for multiple slugs', 'bookingcom-official-searchbox' ) . '<br>',
                'error'
            );
        }
    }

    if (isset($input['cname'])) {
        if (!empty($input['cname'])) {
            if (preg_match( '/^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\\-_]*[a-zA-Z0-9])\\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\\-]*[A-Za-z0-9])$/', $input['cname'] )) {
                $valid['cname'] = sanitize_text_field($input['cname']);
            } else {
                add_settings_error( 'bos_searchbox_user_options', //setting
                    'bos_searchbox_cname_error', //code added to tag #id            
                    'Cname: ' . esc_html__( 'Cname format is incorrect', 'bookingcom-official-searchbox' ) . '<br>',
                    'error'
                );
            }
        }
    }

    if (isset($input['widget_width'])) {
        $valid['widget_width'] = sanitize_text_field($input['widget_width']);
    }

    if (isset($input['aid']) && !empty($input['aid']) && is_numeric($input['aid'])) {
        if ( !preg_match( '/^(?:4|7)\d+$/', $input['aid'] ) ) {
            $valid['aid'] = sanitize_text_field($input['aid']);
        } else {
            add_settings_error( 'bos_searchbox_user_options', //setting
                'bos_searchbox_display_aid_match_error', //code added to tag #id
                'Affiliate ID: ' . esc_html__( 'Affiliate ID is different from partner ID: should start with a 1, 3, 8 or 9. Please change it.', 'bookingcom-official-searchbox' ) . '<br>',
                'error'
            );
        }
    }

    if (isset($input['fields_border_radius']) && !empty($input['fields_border_radius']) && is_numeric($input['fields_border_radius'])) {
        if ( !empty($input['fields_border_radius']) ) {
            $valid['fields_border_radius'] = sanitize_text_field($input['fields_border_radius']);
        } else {
            add_settings_error( 'bos_searchbox_user_options', //setting
                'bos_searchbox_fields_border_radius_error', //code added to tag #id
                'Border radius: ' . esc_html__( 'Border radius should contain a number.', 'bookingcom-official-searchbox' ) . '<br>',
                'error'
            );
        }
    }

    if (isset($input['sb_border_radius']) && !empty($input['sb_border_radius']) && is_numeric($input['sb_border_radius'])) {
        if ( !empty($input['sb_border_radius']) ) {
            $valid['sb_border_radius'] = sanitize_text_field($input['sb_border_radius']);
        } else {
            add_settings_error( 'bos_searchbox_user_options', //setting
                'bos_searchbox_sb_border_radius_error', //code added to tag #id
                'Searchbox Border radius: ' . esc_html__( 'Searchbox Border radius should contain a number.', 'bookingcom-official-searchbox' ) . '<br>',
                'error'
            );
        }
    }

    if (isset($input['month_format'])) {
        switch ( $input['month_format'] ) {
            case 'short':
                $valid['month_format'] = $input['month_format'];
                break;
            case 'long':
            default:
                $valid['month_format'] = 'long';
                break;
        }
    }

    if (isset($input['logodim'])) {
        switch ( $input['logodim'] ) {
            case 'blue_200x33':
            case 'blue_300x50':
            case 'white_150x25':
            case 'white_200x33':
            case 'white_300x50':
                $valid['logodim'] = $input['logodim'];
                break;
            case 'blue_150x25':
            default:
                $valid['logodim'] = 'blue_150x25'; //default : blue_150x25
                break;
        }
    }

    if (isset($input['logo_enabled'])) {
        $valid['logo_enabled'] = empty($input['logo_enabled']) ? 0 : 1;
    }

    if (isset($input['buttonpos'])) {
        switch ( $input['buttonpos'] ) {
            case 'center':
            case 'left':
                $valid['buttonpos'] = $input['buttonpos'];
                break;
            case 'right':
            default:
                $valid['buttonpos'] = 'right'; //default : right
                break;
        }
    }

    if (isset($input['logopos'])) {
        switch ( $input['logopos'] ) {
            case 'center':
            case 'right':
                $valid['logopos'] = $input['logopos'];
                break;
            case 'left':
            default:
                $valid['logopos'] = 'left'; //default : left
                break;
        }
    }

    if (isset($input['destination'])) {
        $valid['destination'] = sanitize_text_field($input['destination']);
    }

    if (isset($input['dest_type'])) {
        switch ( $input['dest_type'] ) {
            case 'city':
            case 'region':
            case 'district':
            case 'landmark':
                $valid['dest_type'] = $input['dest_type'];
                break;
            case 'select':
            default:
                $valid['dest_type'] = 'select'; //default : select
                break;
        }
    }

    if (isset($input['dest_id'])) {
        $valid['dest_id'] = sanitize_text_field($input['dest_id']);
    }

    if (isset($input['bgcolor']) && !empty($input['bgcolor'])) {
        $valid['bgcolor'] = sanitize_text_field($input['bgcolor']);
    }
    if (isset($input['headline_textcolor']) && !empty($input['headline_textcolor'])) {
        $valid['headline_textcolor'] = sanitize_text_field($input['headline_textcolor']);
    }
    if (isset($input['textcolor']) && !empty($input['textcolor'])) {
        $valid['textcolor'] = sanitize_text_field($input['textcolor']);
    }
    if (isset($input['dest_textcolor']) && !empty($input['dest_textcolor'])) {
        $valid['dest_textcolor'] = sanitize_text_field($input['dest_textcolor']);
    }
    if (isset($input['dest_bgcolor']) && !empty($input['dest_bgcolor'])) {
        $valid['dest_bgcolor'] = sanitize_text_field($input['dest_bgcolor']);
    }
    if (isset($input['flexdate_textcolor']) && !empty($input['flexdate_textcolor'])) {
        $valid['flexdate_textcolor'] = sanitize_text_field($input['flexdate_textcolor']);
    }
    if (isset($input['date_bgcolor']) && !empty($input['date_bgcolor'])) {
        $valid['date_bgcolor'] = sanitize_text_field($input['date_bgcolor']);
    }
    if (isset($input['date_textcolor']) && !empty($input['date_textcolor'])) {
        $valid['date_textcolor'] = sanitize_text_field($input['date_textcolor']);
    }
    if (isset($input['submit_bgcolor']) && !empty($input['submit_bgcolor'])) {
        $valid['submit_bgcolor'] = sanitize_text_field($input['submit_bgcolor']);
    }
    if (isset($input['submit_bordercolor']) && !empty($input['submit_bordercolor'])) {
        $valid['submit_bordercolor'] = sanitize_text_field($input['submit_bordercolor']);
    }
    if (isset($input['submit_textcolor']) && !empty($input['submit_textcolor'])) {
        $valid['submit_textcolor'] = sanitize_text_field($input['submit_textcolor']);
    }
    if (isset($input['calendar_selected_bgcolor']) && !empty($input['calendar_selected_bgcolor'])) {
        $valid['calendar_selected_bgcolor'] = sanitize_text_field($input['calendar_selected_bgcolor']);
    }
    if (isset($input['calendar_selected_textcolor']) && !empty($input['calendar_selected_textcolor'])) {
        $valid['calendar_selected_textcolor'] = sanitize_text_field($input['calendar_selected_textcolor']);
    }
    if (isset($input['calendar_daynames_color']) && !empty($input['calendar_daynames_color'])) {
        $valid['calendar_daynames_color'] = sanitize_text_field($input['calendar_daynames_color']);
    }


    if (isset($input['flexible_dates'])) {
        $valid['flexible_dates'] = empty($input['flexible_dates']) ? 0 : 1;
    }

    if (isset($input['show_weeknumbers'])) {
        $valid['show_weeknumbers'] = empty($input['show_weeknumbers']) ? 0 : 1;
    }

    
    if (isset($input['headline_textsize']) && is_numeric($input['headline_textsize'])) {
        $valid['headline_textsize'] = sanitize_text_field($input['headline_textsize']);
    }
    if (isset($input['maintitle'])) {
        $valid['maintitle'] = sanitize_text_field($input['maintitle']);
    }
    if (isset($input['dest_title'])) {
        $valid['dest_title'] = sanitize_text_field($input['dest_title']);
    }
    if (isset($input['checkin'])) {
        $valid['checkin'] = sanitize_text_field($input['checkin']);
    }
    if (isset($input['checkout'])) {
        $valid['checkout'] = sanitize_text_field($input['checkout']);
    }
    if (isset($input['submit'])) {
        $valid['submit'] = sanitize_text_field($input['submit']);
    }

    return $valid;
}

function dynamic_bos_css() {
    $bos_options = bos_searchbox_retrieve_all_user_options();
    
    if ($bos_options && count($bos_options)) {
        $custom_css = '.daterangepicker.bos-css th.month, .daterangepicker.bos-css .calendar-table th:not(.week) {color: ' .$bos_options['calendar_daynames_color']. ';}';

        $custom_css .= '.daterangepicker.bos-css td.in-range { background-color: #1a1a1a0f; }';

        $custom_css .= '.daterangepicker.bos-css td.active, .daterangepicker.bos-css td.active:hover { background-color: ' .$bos_options['calendar_selected_bgcolor']. ';}';

        $custom_css .= '.daterangepicker.bos-css td.off, .daterangepicker.bos-css td.off.in-range, .daterangepicker.bos-css td.off.start-date, .daterangepicker.bos-css td.off.end-date { background-color: #FFF; }';

        $css_file_path = BOS_PLUGIN_MAIN_PATH . '/assets/css/bos_dynamic.css';

        file_put_contents($css_file_path, $custom_css);
    }
}


add_action( 'wp_ajax_bos_preview', 'bos_ajax_preview' );
function bos_ajax_preview( ) {
    if ( isset( $_REQUEST[ 'nonce' ] ) ) {
        // Verify that the incoming request is coming with the security nonce
        if ( wp_verify_nonce( $_REQUEST[ 'nonce' ], 'bos_ajax_nonce' ) ) {
            $options_array = get_option('bos_searchbox_user_options');
            $arrayFields = bos_searchbox_settings_fields_array();
            foreach ( $arrayFields as $field ) {
                if ( $field[ 1 ] == 'text' || $field[ 1 ] == 'number' || $field[ 1 ] == 'radio' || $field[ 1 ] == 'select' ) {
                    $options[ $field[ 0 ] ] = isset( $_REQUEST[ $field[ 0 ] ] ) ? stripslashes( sanitize_text_field( $_REQUEST[ $field[ 0 ] ] ) ) : '';
                } //if ( $field[ 1 ] == 'text' )
                elseif ( $field[ 1 ] == 'checkbox' ) {
                    if ( $field[ 0 ] == 'flexible_dates' ) {
                        $options[ $field[ 0 ] ] = empty( $_REQUEST[ 'flexible_dates' ] ) ? 0 : 1;
                    }//if ( $field[ 0 ] == 'flexible_dates' )
                    elseif ( $field[ 0 ] == 'show_weeknumbers' ) {
                        $options[ $field[ 0 ] ] = empty( $_REQUEST[ 'show_weeknumbers' ]) ? 0 : 1;
                    }//$field[ 0 ] == 'show_weeknumbers'
                } //$field[ 1 ] == 'checkbox'
                elseif ( $field[ 1 ] == 'switch' ) {
                    if ( $field[ 0 ] == 'logo_enabled' ) {
                        $options[ $field[ 0 ] ] = empty( $_REQUEST[ 'logo_enabled' ] ) ? 0 : 1;
                    } //if ( $field[ 0 ] == 'logo_enabled' )
                } //$field[ 1 ] == 'switch'
            } //foreach( $arrayFields as $field)

            $preview = true;
            echo '<div id="bos_preview_title"><img src="' . esc_attr(BOS_PLUGIN_ASSETS) . '/images/preview_title.png" alt="Preview" /></div>';
            bos_create_searchbox( $options, $preview );
            die( );
        } //wp_verify_nonce( $_REQUEST[ 'nonce' ], 'bos_ajax_nonce' )
        else {
            die( 'There was an issue in the preview statement' );
        }
    } //isset( $_REQUEST[ 'nonce' ] )
}