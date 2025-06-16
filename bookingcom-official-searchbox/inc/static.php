<?php
/**
 * Frontend functions.
 *
 * @package Booking Official Searchbox
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'bos_register_scripts' ) ) :

	/**
	 * Register theme styles and scripts.
	 *
	 * @since 2.2.4
	 */
	function bos_register_scripts() {

        $register_styles = [
			'bos-searchbox' => [
				'src' => BOS_PLUGIN_ASSETS . '/css/bos_searchbox.css',
			],
            'bos-settings' => [
                'src' => BOS_PLUGIN_ASSETS . '/css/bos_settings.css',
            ],
            'jquery-ui' => [
                'src' => BOS_PLUGIN_ASSETS . '/css/jquery-ui.css',
            ],
            'bos-date-range-picker-style' => [
                'src' => BOS_PLUGIN_ASSETS . '/css/daterangepicker.css',
            ],
            'bos-dynamic_style' => [
                'src' => BOS_PLUGIN_ASSETS . '/css/bos_dynamic.css',
            ]
		];

		foreach ( $register_styles as $name => $props ) {
			bos_register_style( $name, $props['src'] );
		}

        $register_scripts = [
			'bos-main' => [
				'src'       => BOS_PLUGIN_ASSETS . '/js/bos_main.js',
				'deps'      => [ 'jquery' ],
				'in_footer' => true,
			],
            'bos-date-range-picker' => [
                'src'       => BOS_PLUGIN_ASSETS . '/js/daterangepicker.js',
                'deps'      => [ 'jquery' ],
                'in_footer' => true
            ],
			'bos-date' => [
				'src'       => BOS_PLUGIN_ASSETS . '/js/bos_date.js',
				'deps'      => [ 'jquery' ],
				'in_footer' => true,
			],
			'bos-general' => [
				'src'       => BOS_PLUGIN_ASSETS . '/js/bos_general.js',
				'deps'      => [ 'jquery' ],
				'in_footer' => true,
			],
            'bos-moment' => [
                'src'       => BOS_PLUGIN_ASSETS . '/js/moment-with-locales.min.js',
                'deps'      => [],
                'in_footer' => true
            ]
		];

		foreach ( $register_scripts as $name => $props ) {
			bos_register_script( $name, $props['src'], $props['deps'], false, $props['in_footer'] );
		}

    }

endif;

if ( ! function_exists( 'bos_enqueue_scripts' ) ) :

    function bos_enqueue_scripts() {

        bos_register_scripts();

        dynamic_bos_css();

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'bos-searchbox' );
        if (is_admin()) {
            wp_enqueue_style( 'bos-settings' );
        }
        wp_enqueue_style( 'jquery-ui' );
        wp_enqueue_style( 'bos-date-range-picker-style' );
        wp_enqueue_style( 'bos-dynamic_style' );

        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'bos-moment' );
        wp_enqueue_script( 'bos-main' );
        wp_enqueue_script( 'bos-date-range-picker' );
        wp_enqueue_script( 'bos-date' );
        if (is_admin()) {
            wp_enqueue_script( 'bos-general' );
        }
        wp_enqueue_script( 'wp-color-picker' );

        $options = bos_searchbox_retrieve_all_user_options();

        wp_localize_script( 'bos-date', 'objectL10n', array(
            'destinationErrorMsg' => esc_html__( 'Sorry, we need at least part of the name to start searching.', 'bookingcom-official-searchbox' ),
            'updating' => esc_html__( 'Updating...', 'bookingcom-official-searchbox' ),
            'close' => esc_html__( 'Close', 'bookingcom-official-searchbox' ),
            'placeholder' => esc_html__( 'e.g. city, region, district or specific hotel', 'bookingcom-official-searchbox' ),
            'noSpecificDate' => esc_html__( ' I don\'t have specific dates yet ', 'bookingcom-official-searchbox' ),
            'language' => get_locale(),
            // following values are when reset to default values is triggered
            'main_title' => __( 'Search hotels and more...', 'bookingcom-official-searchbox' ),
            'dest_title' => __( 'Destination', 'bookingcom-official-searchbox' ),
            'checkin_title' => __( 'Check-in date', 'bookingcom-official-searchbox' ),
            'checkout_title' => __( 'Check-out date', 'bookingcom-official-searchbox' ),
            'submit_title' => __( 'Search', 'bookingcom-official-searchbox' ),
            'aid' => BOS_DEFAULT_AID,
            'dest_type' => BOS_DEST_TYPE,
            'flexible_dates' => BOS_FLEXIBLE_DATES,
            'logo_enabled' =>  BOS_LOGO_ENABLED,
            'logodim' => BOS_LOGODIM,
            'logopos' => BOS_LOGOPOS,
            'fields_border_radius' => BOS_FIELDS_BORDER_RADIUS,
            'sb_border_radius' => BOS_SB_BORDER_RADIUS,
            //'prot' => BOS_PROTOCOL,
            'buttonpos' => BOS_BUTTONPOS,
            //'sticky' => BOS_STICKY,
            'selected_datecolor' => BOS_SELECTED_DATE_COLOR,
            'bgcolor' => BOS_BGCOLOR,
            'dest_bgcolor' => BOS_DEST_BGCOLOR,
            'dest_textcolor' => BOS_DEST_TEXTCOLOR,
            'headline_textsize' => BOS_HEADLINE_SIZE,
            'headline_textcolor' => BOS_HEADLINE_TEXTCOLOR,
            'textcolor' => BOS_TEXTCOLOR,
            'flexdate_textcolor' => BOS_FLEXDATE_TEXTCOLOR,
            'date_textcolor' => BOS_DATE_TEXTCOLOR,
            'date_bgcolor' => BOS_DATES_BGCOLOR,
            'submit_bgcolor' => BOS_SUBMIT_BGCOLOR,
            'submit_bordercolor' => BOS_SUBMIT_BORDERCOLOR,
            'submit_textcolor' => BOS_SUBMIT_TEXTCOLOR,
            'is_light_color' => !empty($options[ 'dest_textcolor' ]) && wc_hex_is_light($options[ 'dest_textcolor' ]),
            'show_weeknumbers' => BOS_SHOW_WEEKNUMBERS,
            'calendar_selected_bgcolor' => !empty( $options[ 'calendar_selected_bgcolor' ] ) ? $options[ 'calendar_selected_bgcolor' ] : BOS_CALENDAR_SELECTED_DATE_BGCOLOR,
            'calendar_selected_textcolor' => !empty( $options[ 'calendar_selected_textcolor' ] ) ? $options[ 'calendar_selected_textcolor' ] : BOS_CALENDAR_SELECTED_DATE_TEXTCOLOR,
            'calendar_daynames_color' => !empty( $options[ 'calendar_daynames_color' ] ) ? $options[ 'calendar_daynames_color' ] : BOS_CALENDAR_DAYNAMES_COLOR,
            'aid_starts_with_four' => esc_html__( 'Affiliate ID is different from partner ID: should start with a 1, 3, 8 or 9. Please change it.', 'bookingcom-official-searchbox' ),
            //set the path for javascript files
            // 'destination' => get_post_meta();
            'images_js_path' => BOS_PLUGIN_ASSETS . '/images', //path for images to be called from javascript   
            'target_path' => !empty( $options[ 'target_page' ] ) ? $options[ 'target_page' ] : BOS_TARGET_PAGE,
            "domain"      => !empty( $options[ 'cname' ] ) ? '//' . $options[ 'cname' ] . '/' : BOS_DEFAULT_DOMAIN,
            "settings" => get_option( 'bos_searchbox_user_options' )
        ) );

        // do_action('wp_enqueue_scripts')
    }

endif;

add_action( 'wp_enqueue_scripts', 'bos_enqueue_scripts', 15 );
add_action( 'admin_enqueue_scripts', 'bos_enqueue_scripts');