$(function(){
  login_scroll();
  smooth();
});


function login_scroll(){
  $.scrollify({
    section:'.section',
    easing:'swing',
    scrollSpeed:600,
    updateHash: false,
  });
}

function smooth(){
  $("a[href^='#']").on('click', function(){
    var speed = 500;
    var href = $(this).attr('href');
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top;
    $("html, body").animate({scrollTop:position}, speed, "swing");
return false;
});
};
