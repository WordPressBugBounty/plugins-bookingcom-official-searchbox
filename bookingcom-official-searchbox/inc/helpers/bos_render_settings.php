<?php

/**
 * Render setting field per tab
 * 
 * @since 3.0.0
 *
 * @package Booking Official Searchbox
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'custom_do_settings_sections' ) ) {

  function custom_do_settings_sections( $page ) {
    global $wp_settings_sections, $wp_settings_fields;
  
    if ( ! isset( $wp_settings_sections[ $page ] ) ) {
      return;
    }
  
    foreach ( (array) $wp_settings_sections[ $page ] as $section ) {
      if ( '' !== $section['before_section'] ) {
        if ( '' !== $section['section_class'] ) {
          echo wp_kses_post( sprintf( $section['before_section'], esc_attr( $section['section_class'] ) ) );
        } else {
          echo wp_kses_post( $section['before_section'] );
        }
      }
  
      if ( $section['title'] ) {
        echo "<h2>{$section['title']}</h2>\n";
      }
  
      if ( $section['callback'] ) {
        call_user_func( $section['callback'], $section );
      }
  
      if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
        continue;
      }
      echo '<table class="form-table" role="presentation">';
      custom_do_settings_fields( $page, $section['id'] );
      echo '</table>';
  
      if ( '' !== $section['after_section'] ) {
        echo wp_kses_post( $section['after_section'] );
      }
    }
  }

}

if ( ! function_exists( 'custom_do_settings_fields' ) ) :

  function custom_do_settings_fields( $page, $section ) {
    global $wp_settings_fields;

    if ( !isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section]) )
      return;

    $group_name = '';

    foreach ( (array) $wp_settings_fields[$page][$section] as $field ) {
      $class = '';

      if ( ! empty( $field['args']['class'] ) ) {
        $class = ' class="' . esc_attr( $field['args']['class'] ) . '"';
      }

      if (isset($field['args'][9]) && !empty($field['args'][9])) {
        if (isset($group_name) && $group_name !== $field['args'][9]) {
            echo '<tr class="bos-group-title-row"><td><h3>' . esc_attr($field['args'][9]) . '</h3></td></tr>';
            $group_name = esc_attr($field['args'][9]);
        }
      }

      echo "<tr{$class}>";

      if ( !empty($field['args']['label_for']) ) {
        echo '<th scope="row"><label for="' . $field['args']['label_for'] . '">' . $field['title'] . '</label></th>';
      } else {
        echo '<th scope="row">' . $field['title'] . '</th>';
      }

      echo '<td>';

        call_user_func($field['callback'], $field['args']);

      echo '</td>';

      echo '</tr>';

    }

  }

endif;