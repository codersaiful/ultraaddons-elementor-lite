;(function ($, w) {
    'use strict';
    
    var $window = $(w);

    $($window).ready(function(){
        $(document.body).on('click','.ua-option-item-wrappper .ua-option-item.pro,.ultraaddons-wrap button.ua-primary.ua-no-update',function(e){
            e.preventDefault();
        });
        
        $(document.body).on('change','.ua-option-item-wrappper',function(e){
            $('.ultraaddons-wrap button.ua-primary').removeClass('ua-no-update');
        });
        
    });
} (jQuery, window));