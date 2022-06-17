/**
 * Initialize Module depends on the area
 * @return widget
 */

 define([
    'jquery',
    'Forever_QuickView/js/quickViewRenderer',
    'domReady!'
], function ($) {
    // alert('her11111111111');

    $.widget('mage.foreverInitQuickView', {
        options: {
            mode: null,
            isAdminArea: null,
            config: null,
            productId: null,
            selector: null,
        },

        /**
         * Widget constructor.
         * @protected
         */
        _create: function () {
            var self = this,
                element = $("[data-product-id='" + self.options.productId + "']").closest('.product-item, .item');

            // observe only on category pages and without swatches
            if (self.options.mode === 'cat' && element.length) {
                self._handleIntersect(element);
            } else {
                self.execQuickView();
            }
        },

        /**
         * Exec Quick View widget
         * @public
         */
        execQuickView: function () {
            this.element.foreverRenerQuickViewButton(this.options.config);
        },

        /**
         * Use IntersectionObserver to lazy loading Amasty Label widget
         * @protected
         * @returns {function}
         */
        _handleIntersect: function (element) {
            var self = this,
                observer;

            observer = new IntersectionObserver(function (entries) {
                if (entries[0].isIntersecting) {
                    self.execQuickView();
                    observer.disconnect();
                }
            });

            observer.observe(element[0]);
        }
    });

    return $.mage.foreverInitQuickView;
});
