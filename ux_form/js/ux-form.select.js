!function(e,t,i,n){"use strict";function s(i,n){this.element=i,this.$element=e(this.element),this._name=o,this._defaults=e.fn.uxFormSelect.defaults,this.options=e.extend({},this._defaults,n),this.uniqueId=t.Ux.guid(),this.init()}var o="uxFormSelect";e.extend(s.prototype,{init:function(){this.buildCache(),this.buildElement(),this.evaluateElement(),this.bindEvents()},destroy:function(){this.unbindEvents(),this.$element.removeData()},buildCache:function(){this.$field=this.$element.find("select"),this.$wrapper=e('<div class="ux-form-select-wrapper ux-form-input ux-form-input-js"></div>'),this.$caret=e('<span class="ux-form-select-caret">&#9660;</span>'),this.$trigger=e('<input class="ux-form-input-item ux-form-input-item-js" '+(this.isDisabled()?"disabled":"")+"></input>"),this.$hidden=e('<input class="ux-form-select-hidden"></input>'),this.$dropdown=e('<ul class="ux-form-select-dropdown"></ul>'),this.multiple=!!this.$field.attr("multiple"),this.placeholder=this.$field.attr("placeholder")||(this.multiple?"Select Multiple":"Select One"),this.$trigger.addClass("ux-form-select-trigger"),this.$trigger.attr("placeholder",this.placeholder),this.$wrapper.insertAfter(this.$field),this.$wrapper.append(this.$caret).append(this.$hidden).append(this.$trigger).append(this.$dropdown).append(this.$field),this.$dropdown.addClass(this.multiple?"is-multiple":"is-single")},buildElement:function(){var e=this;e.loadOptionsFromSelect(),e.updateTrigger(),t.attachBehaviors(e.$element[0]),e.options.debug&&(e.$field.show(),setTimeout(function(){e.$trigger.trigger("tap")},500)),e.$field.attr("tabindex")&&e.$trigger.attr("tabindex",e.$field.attr("tabindex")),setTimeout(function(){e.$element.addClass("ready")})},evaluateElement:function(){var e=this;e.isRequired()&&(e.$field.removeAttr("required"),e.$trigger.attr("required","required"))},bindEvents:function(){var t=this;t.$trigger.on("focus."+t._name,function(t){e(this).blur()}).on("tap."+t._name,function(e){t.isSupported()?(t.populateDropdown.call(t),t.showDropdown.call(t)):(e.preventDefault(),t.$field.show().focus().hide())}),t.$dropdown.on("tap."+t._name,".selector",function(e){t.onItemTap.call(t,e),t.$trigger.focus()}),t.$dropdown.on("tap."+t._name,".close",function(e){t.closeDropdown.call(t,e)}),t.$hidden.on("focus",function(e){t.populateDropdown.call(t),t.showDropdown.call(t),t.$trigger.focus()}),t.$field.on("state:disabled."+t._name,function(e){t.evaluateElement()}).on("state:required."+t._name,function(e){t.evaluateElement()}).on("state:visible."+t._name,function(e){t.evaluateElement()}).on("state:collapsed."+t._name,function(e){t.evaluateElement()}),t.isSupported()||this.$field.on("change."+t._name,function(e){t.loadOptionsFromSelect(),t.updateTrigger()})},unbindEvents:function(){this.$element.off("."+this._name),this.$dropdown.off("."+this._name),this.$field.off("."+this._name),e(n).off("."+this._name)},onItemTap:function(t){var i,n=e(t.currentTarget),s=n.data("option");return this.multiple?(this.$dropdown.find(".selector.selected").removeClass("selected"),n.is(".active")?(i="remove",n.removeClass("active"),n.find("input").prop("checked",!1).trigger("change")):(i="add",n.addClass("active"),n.find("input").prop("checked",!0).trigger("change"),n.addClass("selected")),this.changeSelected(s,i)):(this.changeSelected(s,"add"),this.closeDropdown())},onSearch:function(t){var i=e(t.currentTarget),n=i.val().toLowerCase();n?this.$dropdown.find(".selector").each(function(){var t=e(this).data("option").text.toLowerCase();t.indexOf(n)>=0?e(this).show():e(this).hide()}):this.$dropdown.find(".selector").show()},populateDropdown:function(){this.$dropdown.find("li").remove(),0===this.$dropdown.children().length&&this.$dropdown.append('<li class="close">&times;</li>').append('<li class="search"><input type="text" class="ux-form-input-item simple search-input" tabindex="-1"></input></li>').find(".search-input").attr("placeholder",this.placeholder),this.$trigger.val()?this.$dropdown.find(".search-input").attr("placeholder",this.$trigger.val()):this.$dropdown.find(".search-input").attr("placeholder",this.placeholder);for(var i=this.getAllOptions(),n=0;n<i.length;n++){var s=i[n],o=e("<li></li>");s.group?(o.addClass("optgroup"),o.html("<span>"+s.text+"</span>")):this.multiple?(o.addClass("selector ux-select-checkbox ready"),o.html('<span><input type="checkbox" class="form-checkbox"><label class="option">'+s.text+"</label></span>")):(o.addClass("selector"),o.html("<span>"+s.text+"</span>")),s.selected&&(o.addClass("active"),o.find("input").prop("checked",!0)),o.data("option",s),this.$dropdown.append(o)}this.$dropdown.find(".selector.active:eq(0)").addClass("selected"),t.attachBehaviors(this.$dropdown[0])},loadOptionsFromSelect:function(){var t=this;this.selected=[],this.$field.find("option, optgroup").each(function(){var i={value:"",text:"",selected:!1,group:!1};e(this).is("optgroup")?(i.text=e(this).attr("label"),i.group=!0):(i.value=e(this).attr("value"),i.text=e(this).html(),i.selected=e(this).is(":selected")),t.selected.push(i)})},getAllOptions:function(e){if(!e)return this.selected;for(var t=[],i=0;i<this.selected.length;i++)t.push(this.selected[i][e]);return t},updateTrigger:function(){var e=this.getSelectedOptions("value").join("");null===e||""===e||"_none"===e?(this.$trigger.val(""),this.$trigger.attr("placeholder",this.htmlDecode(this.getSelectedOptions("text").join(", ")))):this.$trigger.val(this.htmlDecode(this.getSelectedOptions("text").join(", ")))},updateSearch:function(){this.$dropdown.find(".search-input").attr("placeholder",this.getSelectedOptions("text").join(", "))},getSelectedOptions:function(e){for(var t=[],i=0;i<this.selected.length;i++)this.selected[i].selected&&(e?t.push(this.selected[i][e]):t.push(this.selected[i]));return t},changeSelected:function(e,t){for(var i=!1,n=0;n<this.selected.length;n++)this.multiple||(this.selected[n].selected=!1),this.selected[n].value===e.value&&(i=!0,"add"===t?this.selected[n].selected=!0:"remove"===t&&(this.selected[n].selected=!1));this.updateTrigger(),this.multiple&&this.updateSearch(),this.updateSelect(i?null:e)},updateSelect:function(t){if(t){var i=e("<option></option>").attr("value",t.value).html(t.text);this.$field.append(i)}this.$field.val(this.getSelectedOptions("value")),this.$field.trigger("change",[!0]),this.$field.trigger("input",[!0])},showDropdown:function(){var t=this;return e(n).trigger("tap"),this.open?this.closeDropdown():(this.open=!0,this.$element.addClass("active"),setTimeout(function(){t.$element.addClass("animate"),t.$dropdown.focus()},50),t.$hidden.attr("readonly",!0),void this.windowHideDropdown())},windowHideDropdown:function(){var t=this;e(n).on("tap."+t.uniqueId,function(i){t.open&&(e(i.target).closest(t.$dropdown).length||t.closeDropdown())})},closeDropdown:function(){var t=this;this.open=!1,this.$dropdown.find(".search-input").val(""),this.$element.removeClass("animate"),e(n).off("."+t.uniqueId),setTimeout(function(){t.open===!1&&t.$element.removeClass("active")},350),t.$hidden.attr("readonly",!1)},isRequired:function(){var e=this.$field.attr("required");return"undefined"!=typeof e},isDisabled:function(){return this.$field.is(":disabled")},isSupported:function(){return"Microsoft Internet Explorer"===i.navigator.appName?n.documentMode>=8:!(/iP(od|hone)/i.test(i.navigator.userAgent)||/IEMobile/i.test(i.navigator.userAgent)||/Windows Phone/i.test(i.navigator.userAgent)||/BlackBerry/i.test(i.navigator.userAgent)||/BB10/i.test(i.navigator.userAgent)||/Android.*Mobile/i.test(i.navigator.userAgent))},htmlDecode:function(t){return e("<div/>").html(t).text()}}),e.fn.uxFormSelect=function(t){return this.each(function(){var i=e(this);i.hasClass("browser-default")||i.find("select").hasClass("browser-default")||e.data(this,o)||e.data(this,o,new s(this,t))}),this},e.fn.uxFormSelect.defaults={debug:!1},t.behaviors.uxFormSelect={attach:function(t){var i=e(t);i.find(".ux-form-select").once("ux-form-select").uxFormSelect()},detach:function(t,i,n){"unload"===n&&e(t).find(".ux-form-select").each(function(){var t=e(this).data("uxFormSelect");t&&t.destroy()})}}}(jQuery,Drupal,window,document);