!function(e){"use strict";Drupal.behaviors.uxMediaView={attach:function(c,i){e(".views-row",c).once("ux-media-view").each(function(){var c=e(this),i=c.find(".views-field-entity-browser-select input");e('<div class="ux-media-check"><svg class="ux-media-checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="ux-media-checkmark--circle" cx="26" cy="26" r="25" fill="none"/><path class="ux-media-checkmark--check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg></div>').appendTo(c),i.prop("checked")&&c.addClass("checked")}).click(function(){var c=e(this),i=c.find(".views-field-entity-browser-select input"),s=!i.prop("checked");i.prop("checked",s),s?c.removeClass("unchecked").addClass("checked"):c.removeClass("checked").addClass("unchecked")})}}}(jQuery,Drupal);