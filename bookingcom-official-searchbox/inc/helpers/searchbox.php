<?php
/**
 * Searchbox helpers.
 * @package Booking Official Searchbox\Helpers
 * @since 2.2.4
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'bos_searchbox_option_page' ) ) :

    function bos_searchbox_option_page( ) {
        // Include of checkin and checkout select
        ?>
        <div class="wrap bos_settings">
            <h1><?php echo get_admin_page_title(); ?></h1>
            <?php
            $tabs = array(
                'tab_main' => esc_html__( 'Main settings', 'bookingcom-official-searchbox' ),
                'tab_destination' => esc_html__( 'Preset destination', 'bookingcom-official-searchbox' ),
                'tab_colours' => esc_html__( 'Colour settings', 'bookingcom-official-searchbox' ),
                'tab_calendar' => esc_html__( 'Calendar settings', 'bookingcom-official-searchbox' ),
                'tab_searchbox_text' => esc_html__( 'Search box text', 'bookingcom-official-searchbox' ),
            );
            $current_tab = isset($_GET['tab']) && isset( $tabs[ $_GET['tab'] ]) ? $_GET['tab'] : array_key_first($tabs);
            ?>
            <nav class="nav-tab-wrapper">
                <?php
                foreach( $tabs as $tab => $name ) {
                    $current = $tab === $current_tab ? ' nav-tab-active' : '';
                    $url = add_query_arg( array( 'page' => 'bos_searchbox', 'tab' => $tab ), '' );

                    echo "<a class=\"nav-tabs{$current}\" href=\"{$url}\">{$name}</a>";
                }
                ?>
            </nav>
            <div id="bos_settings">
                <form method="post" action="options.php">
                    <?php 
                        settings_fields('bos_searchbox_settings');
                        custom_do_settings_sections('bos_searchbox');
                    ?>
                    <p class="submit">
                        <!-- fallback in case no javascript -->
                        <noscript><style>#reset_default, #preview_button, #bos_right { display: none; } #bos_wrap { background: none !important; }</style></noscript>
        
                        <input type="button" id="preview_button" class="button-primary" data-clicked="false" value="<?php esc_html_e( 'Preview', 'bookingcom-official-searchbox' ); ?>" />
                        <input type="submit" class="button-primary" value="<?php esc_html_e( 'Save Changes', 'bookingcom-official-searchbox' ); ?>" />
                        <input type="submit" id="reset_default" class="button-secondary" value="<?php esc_html_e( 'Reset to default', 'bookingcom-official-searchbox' ); ?>" />
                    </p>
                </form>
            </div>
            <hr />  
            <div id="bos_preview_wrapper">
                <div id="bos_preview">
                    <div id="bos_preview_title">
                        <img src="<?php echo esc_attr(BOS_PLUGIN_ASSETS) . '/images/preview_title.png'; ?>" alt="Preview" />
                    </div>
                    <?php
                    $options = bos_searchbox_retrieve_all_user_options();
                    $preview = true;
                    bos_create_searchbox( $options, $preview );
                    ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    <?php
    }

endif;


if ( ! function_exists( 'bos_create_searchbox' ) ) :

    function bos_create_searchbox( $searchbox_options, $preview ) {
        $options = $searchbox_options;

        $args = array(
            "preview_mode"                => $preview ? $preview : false,
            "destination"                 => !empty( $options[ 'destination' ] ) ? $options[ 'destination' ] : '',
            "dest_type"                   => !empty( $options[ 'dest_type' ] ) ? $options[ 'dest_type' ] : BOS_DEST_TYPE,
            "dest_id"                     => !empty( $options[ 'dest_id' ] ) ? $options[ 'dest_id' ] : '',
            "display_in_custom_post_type" => !empty( $options[ 'display_in_custom_post_type' ] ) ? $options[ 'display_in_custom_post_type' ] : '',
            "widget_width"                => !empty( $options[ 'widget_width' ] ) ? $options[ 'widget_width' ] : '',
            "domain"                      => !empty( $options[ 'cname' ] ) ? '//' . $options[ 'cname' ] . '/' : BOS_DEFAULT_DOMAIN,
            "cname"                       => !empty( $options[ 'cname' ] ) ? $options[ 'cname' ] : '',
            "flexible_dates"              => empty( $options[ 'flexible_dates' ] ) ? 0 : 1,
            "logo_enabled"                => (!empty( $options[ 'logo_enabled' ] ) ? $options[ 'logo_enabled' ] : (isset( $options[ 'logo_enabled' ] ) && $options[ 'logo_enabled' ] == 0 ? 0 : BOS_LOGO_ENABLED)),
            "logodim"                     => !empty( $options[ 'logodim' ] ) ? $options[ 'logodim' ] : BOS_LOGODIM,
            "logopos"                     => !empty( $options[ 'logopos' ] ) ? $options[ 'logopos' ] : BOS_LOGOPOS,
            "fields_border_radius"        => !empty( $options[ 'fields_border_radius' ] ) ? $options[ 'fields_border_radius' ] : BOS_FIELDS_BORDER_RADIUS,
            "sb_border_radius"            => !empty( $options[ 'sb_border_radius' ] ) ? $options[ 'sb_border_radius' ] : BOS_SB_BORDER_RADIUS,
            "selected_datecolor"          => !empty( $options[ 'selected_datecolor' ] ) ? $options[ 'selected_datecolor' ] : BOS_SELECTED_DATE_COLOR,
            "buttonpos"                   => !empty( $options[ 'buttonpos' ] ) ? $options[ 'buttonpos' ] : BOS_BUTTONPOS,
            "dest_bgcolor"                => !empty( $options[ 'dest_bgcolor' ] ) ? $options[ 'dest_bgcolor' ] : BOS_DEST_BGCOLOR,
            "dest_textcolor"              => !empty( $options[ 'dest_textcolor' ] ) ? $options[ 'dest_textcolor' ] : BOS_DEST_TEXTCOLOR,
            "headline_textsize"           => !empty( $options[ 'headline_textsize' ] ) ? $options[ 'headline_textsize' ] : BOS_HEADLINE_SIZE,
            "headline_textcolor"          => !empty( $options[ 'headline_textcolor' ] ) ? $options[ 'headline_textcolor' ] : BOS_HEADLINE_TEXTCOLOR,
            "textcolor"                   => !empty( $options[ 'textcolor' ] ) ? $options[ 'textcolor' ] : BOS_TEXTCOLOR,
            "flexdate_textcolor"          => !empty( $options[ 'flexdate_textcolor' ] ) ? $options[ 'flexdate_textcolor' ] : BOS_FLEXDATE_TEXTCOLOR,
            "date_bgcolor"                => !empty( $options[ 'date_bgcolor' ] ) ? $options[ 'date_bgcolor' ] : BOS_DATES_BGCOLOR,
            "date_textcolor"              => !empty( $options[ 'date_textcolor' ] ) ? $options[ 'date_textcolor' ] : BOS_DATE_TEXTCOLOR,
            "bgcolor"                     => !empty( $options[ 'bgcolor' ] ) ? $options[ 'bgcolor' ] : BOS_BGCOLOR,
            "submit_bgcolor"              => !empty( $options[ 'submit_bgcolor' ] ) ? $options[ 'submit_bgcolor' ] : BOS_SUBMIT_BGCOLOR,
            "submit_bordercolor"          => !empty( $options[ 'submit_bordercolor' ] ) ? $options[ 'submit_bordercolor' ] : BOS_SUBMIT_BORDERCOLOR,
            "submit_textcolor"            => !empty( $options[ 'submit_textcolor' ] ) ? $options[ 'submit_textcolor' ] : BOS_SUBMIT_TEXTCOLOR,
            "calendar_selected_bgcolor"   => !empty( $options[ 'calendar_selected_bgcolor' ] ) ? $options[ 'calendar_selected_bgcolor' ] : BOS_CALENDAR_SELECTED_DATE_BGCOLOR,
            "calendar_selected_textcolor" => !empty( $options[ 'calendar_selected_textcolor' ] ) ? $options[ 'calendar_selected_textcolor' ] : BOS_CALENDAR_SELECTED_DATE_TEXTCOLOR,
            "calendar_daynames_color"     => !empty( $options[ 'calendar_daynames_color' ] ) ? $options[ 'calendar_daynames_color' ] : BOS_CALENDAR_DAYNAMES_COLOR,
            "maintitle"                   => !empty( $options[ 'maintitle' ] ) ? $options[ 'maintitle' ] : __( 'Search hotels and more...', 'bookingcom-official-searchbox' ),
            "dest_title"                  => !empty( $options[ 'dest_title' ] ) ? $options[ 'dest_title' ] : __( 'Destination', 'bookingcom-official-searchbox' ),
            "checkin"                     => !empty( $options[ 'checkin' ] ) ? $options[ 'checkin' ] : __( 'Check-in date', 'bookingcom-official-searchbox' ),
            "checkout"                    => !empty( $options[ 'checkout' ] ) ? $options[ 'checkout' ] : __( 'Check-out date', 'bookingcom-official-searchbox' ),
            "show_weeknumbers"            => empty( $options[ 'show_weeknumbers' ] ) ? 0 : 1,
            "submit"                      => !empty( $options[ 'submit' ] ) ? $options[ 'submit' ] : __( 'Search', 'bookingcom-official-searchbox' ),
            "target_page"                 => BOS_TARGET_PAGE,
            "aid"                         => empty( $options[ 'aid' ] ) || !is_numeric( $options[ 'aid' ] ) || $options[ 'aid' ] == '' || $options[ 'aid' ] == ' ' ? BOS_DEFAULT_AID : trim( $options[ 'aid' ] ),
        );

        bos_searchbox($args);
    }

endif;

if ( ! function_exists( 'bos_searchbox' ) ) :

    function bos_searchbox( $args, $bos_mb_destination = '', $bos_mb_dest_type = '', $bos_mb_dest_id = '') {

        // Retrieve all meta box values
        if ( is_page() || is_single() ) {
            global $wp_query;
            $postid             = $wp_query->post->ID;
            $bos_mb_destination = get_post_meta( $postid, '_bos_mb_destination', true );
            $bos_mb_dest_type   = get_post_meta( $postid, '_bos_mb_dest_type', true );
            $bos_mb_dest_id     = get_post_meta( $postid, '_bos_mb_dest_id', true );
            wp_reset_query();
        } //is_page() || is_single()
        ?>

        <div id="flexi_searchbox" style="<?php
            echo $args['bgcolor'] ? 'background-color:' . esc_attr($args['bgcolor']) . ';' : '';
            echo $args['textcolor'] ? 'color:' . esc_attr($args['textcolor']) . ';' : '';
            echo $args['widget_width'] ? 'width:' . esc_attr($args['widget_width']) . ';' : '';
            echo $args['sb_border_radius'] ? 'border-radius:' . esc_attr($args['sb_border_radius']) . 'px;' : '';
        ?>" data-ver="<?php echo esc_attr(BOS_PLUGIN_VERSION);?>" >

            <div id="b_searchboxInc">
                <?php 
                    $headline_textcolor = $args['headline_textcolor'] ? 'color:' . esc_attr($args['headline_textcolor']) . ';' : '';
                    $headline_textsize = $args['headline_textsize'] ? 'font-size:' . esc_attr($args['headline_textsize']) . 'px;' : '';
                ?>
                <h2 class="search-box-title-1"  style="<?php echo esc_attr($headline_textcolor) . esc_attr($headline_textsize); ?>">
                    <?php esc_html_e($args['maintitle']);?>
                </h2>
                <form id="b_frm" action="<?php echo esc_attr($args['domain'])  . esc_attr($args['target_page']);?>" method="get" target="_blank" onsubmit="return sp.validation.validSearch();">
                    <div id="searchBox_error_msg" class="b_error b_external_searchbox" style="display: none;"></div>
                    <div id="b_frmInner">                        
                        <input type="hidden" name="si" value="ai,co,ci,re,di" />
                        <input type="hidden" name="utm_campaign" value="search_box" /> 
                        <input type="hidden" name="utm_medium" value="sp" /> 
                        
                        <?php
                        /* Print the aid if we do not have  acname or if  we have a cname and a affiliate aid */
                        if ( empty( $cname ) || ( !empty( $cname ) && $args['aid'] != BOS_DEFAULT_AID ) ) {
                            echo '<input type="hidden" name="aid" value="' . esc_attr($args['aid']) . '" />';
                            echo '<input type="hidden" name="label" value="wp-searchbox-widget-' . esc_attr($args['aid']) . '" />';
                            echo '<input type="hidden" name="utm_term" value="wp-searchbox-widget-' . esc_attr($args['aid']) . '" />';
                            echo '<input type="hidden" name="error_url" value="' . esc_attr($args['domain']) . esc_attr($args['target_page']) . '?aid=' . esc_attr($args['aid']) . ';" />';
                        } //empty( $cname ) || ( !empty( $cname ) && $aid != BOS_DEFAULT_AID )
                        /*This shoudl not be necessary anymore, but just in case we skip the disambiguation page*/
                        elseif ( !empty( $cname ) ) {
                            echo '<input type="hidden" name="ifl" value="1" />';
                            echo '<input type="hidden" name="label" value="wp-searchbox-widget-' . esc_attr($args['cname']) . '" />';
                            echo '<input type="hidden" name="utm_term" value="wp-searchbox-widget-' . esc_attr($args['cname']) . '" />';
                            echo '<input type="hidden" name="error_url" value="' . esc_attr($args['domain']) . esc_attr($args['target_page']) . '" />';
                        } //!empty( $cname )
                        else {
                            echo '<input type="hidden" name="label" value="wp-searchbox-widget-' . esc_attr($args['aid']) . '" />';
                        }
                        ?>             
                        <div id="b_searchDest">
                            <h3 id="b_destination_h4" style="<?php echo $args['textcolor'] ? 'color:' . esc_attr($args['textcolor']) . ';' : '' ?>">
                                <?php echo esc_html($args['dest_title']);?>
                            </h3>
    
                            <?php
                            $dest_bgcolor = $args['dest_bgcolor'] ? "background-color:" . esc_attr($args['dest_bgcolor']) . ";" : "";
                            $dest_textcolor = $args['dest_textcolor'] ? "color:" . esc_attr($args['dest_textcolor']) . ";" : "";
                            $dest_border_radius = $args['fields_border_radius'] ? "border-radius:" . esc_attr($args['fields_border_radius']) . "px;" : "";
                            $light_placeholder = wc_hex_is_light($args['dest_textcolor']) ? " light_placeholder" : "";
                            if ( !empty( $bos_mb_destination ) ) { //$bos_mb_destination can have values ONLY on page and single post template 
                                if ( $bos_mb_dest_type != 'select' && !empty( $bos_mb_dest_id ) ) { // Set destination type and id if exists from meta boxes only if page or single post template
                                    echo '<span class="b_dest_wrap"><input type="text" id="b_destination"  class="b_destination'.$light_placeholder.'" name="ss" placeholder="' . esc_html__( 'e.g. city, region, district or specific hotel', 'bookingcom-official-searchbox' ) . '" value="' . esc_attr($bos_mb_destination) . '" readonly="readonly" style="' . esc_attr($dest_bgcolor) . esc_attr($dest_textcolor) . esc_attr($dest_border_radius) . '" />';
                                    echo '</span>';
                                    echo '<input id="b_dest_type" type="hidden" name="dest_type" value="' . esc_attr($bos_mb_dest_type) . '" />';
                                    echo '<input id="b_dest_id" type="hidden" name="dest_id" value="' . esc_attr($bos_mb_dest_id) . '" />';
                                } // !empty( $bos_mb_dest_type ) && !empty( $bos_mb_dest_id )  
                                else {
                                    echo '<input type="text" id="b_destination" class="b_destination'.$light_placeholder.'" name="ss" placeholder="' . esc_html__( 'e.g. city, region, district or specific hotel', 'bookingcom-official-searchbox' ) . '" value="' . esc_attr($bos_mb_destination) . '" style="' . esc_attr($dest_bgcolor) . esc_attr($dest_textcolor) . esc_attr($dest_border_radius) . '" autocomplete="off" />';
                                }
                            } //!empty( $bos_mb_destination )
                            else if ( !empty( $args['destination'] ) ) {
                                if ( $args['dest_type'] != BOS_DEST_TYPE && !empty( $args['dest_id'] ) ) { // Set destination type and id if exists from settings
                                    echo '<span class="b_dest_wrap"><input type="text" id="b_destination"  class="b_destination'.$light_placeholder.'" name="ss" value="' . esc_attr($args['destination']) . '" placeholder="' . esc_html__( 'e.g. city, region, district or specific hotel', 'bookingcom-official-searchbox' ) . '" readonly="readonly" style="' . esc_attr($dest_bgcolor) . esc_attr($dest_textcolor) . esc_attr($dest_border_radius) . '" />';
                                    echo '</span>';
                                    echo '<input id="b_dest_type" type="hidden" name="dest_type" value="' . esc_attr($args['dest_type']) . '" />';
                                    echo '<input id="b_dest_id" type="hidden" name="dest_id" value="' . esc_attr($args['dest_id']) . '" />';
                                } //$dest_type != BOS_DEST_TYPE && !empty( $dest_id )
                                else {
                                    echo '<input type="text" id="b_destination" class="b_destination'.$light_placeholder.'" name="ss" placeholder="' . esc_html__( 'e.g. city, region, district or specific hotel', 'bookingcom-official-searchbox' ) . '" value="' . esc_attr($args['destination']) . '" style="' . esc_attr($dest_bgcolor) . esc_attr($dest_textcolor) . esc_attr($dest_border_radius) . '" autocomplete="off" />';
                                }
                            } //!empty( $destination )
                            else {
                                echo '<input type="text" id="b_destination" class="b_destination'.$light_placeholder.'" name="ss" placeholder="' . esc_html__( 'e.g. city, region, district or specific hotel', 'bookingcom-official-searchbox' ) . '" title="' . esc_html__( 'e.g. city, region, district or specific hotel', 'bookingcom-official-searchbox' ) . '" style="' . esc_attr($dest_bgcolor) . esc_attr($dest_textcolor) . esc_attr($dest_border_radius) . '" autocomplete="off" />';
                            }
                            ?>
                            
                        </div><!-- #b_searchDest -->

                        <div id="searchBox_dates_error_msg" class="b_error b_external_searchbox" style="display: none ;"></div>

                        <div class="b_aff-dates">
                        <?php

                        echo bos_dateSelector( $args['checkin'], $args['checkout'], $args['date_bgcolor'], $args['date_textcolor'], $args['fields_border_radius'] );
                        ?>
                        </div>

                        <div class="b_avail">
                            <input type="hidden" value="on" name="do_availability_check" />
                        </div><!-- .b_submitButton_wrapper-->
                        
                        
                        <?php
                        if ( $args['flexible_dates'] ) {
                        ?>
                                            
                        <div id="b_flexible_dates">
                            <label class="b_checkbox_container">
                                <input type="checkbox" name="idf" id="b_idf"/>
                                <span style="<?php echo $args['flexdate_textcolor'] ? 'color:' . esc_attr($args['flexdate_textcolor']) . ';' : '';?>">
                                    <?php esc_html_e( ' I don\'t have specific dates yet ', 'bookingcom-official-searchbox' );?>
                                </span>
                            </label>
                        </div>
                        <?php
                        } //$flexible_dates
                        ?>
                        
                        <div class="b_submitButton_wrapper" style="<?php echo $args['buttonpos'] ? 'text-align:' . esc_attr($args['buttonpos']) : '';?>">
                            <input 
                                class="b_submitButton" 
                                type="submit" 
                                value="<?php echo esc_attr($args['submit']);?>" 
                                style="<?php
                                    echo $args['submit_bgcolor'] ? 'background-color:' . esc_attr($args['submit_bgcolor']) . ';' : '';
                                    echo $args['submit_textcolor'] ? 'color:' . esc_attr($args['submit_textcolor']) . ';' : '';
                                    echo $args['submit_bordercolor'] ? 'border-color:' . esc_attr($args['submit_bordercolor']) . ';' : '';
                                    echo $args['fields_border_radius'] ? 'border-radius:' . esc_attr($args['fields_border_radius']) . 'px;' : '';?>"
                            />
                        </div><!-- .b_submitButton_wrapper-->
                    
                        <?php 
                        if ( $args['logo_enabled'] ) {
                        ?>
                        <div id="b_logo" <?php echo $args['logopos'] ? 'style="text-align:' . esc_attr($args['logopos']) . ';"' : '';?>>
                            <img width="<?php echo esc_attr(substr($args['logodim'], 5, -3)); ?>" src="<?php echo esc_attr(BOS_PLUGIN_ASSETS) . '/images/booking_logo_' . esc_attr($args['logodim']) . '.png';?>" alt="Booking.com">
                        </div>                
                        <!-- #b_logo" -->   
                        <?php
                        } //$logo_enabled
                        ?>                     
                    </div><!-- #b_frmInner -->
                </form>
            </div><!-- #b_searchboxInc -->
        </div><!-- #flexi_searchbox -->
        <?php
    }

endif;