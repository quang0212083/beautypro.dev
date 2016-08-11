/**
 * @package		YJ Module Engine
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2014 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */
window.addEvent("domready", function () {

  
   $$('.pane-slider label').each(function (el) {
	   
	   
	   var getFor = el.getProperty("for");
	   
	   if(getFor){
	   	
		 var newClass= getFor.replace("jform_params_", "");
		 el.getParent().addClass(newClass + '_yjme');
	   }
	   
	});

  //  toggler
  var gotjoomla = $$('#joomla_items_holder');
  var gotk2 = $$('#k2_items_holder');
  // collect all items for toggler
  var get_yj_items = $$('.j_news_source_yjme,.yjcatfilter_yjme,.get_items_yjme,.item_yjme,.getspecific_yjme,.ordering_yjme,.show_frontpage_yjme');
  var get_k2_items = $$('.k2_news_source_yjme,.k2catfilter_yjme,.category_id_yjme,.k2item_yjme,.k2items_yjme,.k2image_size_yjme,.k2ordering_yjme');
  // add items in toggler
  gotjoomla.adopt(get_yj_items);
  gotk2.adopt(get_k2_items);





  //	//toggle
  var selected = $('jform_params_item_source').get("value");
  if (selected == 1) {
    $('selectedresult').set('html', 'Your news source is Joomla Content!');
    $('selectedresult').setStyle('color', '#769904');
    var mySlide1 = new Fx.Slide('k2_items_holder', {
      duration: 1000,
      transition: Fx.Transitions.Pow.easeOut
    }).hide();
    var mySlide2 = new Fx.Slide('joomla_items_holder', {
      duration: 1000,
      transition: Fx.Transitions.Pow.easeOut
    }).show();
  } else if (selected == 2) {
    $('selectedresult').set('html', 'Your news source is K2 Content!');
    $('selectedresult').setStyle('color', '#1A6EAE');
    var mySlide1 = new Fx.Slide('k2_items_holder', {
      duration: 1000,
      transition: Fx.Transitions.Pow.easeOut
    }).show();
    var mySlide2 = new Fx.Slide('joomla_items_holder', {
      duration: 1000,
      transition: Fx.Transitions.Pow.easeOut
    }).hide();
  }

  $('joomla_items_holder').getParent().addClass('togh_yj');
  $('k2_items_holder').getParent().addClass('togh_k2');
  $('jform_params_item_source').addEvent('change', function (event) {

    event.stop();



    var selectedsource = this.get("value");
    if (selectedsource == 1) { ///joomla selected
      mySlide1.toggle();
      mySlide2.toggle();
      $$('#select_source_title').highlight('#769904');
      $$('#select_source_title').setStyle('color', '#769904');
      $('k2not').setStyle('display', 'none');
    }

    if (selectedsource == 2) { ///k2 selected
      mySlide1.toggle();
      mySlide2.toggle();
      $$('#select_source_title').highlight('#1A6EAE');
      $$('#select_source_title').setStyle('color', '#1A6EAE');
      $('k2not').setStyle('display', 'block');
    }


  });

  // move order
  var cssholder = $('css_file');
  var cssselect = $('jform_params_module_css');
  cssselect.inject(cssholder, 'top');

  var tmplholder = $('copy_template');
  var tmplselect = $('jform_params_module_template');
  tmplselect.inject(tmplholder, 'top');




  // k2 select 


  $('jform_params_k2catfilter0').addEvent('click', function () {
    $('jformparamscategory_id').setProperty('disabled', 'disabled');
    $$('#jformparamscategory_id option').each(function (el) {
      el.setProperty('selected', 'selected');
    });
  })

  $('jform_params_k2catfilter1').addEvent('click', function () {
    $('jformparamscategory_id').removeProperty('disabled');
    $$('#jformparamscategory_id option').each(function (el) {
      el.removeProperty('selected');
    });

  })

  if ($('jform_params_k2catfilter0').checked) {
    $('jformparamscategory_id').setProperty('disabled', 'disabled');
    $$('#jformparamscategory_id option').each(function (el) {
      el.setProperty('selected', 'selected');
    });
  }

  if ($('jform_params_k2catfilter1').checked) {
    $('jformparamscategory_id').removeProperty('disabled');
  }


  // joomla select
  $('jform_params_yjcatfilter0').addEvent('click', function () {
    $('jform_params_get_items').setProperty('disabled', 'disabled');
    $$('#jform_params_get_items option').each(function (el) {
      el.setProperty('selected', 'selected');
    });
  })

  $('jform_params_yjcatfilter1').addEvent('click', function () {
    $('jform_params_get_items').removeProperty('disabled');
    $$('#jform_params_get_items option').each(function (el) {
      el.removeProperty('selected');
    });

  })

  if ($('jform_params_yjcatfilter0').checked) {
    $('jform_params_get_items').setProperty('disabled', 'disabled');
    $$('#jform_params_get_items option').each(function (el) {
      el.setProperty('selected', 'selected');
    });
  }

  if ($('jform_params_yjcatfilter1').checked) {
    $('jform_params_get_items').removeProperty('disabled');
  }


});