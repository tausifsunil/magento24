define([
	 'jquery',
	'mage/validation'
], function ($) {
	 'use strict';

	 return function (config) {
  		var dataForm = jQuery('#form-validate');
  		console.log('-------------');
  		console.log(dataForm);
  		console.log('-------------');
  		console.log(config.formKey);
  		console.log('-------------');
		var ignore = null;
		var quickOrderUrl = config.quickOrderUrl;
		var addtocartUrl = config.addtocartUrl;
		jQuery('body').on('keypress','.sku', _.debounce( function(){
		var mysku = jQuery(this).val();
		var mythis = jQuery(this).parents('.item').find('.product-options');
		var simpleProd = jQuery(this).parents('.item');
		jQuery.ajax({
			url : quickOrderUrl,
			data: {"sku" : mysku },
			type : 'post',
			beforeSend: function(){
			 jQuery('.loader').show();
			},
			success : function(result) {
				jQuery('.loader').hide();
				mythis.html(result.products);
				mythis.html(result.suggetion);
				if(result.simpleproducts != undefined){
					simpleProd.addClass('simple');
					simpleProd.attr('data',result.simpleproducts);
				}
			}
		});
	}, 1000));
		dataForm.mage('validation', {
			ignore: ignore ? ':hidden:not(' + ignore + ')' : ':hidden'
		}).find('input:text').attr('autocomplete', 'off');
		/*
		* Ajax call for product add to cart
		*/
		jQuery('.add-to-cart').click(function(){
			if(dataForm.validation('isValid') == true){
				var qty;
				var myarry = [];

				/*
				* Prepare needed product details array and pass in to ajax.
				*/
				/*jQuery(".super-attribute-select").each(function(){ 
				 myarry.push({
					  perent_id: jQuery(this).attr('parent-id'),
					  option_id: jQuery(this).val(),
					  product_id: jQuery(this).parents('.main-product-option').attr('product-id'),
					  qty : jQuery(this).parents('.item').find('.qty').val()
					});
				
				});*/
				$(".main-product-option").each(function(){
					var optionArray=[];
					var parent = $(this);
					console.log(parent);
				 	$(this).find('.super-attribute-select').each(function(){
				 		optionArray.push({
							perent_id: $(this).attr('parent-id'),
							option_id: jQuery(this).val(),
							product_id: parent.attr('product-id'),
							qty : jQuery(this).parents('.item').find('.qty').val()
						});
				 	});
				 myarry.push({
					  product_id: parent.attr('product-id'),
					  options:optionArray,
					  qty : jQuery(this).parents('.item').find('.qty').val()
					});
				});
				jQuery(".all-items .simple").each(function(){ 
					myarry.push({
						perent_id: '',
						option_id: '',
						product_id : jQuery(this).attr('data'),
						qty : jQuery(this).find('.qty').val()
					});
				});
				console.log(myarry);
				var person = JSON.stringify(myarry);
				// console.log(person);
				jQuery.ajax({
					url : addtocartUrl,
					dataType : 'json',
					data: {"myarray" : person },
					type : 'post',
					beforeSend: function(){
					 jQuery('.loader').show();
					},
					success : function(data) {
						jQuery('.loader').hide();
						setTimeout(function(){  
							if (!jQuery(".message-error")[0]){
								window.location.reload();
							}
						}, 2000);
					}
				});
			}
		}); 
		jQuery("body").on('click','.suggetion li a',function(){
		jQuery(this).parents('.item').find('.sku').val(jQuery(this).attr('data'));
		jQuery(this).parents('.suggetion').hide();
		jQuery(this).parents('.item').find('.sku').trigger('keypress');
	});
	jQuery("body").on('click','.close',function(){
		jQuery(this).parent('.item').remove();
		
	});
	jQuery("body").on('keypress','.field .qty',function(e){
		if(e.keyCode == 48 && jQuery(this).val().length == 0) {
			return false;
		}
		if(jQuery(this).val().length > 9){
			return false;
		} else {
			return true;
		}
	}); 	
	/*
	* Add another product title to add multiple product in cart.
	*/
  	var nameCount = 1;
	jQuery(".add-product-field").click(function(){
		jQuery(".all-items").append('<div class="item"><div class="field"><input type="text" class="sku" name="sku['+nameCount+']" placeholder="Enter Product/SKU" data-validate="{required:true}" autocomplete="off" /></div><div class="field"><input type="number" class="qty" name="qty['+nameCount+']" placeholder="Qty." data-validate="{required:true}" autocomplete="off" /></div><div class="product-options"></div><div class="close">Remove</div></div>');
		nameCount++;
	});
	 }
});