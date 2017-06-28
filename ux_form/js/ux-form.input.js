!function(t,e,i,n){"use strict";function a(e,i){this.element=e,this._name=s,this._defaults=t.fn.uxFormInput.defaults,this.options=t.extend({},this._defaults,i),this.init()}var s="uxFormInput";t.extend(a.prototype,{init:function(){this.buildCache(),this.bindEvents(),this.buildElement()},destroy:function(){this.unbindEvents(),this.$element.removeData()},buildElement:function(){var t=this;(this.hasValue()||this.isAutofocus()||this.hasPlaceholder()||this.hasBadInput())&&this.$element.addClass("active"),setTimeout(function(){t.$element.addClass("ready")})},buildCache:function(){var e=this;e.$element=t(this.element),e.input_selector=".ux-form-input-item-js",e.$field=e.$element.find(e.input_selector),e.$field.each(function(i){var n=e.$element.find(".field-suffix");n.length?n.after('<div class="ux-form-input-line" />'):t(this).after('<div class="ux-form-input-line" />')}),e.hasError()&&e.$element.addClass("invalid")},bindEvents:function(){var t=this;t.$field.on("change."+t._name,function(){t.onChange.call(t)}),t.$field.on("focus."+t._name,function(){t.onFocus.call(t)}),t.$field.on("blur."+t._name,function(){t.onBlur.call(t)})},unbindEvents:function(){this.$field.off("."+this._name)},onChange:function(){(this.hasValue()||this.hasPlaceholder())&&this.$element.addClass("active"),this.validate()},onFocus:function(){this.isReadonly()||this.$element.addClass("active focus")},onBlur:function(){var t="focus";this.hasValue()||!this.isValid()||this.hasPlaceholder()||(t+=" active"),this.$element.removeClass(t),this.validate()},validate:function(){this.$element.removeClass("valid invalid").removeAttr("data-error"),this.isValid()?this.hasValue()&&this.$element.addClass("valid"):this.$element.addClass("invalid").attr("data-error",this.$field[0].validationMessage)},hasPlaceholder:function(){var t=this.$field.attr("placeholder");return"undefined"!=typeof t&&t.length>0},hasValue:function(){return this.$field.val().length>0},hasError:function(){return this.$field.hasClass("error")},hasBadInput:function(){return this.$field[0].validity.badInput===!0},isValid:function(){return this.$field[0].validity.valid===!0},isAutofocus:function(){var t=this.$field.attr("autofocus");return"undefined"!=typeof t},isReadonly:function(){var t=this.$field.attr("readonly");return"undefined"!=typeof t}}),t.fn.uxFormInput=function(e){return this.each(function(){t.data(this,s)||t.data(this,s,new a(this,e))}),this},t.fn.uxFormInput.defaults={},e.behaviors.uxFormInput={attach:function(e){var i=t(e);i.find(".ux-form-input-js").once("ux-form-input").uxFormInput()},detach:function(e,i,n){"unload"===n&&t(e).find(".ux-form-input-js").each(function(){var e=t(this).data("uxFormInput");e&&e.destroy()})}}}(jQuery,Drupal,window,document);