;(function ($, w) {
    'use strict';
    
    var $window = $(w);

    $($window).ready(function(){
        $(document.body).on('click','.ua-option-item-wrappper .ua-option-item.item_on_off_disable,.ultraaddons-wrap button.ua-primary.ua-no-update',function(e){
            e.preventDefault();
        });
        
        /**
         * Do something
         * When something will be change on Form
         */
        $(document.body).on('change','.ua-option-item-wrappper,.ua-form-wrappper',function(e){
            $('.ultraaddons-wrap button.ua-primary').removeClass('ua-no-update'); //remove class from that submit button
            //Container Size field wrapper hide or show on Change anything of Field
            hf_container_size_update();
        });
        
        /**
         * For Widget and Extensions Page
         * Basically for On Off Feature Box/Widget Box/ Extensions Box
         */
        $(document.body).on('change','.ua-checkbox-hidden',function(){
            $(this).closest('.ua-option-item').toggleClass('disabled');
        });
        
        //By default check for Container Size field wrapper of Header Footer page
        hf_container_size_update();
        function hf_container_size_update(){
            
            var hf_container_size = $('.ua-hf-type-radio:checked').val();
            console.log(hf_container_size);
            if(typeof hf_container_size !== 'undefined' && hf_container_size === 'php'){
                $('.field-container-size').fadeIn();
            }else{
                $('.field-container-size').fadeOut();
            }
        }
    });
} (jQuery, window));

/*
//To take help/idea from elementor. we have kept this part of comment.
  (0, _createClass2.default)(_default, [{
    key: "addCustomCss",
    value: function addCustomCss(css, context) {
      if (!context) {
        return;
      }

      var model = context.model,
          customCSS = model.get('settings').get('custom_css');
      var selector = '.elementor-element.elementor-element-' + model.get('id');

      if ('document' === model.get('elType')) {
        selector = elementor.config.document.settings.cssWrapperSelector;
      }

      if (customCSS) {
        css += customCSS.replace(/selector/g, selector);
      }

      return css;
    }
  }, {
    key: "onElementorInit",
    value: function onElementorInit() {
      elementor.hooks.addFilter('editor/style/styleText', this.addCustomCss);
      elementor.on('navigator:init', this.onNavigatorInit.bind(this));
    }
  }
  */