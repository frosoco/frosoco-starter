



$(function() {
  var active;
  $("#header .menu .nav > li").hover(function() {
    //menuItems.find(".submenu").hide();
    //$(this).find(".submenu").show();

    active = $("#header .menu .nav > li.active");
    active.addClass("hide");
    $(this).addClass("hover");
  }, function() {
    $(this).removeClass("hover");
    active.removeClass("hide");
    //for (var i = 0; i < menuItems.length; i++) {
      //var menuItem = $(menuItems[i]);
      //if (menuItem.hasClass("active")) {
        //menuItem.find(".submenu").show();
        //break;
      //} 
    //}
  });
});
