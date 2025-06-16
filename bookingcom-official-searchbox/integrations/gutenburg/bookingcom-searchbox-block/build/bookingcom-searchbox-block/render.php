<?php
  $bos_default_aid = 382821;
  $options = get_option( 'bos_searchbox_user_options' );
  if ( is_page() || is_single() ) {
    global $wp_query;
    $postid             = $wp_query->post->ID;
    $bos_mb_destination = get_post_meta( $postid, '_bos_mb_destination', true );
    $bos_mb_dest_type   = get_post_meta( $postid, '_bos_mb_dest_type', true );
    $bos_mb_dest_id     = get_post_meta( $postid, '_bos_mb_dest_id', true );
    wp_reset_query();
  }

  $main_title = (!empty($attributes['sbTitle']) ? $attributes['sbTitle'] : (!empty($options['maintitle']) ? $options['maintitle'] : __( 'Search hotels and more...', 'bookingcom-official-searchbox' )));
  $dest_title = (!empty($attributes['destTitle']) ? $attributes['destTitle'] : (!empty($options['dest_title']) ? $options['dest_title'] : __( 'Destination', 'bookingcom-official-searchbox' )));
  $checkin_title = (!empty($attributes['checkinTitle']) ? $attributes['checkinTitle'] : (!empty($options['checkin']) ? $options['checkin'] :__( 'Check-in date', 'bookingcom-official-searchbox' )));
  $checkout_title = (!empty($attributes['checkoutTitle']) ? $attributes['checkoutTitle'] : (!empty($options['checkout']) ? $options['checkout'] :__( 'Check-out date', 'bookingcom-official-searchbox' )));
  $submit_title = (!empty($attributes['submitTitle']) ? $attributes['submitTitle'] : (!empty($options['submit']) ? $options['submit'] :__( 'Search', 'bookingcom-official-searchbox' )));
  $aid_val = (!empty($attributes['aid']) ? $attributes['aid'] : (!empty($options['aid']) ? $options['aid'] : BOS_DEFAULT_AID));
  $width_val = (!empty($attributes['width']) ? 'width:' . $attributes['width'] : (!empty($options['widget_width']) ? 'width:' . $options['widget_width'] : ''));
  $cname_val = !empty($options['cname']) ? $options['cname'] : '';
  $flex_dates_val = (!empty($attributes['flexDates']) ? $attributes['flexDates'] : (!empty($options['flexible_dates']) ? $options['flexible_dates'] : BOS_FLEXIBLE_DATES));
  $headline_size_val = (!empty($attributes['headLineSize']) ? 'font-size:'.$attributes['headLineSize'] : (!empty($options['headline_textsize']) ? 'font-size:'.$options['headline_textsize'] : 'font-size:'.BOS_HEADLINE_SIZE));
  $sb_bg_val = (!empty($attributes['sbBgColor']) ? 'background-color:' . $attributes['sbBgColor'] : (!empty($options['bgcolor']) ? 'background-color:' . $options['bgcolor'] : 'background-color:'.BOS_BGCOLOR));
  $text_color_val = (!empty($attributes['textColor']) ? 'color:'.$attributes['textColor'] : (!empty($options['textcolor']) ? 'color:'.$options['textcolor'] : 'color:'.BOS_TEXTCOLOR));
  $headline_text_color_val = (!empty($attributes['headlineTextColor']) ? 'color:'.$attributes['headlineTextColor'] : (!empty($options['headline_textcolor']) ? 'color:'.$options['headline_textcolor'] : 'color:'.BOS_HEADLINE_TEXTCOLOR));
  $dest_bg_color_val = (!empty($dest_bg_color_val) ? 'background-color:'.$dest_bg_color_val : (!empty($options['dest_bgcolor']) ? 'background-color:'.$options['dest_bgcolor'] : 'background-color:'.BOS_DEST_BGCOLOR));
  $dest_text_color_val = (!empty($dest_text_color_val) ? 'color:'.$dest_text_color_val : (!empty($options['dest_textcolor']) ? 'color:'.$options['dest_textcolor'] : 'color:'.BOS_DEST_TEXTCOLOR));
  $date_bg_val = (!empty($attributes['dateBgColor']) ? 'background-color:'.$attributes['dateBgColor'] : (!empty($options['date_bgcolor']) ? 'background-color:'.$options['date_bgcolor'] : 'background-color:'.BOS_DATES_BGCOLOR));
  $date_text_color_val = (!empty($date_text_color_val) ? 'color:'.$date_text_color_val : (!empty($options['date_textcolor']) ? 'color:'.$options['date_textcolor'] : 'color:'.BOS_DATE_TEXTCOLOR));
  $flex_dates_color_val = (!empty($attributes['flexDateTextColor']) ? 'color:'.$attributes['flexDateTextColor'] : (!empty($options['flexdate_textcolor']) ? 'color:'.$options['flexdate_textcolor'] : 'color:'.BOS_FLEXDATE_TEXTCOLOR));
  $cta_bg_val = (!empty($attributes['ctaBgColor']) ? 'background-color:'.$attributes['ctaBgColor'] : (!empty($options['submit_bgcolor']) ? 'background-color:'.$options['submit_bgcolor'] : 'background-color:'.BOS_SUBMIT_BGCOLOR));
  $cta_text_color_val = (!empty($attributes['ctaTextColor']) ? 'color:'.$attributes['ctaTextColor'] : (!empty($options['submit_textcolor']) ? 'color:'.$options['submit_textcolor'] : 'color:'.BOS_SUBMIT_TEXTCOLOR));
  $cta_border_color_val = (!empty($attributes['ctaBorderColor']) ? 'border-color:'.$attributes['ctaBorderColor'] : (!empty($options['submit_bordercolor']) ? 'border-color:'.$options['submit_bordercolor'] : 'border-color:'.BOS_SUBMIT_BORDERCOLOR));
  $field_radius_val = (!empty($field_radius_val) ? 'border-radius:'.$field_radius_val : (!empty($options['fields_border_radius']) ? 'border-radius:'.$options['fields_border_radius'] : BOS_FIELDS_BORDER_RADIUS));
  $sb_border_radius_val = (!empty($attributes['sbBorderRadius']) ? 'border-radius:'.$attributes['sbBorderRadius'] : (!empty($options['sb_border_radius']) ? 'border-radius:'.$options['sb_border_radius'] : BOS_SB_BORDER_RADIUS));
  $submit_pos_val = (!empty($attributes['submitPos']) ? 'text-align:'.$attributes['submitPos'] : (!empty($options['buttonpos']) ? 'text-align:'.$options['buttonpos'] : 'text-align:'.BOS_BUTTONPOS));
  $logo_pos_val = (!empty($attributes['logoPos']) ? 'text-align:'.$attributes['logoPos'] : (!empty($options['logopos']) ? 'text-align:'.$options['logopos'] : 'text-align:'.BOS_LOGOPOS));
  $logo_enabled_val = (!empty($attributes['enableLogo']) ? $attributes['enableLogo'] : (!empty($options['logo_enabled']) ? $options['logo_enabled'] : BOS_LOGO_ENABLED));
  $logo_dim_val = (!empty($attributes['logoDim']) ? $attributes['logoDim'] : (!empty($options['logodim']) ? $options['logodim'] : BOS_LOGODIM));
  $dest_val = (!empty($attributes['destination']) ? $attributes['destination'] : (!empty($options['destination']) ? $options['destination'] : ''));
  $dest_type_val = (!empty($attributes['dest_type']) ? $attributes['dest_type'] : (!empty($options['dest_type']) ? $options['dest_type'] : 'select'));
  $dest_id_val = (!empty($attributes['dest_id']) ? $attributes['dest_id'] : (!empty($options['dest_id']) ? $options['dest_id'] : ''));
  $domain_val = !empty($options['cname']) ? 'https://' . $options['cname'] . '/' : BOS_DEFAULT_DOMAIN;
  $target_page_val = BOS_TARGET_PAGE;
?>
<div <?php echo get_block_wrapper_attributes([ 'class' => 'flexi_searchbox' ]); ?> style="<?php echo esc_attr($sb_bg_val); ?>; <?php echo esc_attr($text_color_val);?>;<?php echo esc_attr($sb_border_radius_val); ?>px; <?php echo esc_attr($width_val); ?>">
  <div class="b_searchboxInc">
    <h2 style="<?php echo esc_attr($headline_text_color_val);?>;<?php echo esc_attr($headline_size_val); ?>px;"><?php echo esc_html_e($main_title);?></h2>
    <form action="<?php echo esc_attr($domain_val) . esc_attr($target_page_val); ?>" method="get" target="_blank" role="search">
      <div class="b_error b_external_searchbox" style="display: none;"></div>
      <div class="b_frmInner">                        
        <input type="hidden" name="si" value="ai,co,ci,re,di" />
        <input type="hidden" name="utm_campaign" value="search_box" /> 
        <input type="hidden" name="utm_medium" value="sp" />
        <?php if (empty($cname_val) || ( !empty( $cname_val ) && !empty($aid_val) && $aid_val != $bos_default_aid )) { ?>

          <input type="hidden" name="aid" value="<?php echo esc_attr($aid_val); ?>" />
          <input type="hidden" name="label" value="wp-searchbox-widget-<?php echo esc_attr($aid_val); ?>" />
          <input type="hidden" name="utm_term" value="wp-searchbox-widget-<?php echo esc_attr($aid_val); ?>" />
          <input type="hidden" name="error_url" value="<?php echo esc_attr($domain_val) . esc_attr($target_page_val) . '?aid=' . esc_attr($aid_val); ?>" />

        <?php } elseif ( !empty( $cname_val )) { ?>

          <input type="hidden" name="ifl" value="1" />';
          <input type="hidden" name="label" value="wp-searchbox-widget-<?php echo esc_attr($cname_val); ?>" />
          <input type="hidden" name="utm_term" value="wp-searchbox-widget-<?php echo esc_attr($cname_val); ?>" />
          <input type="hidden" name="error_url" value="<?php echo esc_attr($domain_val) . esc_attr($target_page_val); ?>" />

        <?php } else { ?>

					<input type="hidden" name="label" value="wp-searchbox-widget-<?php echo esc_attr($aid_val); ?>" />

        <?php } ?>
        <div class="b_searchDest">
          <h3 style="<?php echo esc_attr($text_color_val);?>;"><?php echo esc_html($dest_title);?></h3>
          <?php if (!empty($bos_mb_destination)) {
            if ( $bos_mb_dest_type != 'select' && !empty( $bos_mb_dest_id ) ) { ?>
              <span class="b_dest_wrap">
                <input type="text" class="b_destination" name="ss" value="<?php echo esc_attr($bos_mb_destination); ?>" placeholder="e.g. city, region, district or specific hotel" readonly="readonly" style="<?php echo esc_attr($dest_bg_color_val); ?>; <?php echo esc_attr($dest_text_color_val);?>; <?php echo esc_attr($field_radius_val);?>px;" autocomplete="off" />
              </span>
              <input class="b_dest_type" type="hidden" name="dest_type" value="<?php echo esc_attr($bos_mb_dest_type); ?>" />
              <input class="b_dest_id" type="hidden" name="dest_id" value="<?php echo esc_attr($bos_mb_dest_id); ?>" />
          <?php } else { ?>
            <input type="text" class="b_destination" name="ss" value="<?php echo esc_attr($bos_mb_destination); ?>" placeholder="e.g. city, region, district or specific hotel" style="<?php echo esc_attr($dest_bg_color_val); ?>; <?php echo esc_attr($dest_text_color_val);?>; <?php echo esc_attr($field_radius_val);?>px;" autocomplete="off" />
          <?php }
          } elseif (!empty($dest_val)) { 
            if (!empty($dest_type_val) && !empty($dest_id_val)) { ?>
              <span class="b_dest_wrap">
                <input type="text" class="b_destination" name="ss" value="<?php echo esc_attr($dest_val); ?>" readonly="readonly" style="<?php echo esc_attr($dest_bg_color_val); ?>; <?php echo esc_attr($dest_text_color_val);?>; <?php echo esc_attr($field_radius_val);?>px;" autocomplete="off" />
              </span>
              <input class="b_dest_type" type="hidden" name="dest_type" value="<?php echo esc_attr($dest_type_val); ?>" />
              <input class="b_dest_id" type="hidden" name="dest_id" value="<?php echo esc_attr($dest_id_val); ?>" />
            <?php } else { ?>
              <input type="text" class="b_destination" name="ss" value="<?php echo esc_attr($dest_val); ?>" style="<?php echo esc_attr($dest_bg_color_val); ?>; <?php echo esc_attr($dest_text_color_val);?>; <?php echo esc_attr($field_radius_val);?>px;" autocomplete="off" />
            <?php } ?>
          <?php } else { ?>
            <input type="text" class="b_destination" name="ss" placeholder="e.g. city, region, district or specific hotel" title="e.g. city, region, district or specific hotel" style="<?php echo esc_attr($dest_bg_color_val); ?>; <?php echo esc_attr($dest_text_color_val);?>; <?php echo esc_attr($field_radius_val);?>px;" autocomplete="off" />
          <?php } ?>
        </div>

        <div class="b_aff-dates">
          <div class="b_dates bos-dates__col" style="<?php echo esc_attr($date_bg_val);?>; <?php echo esc_attr($field_radius_val);?>px;">

            <div class="b_dates_inner_wrapper">

              <h4 class="checkInDate_h4" style="<?php echo esc_attr($date_text_color_val);?>"><?php echo esc_attr($checkin_title);?></h4>

              <div class="bos-date-field__display bos-date__checkin bos-date_b_checkin" style="<?php echo esc_attr($date_text_color_val);?>"></div>

              <input type="hidden" name="checkin" value="" class="b_checkin" />

            </div>

            <div class="b_dates_inner_wrapper">

              <h4 class="checkOutDate_h4" style="<?php echo esc_attr($date_text_color_val);?>"><?php echo esc_attr($checkout_title);?></h4>

              <div class="bos-date-field__display bos-date__checkout bos-date_b_checkout" style="<?php echo esc_attr($date_text_color_val);?>"></div>

              <input type="hidden" name="checkout" value="" class="b_checkout" />

            </div>

          </div>
        </div>
        <div class="b_avail">
          <input type="hidden" value="on" name="do_availability_check" />
        </div>

        <?php
        if ( $flex_dates_val ) {
        ?>
                            
        <div class="b_flexible_dates">
            <label class="b_checkbox_container">
                <input type="checkbox" name="idf" class="b_idf"/>
                <span style="<?php echo esc_attr($flex_dates_color_val);?>">
                    <?php esc_html_e( ' I don\'t have specific dates yet ', 'bookingcom-official-searchbox' );?>
                </span>
            </label>
        </div>
        <?php
        } //$flexible_dates
        ?>

        <div class="b_submitButton_wrapper" style="<?php echo esc_attr($submit_pos_val); ?>">
          <input 
            class="b_submitButton" 
            type="submit"
            value="<?php echo esc_html($submit_title);?>"
            style="<?php echo esc_attr($cta_bg_val);?>; <?php echo esc_attr($cta_text_color_val); ?>; <?php echo esc_attr($cta_border_color_val);?>; <?php echo esc_html($field_radius_val);?>px;"
          />
        </div>
        <?php if (isset($logo_enabled_val) && $logo_enabled_val) { ?>
        <div class="b_logo" style="<?php echo esc_html($logo_pos_val); ?>">
          <img width="<?php echo esc_attr(substr($logo_dim_val, 5, -3)); ?>" src="<?php echo esc_attr(BOS_PLUGIN_ASSETS) . '/images/booking_logo_' . esc_attr($logo_dim_val) . '.png';?>" alt="Booking.com" />
        </div>
        <?php } ?>
      </div>
    </form>
  </div>
</div>