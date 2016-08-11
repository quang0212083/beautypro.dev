jQuery(function($){
/*============================================================================
  Social Icon Buttons v1.0
  Author:
    Carson Shold | @cshold
    http://www.carsonshold.com
  MIT License
==============================================================================*/
window.CSbuttons = window.CSbuttons || {};

CSbuttons.cache = {
  shareButtons: $('.social-sharing')
}

CSbuttons.init = function () {
  CSbuttons.socialSharing();
}

CSbuttons.socialSharing = function () {
  var buttons = CSbuttons.cache.shareButtons,
      permalink = buttons.attr('data-permalink'),
      shareLinks = buttons.find('.share').addClass('tester');
    

  // Share popups
  shareLinks.on('click', function(e) {
    e.preventDefault();
    var el = $(this),
        popup = el.attr('class').replace('-','_'),
        link = el.attr('href');
    

    window.open(link, popup, 'width=700' +  ', height=400');
  });
}

$(function() {
  window.CSbuttons.init();
});

});
