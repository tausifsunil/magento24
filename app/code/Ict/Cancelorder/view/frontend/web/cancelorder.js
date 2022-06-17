require(["jquery", "jquery/ui"], function ($) {
    jQuery(document).ready(function () {
        jQuery('.cancel-btn').click(function () {
            if (jQuery(".cancelorder-details").length > 0) {
                jQuery(".cancelorder-details").show();
            } else {
                var id = jQuery(this).attr('data-id');
                var url = jQuery(this).attr('data-action');
                jQuery.ajax({
                    type: "post",
                    url: url,
                    data: {orderid : id},
                    cache: false,
                    showLoader: true,
                    success: function (output) {
                        jQuery(".cancelorder-popup").html(output.order);
                    }
                });
                return false;
            }
        });

        jQuery('body').on('click','.cancelorder-details .close',function () {
            jQuery('.cancelorder-details').hide();
        });
    });
});
