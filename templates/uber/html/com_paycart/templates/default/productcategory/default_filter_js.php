<?php

/**
* @copyright	Copyright (C) 2009 - 2014 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+contact@readybytes.in
* @author		rimjhim jain
*/


// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );?>

<script type="text/javascript">

(function($){
	paycart.product = {};
	paycart.product.filter = {};

	// initize searching/filtering
	paycart.product.filter.init = function(searchWord,filters){
		var link = 'index.php?option=com_paycart&view=search&task=filter&query='+searchWord;
		//parseJSON is used because filters are being passed as json string, so need to convert it object
		paycart.ajax.go(link , {'filters':$.parseJSON(filters), 'spinner_selector' : '#paycart-ajax-spinner'} );
		return false;
	};

	//invoke function on document ready to bind required actions on elements
	paycart.product.filter.bindActions = function(){
		$('body').addClass('row-offcanvas row-offcanvas-left');

		paycart.product.arrange('add');

		$('[data-toggle="offcanvas-filter"]').on('click',function () {
			  var currentId = $(this).attr('data-target');
			  $('.sidebar-offcanvas').not(currentId).hide();
			  	setTimeout( function(){ 
			  		if($(currentId).is(':visible')){
			  			$(currentId).hide();
			  		}
			  		else{
			  			$(currentId).show();
			  		}
				  }
				 , 100) ;
		    $('.row-offcanvas').toggleClass('active');
		});
		
		//apply filters on mobile
		$('[data-pc-selector="applyFilters"]').on('click',function(){
			paycart.product.filter.getResult();
		});

		//need to combine(comma seperated) min and max value in case of mobile
		$('input[name="filterPriceMin"],input[name="filterPriceMax"]').on('change', function(){
			var minPrice = $('input[name=filterPriceMin]').val();
			var maxPrice = $('input[name=filterPriceMax]').val();
			$('input[name="filters[core][price]"]').val(minPrice+','+maxPrice);
		});

		//need to combine(comma seperated) min and max value in case of mobile
		$('input[name="filterWeightMin"],input[name="filterWeightMax"]').on('change', function(){
			var minWeight = $('input[name=filterWeightMin]').val();
			var maxWeight = $('input[name=filterWeightMax]').val();
			$('input[name="filters[core][weight]"]').val(minWeight+','+maxWeight);
		});

		$('[data-pc-loadMore="click"]').on('click', function(){
			paycart.product.loadMore();
		});

		$('[data-pc-category="click"]').on('click', function(){
			paycart.category.redirect($(this).data('pc-categorylink'),$(this).data('pc-categoryid'));
		});

		$('[data-pc-filter="remove"]').on('click', function(){			
			paycart.product.filter.removeOption(this);
		});

		$('[data-pc-selector="remove"]').on('click', function(){
			paycart.product.filter.removeAttribute(this);
		});	

		$('[data-pc-selector="removeAll"]').on('click', function(){			
			paycart.product.filter.removeAll();
		});

		$('[data-pc-selector="sortOption"]').on('change',function(){
			paycart.product.filter.getResult();
		});

		//slider related script
		$(".pc-range-slider").slider({});

		$(".pc-range-slider").on('slideStop', function (ev) {
			$('input[name="'+this.name+'"]').attr('value',ev.value);
			$('input[name="pagination_start"]').attr('value',0);
			paycart.product.filter.submit('index.php?option=com_paycart&view=search&task=filter');
		});
		
		//remove the unwanted (duplicate) form
		//otherwise it creates issue when deselecting any checkbox of filter attributes
		wrapper_width = $('pc-product-search-content').width();
		screen_width  = $(window).width();
		if(screen_width < 768 && wrapper_width < 720){
			$('[data-pc-filter-form="desktop"]').remove();
		}
		else if(screen_width >= 768 || wrapper_width >= 720){
			$('[data-pc-filter-form="mobile"]').remove();

			//bind when device is not mobile
			$('[data-pc-result="filter"]').on('change',function(){
				paycart.product.filter.getResult();
			});
		}

		//scroll to first element
		var elem  = $('#pc-product-search-content');
		paycart.jQuery('html, body').animate({
			   scrollTop: elem.offset().top
			  }, 100);
		return true;
	};

	//get result according to the applied filters
	paycart.product.filter.getResult = function(){
		//set the sorting option to hidden input so that it get post with form
		var source = $('[data-pc-filter="sort-source"]').val();
		$('[data-pc-filter="sort-destination"]').val(source);
		$('input[name="pagination_start"]').attr('value',0);

		paycart.product.filter.submit('index.php?option=com_paycart&view=search&task=filter');
	};

	//remove a single applied filter 
	//each elem have data attribute pc-filter-applied-ref
	paycart.product.filter.removeOption = function(elem){
		var name = $(elem).data('pc-filter-applied-ref');

		$('input[name="pagination_start"]').attr('value',0);
		$('[name="'+name+'"]').attr('value','');
		$('[name="'+name+'"]').prop('checked',false);

		paycart.product.filter.submit('index.php?option=com_paycart&view=search&task=filter');
	};

	//remove all the filters belongs to a single attribute
	paycart.product.filter.removeAttribute = function(elem){
		var name = $(elem).data('pc-filter-name');

		$('[name*="'+name+'"]:checked').each(function(){
			$(this).prop('checked',false);
			$(this).prop('value','');
		});

		$('input[name="pagination_start"]').attr('value',0);
		paycart.product.filter.submit('index.php?option=com_paycart&view=search&task=filter');
	};

	//remove all the applied filters
	paycart.product.filter.removeAll = function(){

		var category   = $('input[name="filters[core][category]"]').val();
		var searchWord = $('input[name="query"]').val();
		var sort 	   = $('input[name="filters[sort]"]').val();
		
		var postData 	= {'filters[core][category]':category,'query':searchWord,'filters[sort]':sort,'pagination_start':0};
		
		postData.spinner_selector = '#paycart-ajax-spinner';
		paycart.ajax.go('index.php?option=com_paycart&view=search&task=filter', postData);
	};

	//common function that trigger filtering
	paycart.product.filter.submit = function(link){
		var disabledElem = $('[data-pc-result="filter"]:disabled:checked');

		//remove disabled + checked property so that disabled values can be posted
		$.each(disabledElem, function(k,v) {
			$('[name="'+v.name+'"]').removeAttr('disabled');
		});
		
		var postData 	= $('.pc-form-product-filter').serializeArray();

		//again make modified elements disabled 
		$.each(disabledElem, function(k,v) {
			$('[name="'+v.name+'"]').attr('disabled',true);
		});
		
		postData.spinner_selector = '#paycart-ajax-spinner';
		paycart.ajax.go(link, postData);
		return false;
	};

	//for infinite scrolling
	$(window).data('pc-scrollready', true).scroll(function () { 
		var elem = $('.pc-loadMore');
		//if total elements are less then the list limit then do nothing
		if(!elem.length){
			return;
		}
		
		//required this checking, so that multiple request can't be fired if one request is in process
		if ($(window).data('pc-scrollready') == false) return;
							
		var TopView = $(window).scrollTop();
	    var BotView = TopView + $(window).height();
	    var TopElement = elem.offset().top;
	    var BotElement = TopElement + elem.height();

		//if load more button is visible
		if(((BotElement <= BotView) && (TopElement >= TopView)) &&  !$('[data-pc-loadMore="click"]').hasClass('hide')){
	    	$(window).data('pc-scrollready', false);
	    	
		   //Add more products
		   paycart.product.filter.submit('index.php?option=com_paycart&view=search&task=loadMore');
		   return false;
	    }
	});

	//On click on show more button, load more products 
	paycart.product.loadMore = function(){
		paycart.product.filter.submit('index.php?option=com_paycart&view=search&task=loadMore');
	};

	//success callback for load more function 
	paycart.product.loadMore.success = function(data){
		var response = $.parseJSON(data);
		
		$(".pc-products-wrapper").append(response.html);
		$('input[name="pagination_start"]').val(response.pagination_start);
		
		paycart.product.arrange('update');

		//required to reset parameter that was set for handling multiple scroll request
		$(window).data('pc-scrollready', true);
	};	

	//arrage result products
	paycart.product.arrange = function(mode){
		// setup paycart-wrap size
		var sizeclass = paycart.helper.do_apply_sizeclass('.pc-products-wrapper');
		// arrange item layout
		if(mode=="add"){
			paycart.helper.do_grid_layout('#pc-products[data-columns]','.pc-product-outer', '.pc-product', sizeclass);
		}else{
			var start = $('input[name="pagination_start"]').val();
			paycart.helper.update_grid_layout('#pc-products[data-columns]','.pc-product-outer', '.pc-product-outer.pc-next-'+start, sizeclass);
		}
	};

	//redirect on clicking of any category in filters
	paycart.category = {};
	paycart.category.redirect = function(link,categoryId){
		$('.pc-form-product-filter').attr('action',link);
		$('input[name="filters[core][category]"]').val(categoryId);
		$('.pc-form-product-filter').submit();
	};
			
})(paycart.jQuery);
</script>
<?php 