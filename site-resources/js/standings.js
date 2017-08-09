window.onload = function() {
  $(window).on('beforeunload', function() {
    $("html body").scrollTop(0);
  });
  //var bottom = $(document).height() - $(window).height();
  //console.log($(window).height());
  $("html body").scrollTop(0);
  window.scrollTo(0,0);
  $(document).scrollTop();

  setTimeout(function() {
    $("html body").animate({scrollTop:$(document).height()}, 30000);
    setTimeout(function () {
        $("html body").animate({scrollTop:0}, 30000);
        setTimeout(function() {
          location.reload();
      }, 33000);
    }, 35000);
  }, 6000);
}
$(function() {
  $("html body").scrollTop(0);
  $(document).scrollTop(0);
  window.scrollTo(0,0);
});
