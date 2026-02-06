(function($) {
    $(function() {

        // Check if affiliate placed partner's ID instead of affiliate ID
        if ($('#aid').length && $('#aid').val()[0] === '4') {
            alert(objectL10n.aid_starts_with_four);
            $('#aid').focus();
        }

        // Setup a click handler to initiate the Ajax request and handle the response
        $('#preview_button').click(function() {
            $(this).data('clicked', true);

            var ajax_loader = '<div id=\"bos_ajax_loader\"><h3>' + objectL10n.updating +
                '</h3>';
            /*ajax_loader = ajax_loader + '<img src=\"' ;
                ajax_loader = ajax_loader + objectL10n.images_js_path ;
                ajax_loader = ajax_loader + '\/ajax-loader.gif">' ;*/
            ajax_loader = ajax_loader + '</div>';
            $('#bos_preview').append(ajax_loader);
            $('#flexi_searchbox').css('opacity', '0.5');

            var checkin = moment();
            var checkout = moment(checkin).add(1, 'd');
            var language = objectL10n.language;
            moment.locale(language);

            var data = {

                action: 'bos_preview', // The function for handling the request
                nonce: $('#bos_ajax_nonce').text(), // The security nonce
                aid: $('#aid').val(), // bgcolor
                destination: $('#destination').val(), // destination
                dest_id: $('#dest_id').val(),
                dest_type: $('#dest_type').val(),
                widget_width: $('#widget_width').val(), // widget_width
                flexible_dates: $('#flexible_dates:checked').val(), // flexible dates
                logo_enabled: $('#logo_enabled:checked').val(), // logo_enabled
                logodim: $('.logodim:checked').val(), // logodim
                logopos: $('#logopos').val(), // logopos 
                fields_border_radius: $('#fields_border_radius').val(), // fields_border_radius
                sb_border_radius: $('#sb_border_radius').val(), // sb_border_radius
                preset_checkin_date: $('#preset_checkin_date').val(), // preset_checkin_date 
                preset_checkout_date: $('#preset_checkout_date').val(), // preset_checkout_date  
                buttonpos: $('#buttonpos').val(), // buttonpos  
                bgcolor: $('#bgcolor').val(), // bgcolor
                dest_bgcolor: $('#dest_bgcolor').val(), // dest bgcolor
                dest_textcolor: $('#dest_textcolor').val(), // dest textcolor
                headline_textsize: $('#headline_textsize').val(), // headline text size
                headline_textcolor: $('#headline_textcolor').val(), // headline textcolor
                textcolor: $('#textcolor').val(), // textcolor
                date_textcolor: $('#date_textcolor').val(), // date textcolor
                date_bgcolor: $('#date_bgcolor').val(), // date bgcolor
                flexdate_textcolor: $('#flexdate_textcolor').val(), // flexdate textcolor
                submit_bgcolor: $('#submit_bgcolor').val(), // submit_bgcolor
                submit_bordercolor: $('#submit_bordercolor').val(), // submit_bordercolor
                submit_textcolor: $('#submit_textcolor').val(), // submit_textcolor
                calendar_selected_bgcolor: $('#calendar_selected_bgcolor').val(), // calendar selected bgcolor
                calendar_selected_textcolor: $('#calendar_selected_textcolor').val(), // calendar selected textcolor
                calendar_daynames_color: $('#calendar_daynames_color').val(), // calendar daynames color
                maintitle: $('#maintitle').val(), // maintitle
                dest_title: $('#dest_title').val(), // destination  
                checkin: $('#checkin').val(), // checkin
                checkout: $('#checkout').val(), // checkout
                show_weeknumbers: $('#show_weeknumbers:checked').val(), // show week numbers
                submit: $('#submit').val() // submit                

            };

            $.post(ajaxurl, data, function(response) {

                $('#bos_preview').html(response);
                $('#flexi_searchbox').css('opacity', '1');
                var dateFormat = '';
                if (language === 'de_DE') {
                    dateFormat = 'ddd D. MMM YYYY';
                } else {
                    dateFormat = 'ddd D MMM YYYY';
                }
                $('.b_dates_inner_wrapper div#bos-date_b_checkin').html(checkin.format(dateFormat));
                $('#b_checkin').val(checkin.format('YYYY-MM-DD'));
                $('.b_dates_inner_wrapper div#bos-date_b_checkout').html(checkout.format(dateFormat));
                $('#b_checkout').val(checkout.format('YYYY-MM-DD'));
                $('#bos_ajax_loader').empty();

                function cb(checkin, checkout) {
                    var dateFormat = '';
                    if (language === 'de_DE') {
                        dateFormat = 'ddd D. MMM YYYY';
                    } else {
                        dateFormat = 'ddd D MMM YYYY';
                    }
                    $('.b_dates_inner_wrapper div#bos-date_b_checkin').html(checkin.format(dateFormat));
                    $('#b_checkin').val(checkin.format('YYYY-MM-DD'));
                    $('.b_dates_inner_wrapper div#bos-date_b_checkout').html(checkout.format(dateFormat));
                    $('#b_checkout').val(checkout.format('YYYY-MM-DD'));
                }
            
                $('#b_dates').daterangepicker({
                    singleDatePicker: false,
                    startDate: checkin,
                    endDate: checkout,
                    minDate: moment(),
                    autoApply: true,
                    opens: "center",
                    showWeekNumbers: (data.show_weeknumbers === 'on' || objectL10n.show_weeknumbers == 1) ? true : false,
                    maxSpan: {
                        days: 30
                    }
                }, cb).on('show.daterangepicker', function (ev, picker) {
                    picker.container.addClass('bos-css');                            
                });
            
                cb(checkin, checkout);
            });


        }); // $('#preview_button').click( function()


        // Setup a click handler to initiate the reset values button
        $('#reset_default').click(function() {

            //alert( 'values reset' );
            // Set all values to default values

            $('#aid').val(objectL10n.aid);
            $('#destination').val('');
            $('#dest_id').val('');
            $('#dest_type').val(objectL10n.dest_type);
            $('#cname').val('');
            $('#display_in_custom_post_types').val('');
            $('#widget_width').val('');
            $('#flexible_dates').val(objectL10n.flexible_dates);
            $('.logodim').val(objectL10n.logodim);
            $('#logopos').val(objectL10n.logopos);
            $('#logo_enabled').val(objectL10n.logo_enabled);
            $('#fields_border_radius').val(objectL10n.fields_border_radius);
            $('#sb_border_radius').val(objectL10n.sb_border_radius);
            $('#buttonpos').val(objectL10n.buttonpos);
            $('#bgcolor').val(objectL10n.bgcolor);
            $('#dest_bgcolor').val(objectL10n.dest_bgcolor);
            $('#dest_textcolor').val(objectL10n.dest_textcolor);
            $('#headline_textsize').val(objectL10n.headline_textsize);
            $('#headline_textcolor').val(objectL10n.headline_textcolor);
            $('#textcolor').val(objectL10n.textcolor);
            $('#date_textcolor').val(objectL10n.date_textcolor);
            $('#date_bgcolor').val(objectL10n.date_bgcolor);
            $('#flexdate_textcolor').val(objectL10n.flexdate_textcolor);
            $('#submit_bgcolor').val(objectL10n.submit_bgcolor);
            $('#submit_bordercolor').val(objectL10n.submit_bordercolor);
            $('#submit_textcolor').val(objectL10n.submit_textcolor);
            $('#calendar_selected_bgcolor').val('#0071c2');
            $('#calendar_selected_textcolor').val('#FFFFFF');
            $('#calendar_daynames_color').val('#003580');
            $('#maintitle').val('');
            $('#dest_title').val('');
            $('#checkin').val('');
            $('#checkout').val('');
            $('#show_weeknumbers').val(objectL10n.show_weeknumbers);
            $('#submit').val('');

        }); // $('#reset_default').click( function()*/      


        // colour picker for specific fields    
        $(
            '#bgcolor,#textcolor,#dest_bgcolor,#dest_textcolor,#headline_textcolor,#date_textcolor,#date_bgcolor,#flexdate_textcolor,#submit_bgcolor,#submit_bordercolor,#submit_textcolor,#calendar_selected_bgcolor,#calendar_selected_textcolor,#calendar_daynames_color'
        ).wpColorPicker();

        if ($('#logo_enabled').prop('checked')) {
            $('.logodim_wrapper').removeClass('hidden');
            $('.logopos_wrapper').removeClass('hidden');
        }else {
            $('.logodim_wrapper').addClass('hidden');
            $('.logopos_wrapper').addClass('hidden');
        };

        $("#bos_info_displayer").click(function(event) {
            event.preventDefault();
            $("#bos_info_box").toggle();
        });

        $("#bos_info_displayer").on("focusout", function() {
            setTimeout(() => {
                $("#bos_info_box").toggle();
            }, 300);
        })

        $('#logo_enabled').change(function() {
            if (this.checked) {
                $('.logodim_wrapper').toggleClass('hidden');
                $('.logopos_wrapper').toggleClass('hidden');
            } else {
                $('.logodim_wrapper').toggleClass('hidden');
                $('.logopos_wrapper').toggleClass('hidden');
            }
        });
        


    });
})(jQuery);