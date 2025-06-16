(function(c, a) {
    var b = {};
    b.name = "Strategic Partnerships namespace";
    b.gElm = function(d) {
        return (d) ? c.getElementById(d) : false;
    };
    b.gSelA = function(d) {
        return (d) ? c.querySelectorAll(d) : false;
    };
    b.gSel = function(d) {
        return (d) ? c.querySelector(d) : false;
    };

    b.validation = {
        validSearch: function() {
            var idf = jQuery('#b_idf:checked').val();
            //alert(idf);
            if (idf != 'on') { // check if idf checkbox is checked and exclude date validation
                if (!this.checkDestination() || !this.checkDates()) {
                    return false;
                }
            }
        },
        checkDestination: function() {
            var d = b.gElm("b_destination").value || "";
            if (d) {
                return true;
            }
            this.showFormError(b.vars.errors.destinationErrorMsg,
                "searchBox_error_msg");
            return false;
        },
        showFormError: function(f, d) {
            if (!f || !d) {
                return false;
            }
            var e = c.getElementById(d),
                g = function() {
                    jQuery(e).fadeOut("default");
                };
            e.innerHTML = f;
            e.style.cursor = "pointer";
            jQuery(e).fadeIn("default", function() {
                var h = this;
                if (h.addEventListener) {
                    h.addEventListener("click", g, false);
                }
                if (h.attachEvent) {
                    h.attachEvent("onclick", g);
                }
                setTimeout(g, 5000);
            });
        }
    };
    a.sp = b;
    a.e = b.gElm;
})(document, window);