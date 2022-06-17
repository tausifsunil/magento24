/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/ global $, $H /

define([
    'mage/adminhtml/grid'
], function () {
    'use strict';

    return function (config) {
        var selectedProducts = config.selectedProducts,
            categoryProducts = $H(selectedProducts),
            gridJsObject = window[config.gridJsObjectName],
            tabIndex = 1000;

        $('blog_id').value = Object.toJSON(categoryProducts);

        /**
         * Register Category Product
         *
         * @param {Object} grid
         * @param {Object} element
         * @param {Boolean} checked
         * 
         */

        // $('#blog_id').DataTable({
        // columnDefs: [{
        // orderable: false,
        // className: 'select-checkbox',
        // targets: 0
        // }, {
        //         "targets": [2],
        //         "visible": false,
        //         "searchable": false
        //     }]
        // })
        function registerCategoryProduct(grid, element, checked) {
            if (checked) {
                //if (element.positionElement) {
                    //element.positionElement.disabled = false;
                    categoryProducts.set(element.value, element.value);
                //}
            } else {
                /*if (element.positionElement) {
                    element.positionElement.disabled = true;
                }*/
                categoryProducts.unset(element.value);
            }
            $('blog_id').value = Object.toJSON(categoryProducts);
            grid.reloadParams = {
                'selected_products[]': categoryProducts.keys()
            };
        }

        /**
         * Click on product row
         *
         * @param {Object} grid
         * @param {String} event
         */
        function categoryProductRowClick(grid, event) {
            var trElement = Event.findElement(event, 'tr'),
                eventElement = Event.element(event),
                isInputCheckbox = eventElement.tagName === 'INPUT' && eventElement.type === 'checkbox',
                checked = false,
                checkbox = null;

            if (eventElement.tagName === 'LABEL' &&
                trElement.querySelector('#' + eventElement.htmlFor) &&
                trElement.querySelector('#' + eventElement.htmlFor).type === 'checkbox'
            ) {
                event.stopPropagation();
                trElement.querySelector('#' + eventElement.htmlFor).trigger('click');

                return;
            }

            if (trElement) {
                checkbox = Element.getElementsBySelector(trElement, 'input');
                if (checkbox[0]) {
                    checked = isInputCheckbox ? checkbox[0].checked : !checkbox[0].checked;
                    gridJsObject.setCheckboxChecked(checkbox[0], checked);
                    // console.log($checked);
                }
            }
        }

        /**
         * Change product position
         *
         * @param {String} event
         */
        function positionChange(event) {
            var element = Event.element(event);

            if (element && element.checkboxElement && element.checkboxElement.checked) {
                categoryProducts.set(element.checkboxElement.value, element.value);
                $('blog_id').value = Object.toJSON(categoryProducts);
            }
        }

        /**
         * Initialize category product row
         *
         * @param {Object} grid
         * @param {String} row
         */
        function categoryProductRowInit(grid, row) {
            var checkbox = $(row).getElementsByClassName('checkbox')[0];
                //position = $(row).getElementsByClassName('checkbox')[0];
            if (checkbox) {
                /*checkbox.positionElement = position;
                position.checkboxElement = checkbox;
                position.disabled = !checkbox.checked;
                position.tabIndex = tabIndex++;
                Event.observe(position, 'keyup', positionChange);*/
                
            }
        }

        gridJsObject.rowClickCallback = categoryProductRowClick;
        gridJsObject.initRowCallback = categoryProductRowInit;
        gridJsObject.checkboxCheckCallback = registerCategoryProduct;
        if (gridJsObject.rows) {
            gridJsObject.rows.each(function (row) {
                categoryProductRowInit(gridJsObject, row);
                var checkbox = $(row).getElementsByClassName('checkbox')[0];
                window._pro = selectedProducts;
                if (checkbox && checkbox.value) {
                    var check = false;
                    jQuery.each(selectedProducts, function(i, item) {
                        if(checkbox.value == i){
                            check = true;
                        }
                    });
                    if(check){
                        gridJsObject.setCheckboxChecked(checkbox, true);
                    }
                }
            });
        }
    };
});


// /**
//  * Created by : RH
//  */
// /* global $, $H */
// define([
//     'mage/adminhtml/grid'
// ], function () {
//     'use strict';
//     return function (config) {
//         var selectedProducts = config.selectedProducts,
//             categoryProducts = $H(selectedProducts),
//             gridJsObject = window[config.gridJsObjectName],
//             tabIndex = 1000;
//             console.log(selectedProducts);

//             var value=document.getElementById("blog_id").value;

//             console.log(value);
//         /**
//          * Show selected product when edit form in associated product grid
//          */
//         $('blog_id').value = Object.toJSON(categoryProducts);
//         /**
//          * Register Category Product
//          *
//          * @param {Object} grid
//          * @param {Object} element
//          * @param {Boolean} checked
//          */
//         function registerCategoryProduct(grid, element, checked) {
//             if (checked) {
//                 if (element.positionElement) {
//                     element.positionElement.disabled = false;
//                     categoryProducts.set(element.value, element.positionElement.value);
//                 }
//             } else {
//                 if (element.positionElement) {
//                     element.positionElement.disabled = true;
//                 }
//                 categoryProducts.unset(element.value);
//             }
//             $('blog_id').value = Object.toJSON(categoryProducts);
//             grid.reloadParams = {
//                 'selected_products[]': categoryProducts.keys()
//             };
//         }
//         /**
//          * Click on product row
//          *
//          * @param {Object} grid
//          * @param {String} event
//          */
//         function categoryProductRowClick(grid, event) {
//             var trElement = Event.findElement(event, 'tr'),
//                 isInput = Event.element(event).tagName === 'INPUT',
//                 checked = false,
//                 checkbox = null;
//             if (trElement) {
//                 checkbox = Element.getElementsBySelector(trElement, 'input');
//                 if (checkbox[0]) {
//                     checked = isInput ? checkbox[0].checked : !checkbox[0].checked;
//                     gridJsObject.setCheckboxChecked(checkbox[0], checked);
//                 }
//             }
//         }
//         /**
//          * Change product position
//          *
//          * @param {String} event
//          */
//         function positionChange(event) {
//             var element = Event.element(event);
//             if (element && element.checkboxElement && element.checkboxElement.checked) {
//                 categoryProducts.set(element.checkboxElement.value, element.value);
//                 $('blog_id').value = Object.toJSON(categoryProducts);
//             }
//         }
//         /**
//          * Initialize category product row
//          *
//          * @param {Object} grid
//          * @param {String} row
//          */
//         function categoryProductRowInit(grid, row) {
//             var checkbox = $(row).getElementsByClassName('checkbox')[0],
//                 // position = $(row).getElementsByClassName('input-text')[0];
//             // if (checkbox) {
//             //     // checkbox.positionElement = position;
//             //     position.checkboxElement = checkbox;
//             //     position.disabled = !checkbox.checked;
//             //     position.tabIndex = tabIndex++;
//             //     Event.observe(position, 'keyup', positionChange);
//             // }
//         }
//         gridJsObject.rowClickCallback = categoryProductRowClick;
//         gridJsObject.initRowCallback = categoryProductRowInit;
//         gridJsObject.checkboxCheckCallback = registerCategoryProduct;
//         if (gridJsObject.rows) {
//             gridJsObject.rows.each(function (row) {
//                 categoryProductRowInit(gridJsObject, row);
//             });
//         }
//     };
// });