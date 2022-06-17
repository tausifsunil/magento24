define([
    'jquery',
    'magnificPopup',
    'Magento_Customer/js/customer-data'
], function ($, magnificPopup, customerData) {
    "use strict";

    $.widget('mage.foreverQuickViewAbstract', {
        options: {
            closeSeconds: 10,
            showMiniCart: true,
            showShoppingCheckoutButtons: true
        },

        displayContent: function (event, element, options) {
            var prodUrl = element.data('quickview-url'),
                optionsConf = options ? options : this.options;

            if (!prodUrl.length) {
                return false;
            }

            if (!optionsConf.hasOwnProperty('baseUrl')) {
                return false;
            }

            var url = optionsConf.baseUrl + 'quickview/index/updatecart';

            $.magnificPopup.open({
                items: {
                    src: prodUrl
                },
                type: 'iframe',
                closeOnBgClick: true,
                preloader: true,
                tLoading: '',
                callbacks: {
                    open: function () {
                        $('.mfp-preloader').css('display', 'block');
                    },
                    beforeClose: function () {
                        $('[data-block="minicart"]').trigger('contentLoading');
                        $.ajax({
                            url: url,
                            method: "POST"
                        });

                        customerData.invalidate(['cart']);
                    },
                    close: function () {
                        $('.mfp-preloader').css('display', 'none');
                        customerData.reload(['cart']);
                    },
                    afterClose: function () {

                    }
                }
            });
        }
    });

    $.widget('mage.foreverQuickViewDefault', $.mage.foreverQuickViewAbstract, {
        _create: function () {
            var self = this,
            options = this.options;

            $(function () {

                var button = self.element;
                var prodUrl = button.data('quickview-url');

                var params = {
                    button: button,
                    prodUrl: prodUrl
                };

                button.bind('click', params, function (e) {
                    var button = e.data.button,
                        prodUrl = e.data.prodUrl;
                    if (!prodUrl.length) {
                        return;
                    }

                    $.mage.foreverQuickViewAbstract.prototype.displayContent(e, button, options);

                    e.stopPropagation();
                    e.preventDefault();
                });
            })
        }
    });
});
