define([], function () {
    'use strict';

    return function (target) {
        return target.extend({
            isItemsBlockExpanded: function () {
                return true;
            }
        });
    }
});