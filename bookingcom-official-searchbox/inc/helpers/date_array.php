<?php
/**
 * Dates helpers.
 * @package Booking Official Searchbox\Helpers
 * @since 2.2.4
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'bos_dateSelector' ) ) :

    function bos_dateSelector( $checkin, $checkout, $date_bgcolor, $date_textcolor, $fields_border_radius ) {

        /* create all variables */
        /* Detect language */
        $wp_system_language = get_locale();

        $checkin      = $checkin ? $checkin : esc_html__( 'Check-in date', 'bookingcom-official-searchbox' );
        $checkout     = $checkout ? $checkout : esc_html____( 'Check-out date', 'bookingcom-official-searchbox' );
        $date_textcolor    = $date_textcolor ? 'color:' . esc_attr($date_textcolor) . ';' : 'color: #003580;';
        $date_bgcolor = $date_bgcolor ? 'background:' . esc_attr($date_bgcolor) . ';' : '';
        $date_border_radius = $fields_border_radius ? "border-radius:" . esc_attr($fields_border_radius) . "px;" : "";
        
        echo '<div id="b_dates" class="bos-dates__col '. esc_attr($wp_system_language) .'" style="' . esc_attr($date_bgcolor) . esc_attr($date_border_radius) . '">';

            echo '<div class="b_dates_inner_wrapper">';

                echo '<h4 id="checkInDate_h4" style="' . esc_attr($date_textcolor) . '">' . esc_html($checkin) . '</h4>';

                echo '<div class="bos-date-field__display bos-date__checkin" id="bos-date_b_checkin" style="' . esc_attr($date_textcolor) . '"></div>';

                echo '<input type="hidden" name="checkin" value="" id="b_checkin">';

            echo '</div>';

            echo '<div class="b_dates_inner_wrapper">';

                echo '<h4 id="checkOutDate_h4" style="' . esc_attr($date_textcolor) . '">' . esc_html($checkout) . '</h4>';

                echo '<div class="bos-date-field__display bos-date__checkout" id="bos-date_b_checkout" style="' . esc_attr($date_textcolor) . '"></div>';

                echo '<input type="hidden" name="checkout" value="" id="b_checkout">';

            echo '</div>';

        echo '</div>';
    }

endif;