  jQuery(document).ready(function(){
    if ( jQuery.browser.msie && parseInt(jQuery.browser.version,10) == 6 ) {
      jQuery.fn.dropdownHover = function() {
        return this.each(function() {
          jQuery(this).hover(function(){
            jQuery(this).addClass("hover");
            jQuery('> .dir',this).addClass("open");
            jQuery('ul:first',this).css('visibility', 'visible');
          },function(){
            jQuery(this).removeClass("hover");
            jQuery('.open',this).removeClass("open");
            jQuery('ul:first',this).css('visibility', 'hidden');
          });
        });
      }
      if(jQuery("ul.dropdown").length) {
        jQuery("ul.dropdown li").dropdownHover();
      }
    }
  });
  
  jQuery(document).ready(function(){
    if(jQuery("ul.dropdown").length) {
      var ulDropdowns = jQuery("ul.dropdown");
      for (var i = 0; i<ulDropdowns.length; i++) {
        var ulDropdown = ulDropdowns.eq(i);
        if (ulDropdown.hasClass('dropdown-vertical')) continue;
        var rw = ulDropdown.outerWidth();
        var tw = 0;
        var nw = 0;
        var ulDropdownchildrenli = ulDropdown.children('li');
        ulDropdownchildrenli.each(function() {
          tw += jQuery(this).outerWidth();
        });
        var fctor = rw/tw;
        ulDropdownchildrenli.each(function() {
          jQuery(this).width(Math.round(jQuery(this).width()*fctor));
          nw+=jQuery(this).outerWidth();
        });
        if (nw != tw) {
          var lastLi = ulDropdownchildrenli.last();
          lastLi.width(lastLi.width()+(ulDropdown.outerWidth()-nw));
        }
      }
    }
  });