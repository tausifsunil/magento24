require(['jquery'], function ($) {
    jQuery(document).ready(function () {
        if (jQuery("#login-button-text").val()) {
            var loginButtonText = jQuery("#login-button-text").val();
            var baseUrl = jQuery("#login-button-text").attr("data-baseurl");
            var enable = jQuery("#login-button-text").attr("data-enable");
            var customerLoggedIn = jQuery("#login-button-text").attr("data-customer");
        } else {
            var loginButtonText = "Login to see price";
            var baseUrl = "/";
            var enable = 0;
            var customerLoggedIn = 0;
        }
        if (enable == 1 && customerLoggedIn == 0) {
            jQuery(".product.name.product-item-name").append("<a href='" + baseUrl + "customer/account/' class='seeprice'>" + loginButtonText + "</a>");
            jQuery('.action.tocart').remove();
            jQuery('#product_addtocart_form').remove();
            jQuery(".price-box").remove();
        } else {
            jQuery(".seeprice").remove();
        }
    });
});
