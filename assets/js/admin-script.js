jQuery(document).ready(function($) {

    jQuery(".mmwea-select2-multi").select2();
    jQuery('.mmwea-select2-multi').on('focus', () => {
        inputElement.parent().find('.select2-search__field').prop('disabled', true);
    });

    jQuery(".mmwea-select2-multi-product").select2({
        ajax: {
            type: 'POST',
            url: mmweaObj.ajaxurl,
            dataType: 'json',
            data: (params) => {
                return {
                    'search': params.term,
                    'action': 'mmwea_product_select_ajax',
                }
            },
            processResults: (data, params) => {
                const results = data.map(item => {
                    return {
                        id: item.id,
                        text: item.title,
                    };
                });
                return {
                    results: results,
                }
            },
        },
        minimumInputLength: 3
    });

    jQuery("body").on("change", ".mmwea-user-radio", function() {
        if (this.value == "logged-in") {
            jQuery("tr").removeClass("mmwea-user-role-hide");
        } else {
            jQuery(this).closest('tr').next().addClass("mmwea-user-role-hide");
        }
    });

    jQuery("body").on("keypress change", ".mmwea-number-validation #whatsapp_number", function(e) {

        if (e.which == 13) {
            jQuery(this).removeClass('mmwea-error-border');
            return true;
        } else {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                jQuery(this).addClass('mmwea-error-border');
                return false;
            } else {
                jQuery(this).removeClass('mmwea-error-border');
            }
        }

    });

    jQuery("body").on('submit', '.mmwea-general-setting', function() {
        var wp_number = jQuery("#whatsapp_number").val();

        if (wp_number == '') {
            jQuery("#whatsapp_number").addClass('mmwea-error-border');
            return false;
        } else {
            jQuery("#whatsapp_number").removeClass('mmwea-error-border');
            return true;
        }
    });

    $(function() {
        $('.mmwea_colorpicker').wpColorPicker();
    });

});

jQuery(".mmwea-wa-order-button").on("event", function() {
    alert("demo");
});