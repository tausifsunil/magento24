define([
    'jquery',
    'mage/validation'
], function ($) {
    'use strict';

    return function (config) {
        var dataForm = $('#trackorder-form');
        var ignore = null;
        dataForm.mage('validation', {
            ignore: ignore ? ':hidden:not(' + ignore + ')' : ':hidden'
        }).find('input:text').attr('autocomplete', 'off');
        
        jQuery('#submitorder').click(function () {

            if (dataForm.validation('isValid')) {
                var orderID = jQuery("#order_id").val();
                var emailID = jQuery("#email_id").val();
                
                jQuery("#submitorder").prop('disabled', true);
                jQuery.ajax({
                    url : config.actionUrl + orderID,
                    dataType : 'json',
                    data: {"order_id" : orderID, "email_id" : emailID},
                    type : 'post',
                    beforeSend: function () {
                        jQuery('.ajax_loading').show();
                    },
                    success : function (data) {
                        jQuery('.ajax_loading').hide();
                        if (data.status == 'success') {
                            jQuery('#order-status').html(data.order_status);
                            jQuery('#order-info').html(data.order_info);
                            jQuery('#order-items').html(data.order_items);
                        } else {
                            jQuery('#order-status').html('');
                            jQuery('#order-info').html('');
                            jQuery('#order-items').html('');
                        }
                        jQuery("#submitorder").prop('disabled', false);
                    },
                });
            }
        });
    }
});
