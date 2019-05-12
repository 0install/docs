function collapse(tree) {
  $(tree).children("span.caption-text").addClass("expandable");
  $(tree).children("ul.subnav").hide();
}

function expand(tree) {
  $(tree).children("span.caption-text").removeClass("expandable");
  $(tree).children("ul.subnav").show();
}

// Collapse all sub-menus except the containing the current page
$("li.toctree-l1").each(function() {
  if ($(this).find(".current").length == 0) {
    collapse(this);
  }
});

// Scroll to the current page
$("nav").scrollTop($("a.current").offset().top - 128);

// Expand sub-menu on click and collapse all other sub-menus
$("span.caption-text").click(function(event) {
  if ($(event.target).hasClass("expandable")) {
    collapse("li.toctree-l1");
    expand($(event.target).parent());
  }
});
