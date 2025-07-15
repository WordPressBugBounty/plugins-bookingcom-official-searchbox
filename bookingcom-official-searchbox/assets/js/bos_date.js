jQuery(function($) {

    var language = objectL10n.language;
    moment.locale(language);
    var checkin = moment();
    var checkout = moment(checkin).add(1, 'd');

    function cb(checkin, checkout) {
        var dateFormat = '';
        if (language == 'de_DE') {
            dateFormat = 'ddd., D. MMM. YYYY';
        } else {
            dateFormat = 'ddd, D MMM YYYY';
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
        showWeekNumbers: (objectL10n.show_weeknumbers == 1 || objectL10n.settings.show_weeknumbers == 1) ? true : false,
        maxSpan: {
            days: 30
        }
    }, cb).on('show.daterangepicker', function (ev, picker) {
        picker.container.addClass('bos-css');                            
    });

    cb(checkin, checkout);


    function cb2(checkin, checkout) {
        var dateFormat = '';
        if (language == 'de_DE') {
            dateFormat = 'ddd., D. MMM. YYYY';
        } else {
            dateFormat = 'ddd, D MMM YYYY';
        }
        $('.b_dates_inner_wrapper div.bos-date_b_checkin').html(checkin.format(dateFormat));
        $('.b_checkin').val(checkin.format('YYYY-MM-DD'));
        $('.b_dates_inner_wrapper div.bos-date_b_checkout').html(checkout.format(dateFormat));
        $('.b_checkout').val(checkout.format('YYYY-MM-DD'));
    }


    $('.b_dates').daterangepicker({
        singleDatePicker: false,
        startDate: checkin,
        endDate: checkout,
        minDate: moment(),
        autoApply: true,
        opens: "center",
        showWeekNumbers: objectL10n.show_weeknumbers == 1 ? true : false,
        maxSpan: {
            days: 30
        }
    }, cb2).on('show.daterangepicker', function (ev, picker) {
        picker.container.addClass('bos-css');                            
    });

    cb2(checkin, checkout);
});