!function(t,e,o,a){"use strict";e.behaviors.uxAside={_defaults:{overlay:"#ux-asides",appendTo:!1},openCount:0,attach:function(e,o){var a=this;if(o.ux&&o.ux.aside&&o.ux.aside.items){var s=t("#ux-asides");t(document).once("ux-aside").on("drupalViewportOffsetChange.ux-aside",a.resize()),a.resize();for(var i in o.ux.aside.items)if(o.ux.aside.items[i]){var n=t("#ux-aside-"+i).once("ux-aside").appendTo(s);if(n.length){var u=a.getOptions(o.ux.aside,i);n.uxAside(u)}var d=t('[data-ux-aside="'+i+'"').once("ux-aside");d.length&&d.on("click",function(e){e.preventDefault();var o=t("#ux-aside-"+t(this).data("ux-aside"));o.length&&o.uxAside("open")})}}},detach:function(e,o,a){"unload"===a&&t(document).removeOnce("ux-aside").off("drupalViewportOffsetChange.ux-aside")},resize:function(e,o){t("#ux-asides").css({marginTop:a.offsets.top,marginLeft:a.offsets.left,marginRight:a.offsets.right,marginBottom:a.offsets.bottom})},getOptions:function(e,o){var s=this,i=t.extend({},s._defaults,e.options,e.items[o]);return i.onOpening=s.onOpening,i.onClosed=s.onClosed,i.attachTop&&null!==i.attachTop&&!1!==i.attachTop&&(i.top=0),i.attachBottom&&null!==i.attachBottom&&!1!==i.attachBottom&&(i.bottom=0),i.attachLeft&&null!==i.attachLeft&&!1!==i.attachLeft&&(i.left=0,i.openTall=!0),i.attachRight&&null!==i.attachRight&&!1!==i.attachRight&&(i.right=0,i.openTall=!0),i.offsets=a.offsets,i},onOpening:function(s){e.behaviors.uxAside.openCount++,t("#ux-asides").addClass("active"),s.options.top===a.offsets.top&&s.$element.css({borderTopRightRadius:0,borderTopLeftRadius:0}),s.options.bottom===a.offsets.bottom&&s.$element.css({borderBottomRightRadius:0,borderBottomLeftRadius:0}),s.options.restoreDefaultContent&&setTimeout(function(){e.attachBehaviors(s.$element.get(0),o)},10),s.initialized=!0},onClosed:function(o){0===--e.behaviors.uxAside.openCount&&t("#ux-asides").removeClass("active")}}}(jQuery,Drupal,drupalSettings,Drupal.displace);