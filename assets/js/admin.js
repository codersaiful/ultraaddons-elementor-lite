;(function ($, w) {
    'use strict';
    
    var $window = $(w);

    $($window).ready(function(){
        $(document.body).on('click','.ua-widget-item-wrappper .ua-widget-item.pro',function(e){
            e.preventDefault();
            $(this).addClass('its-pro');
        });
    });
} (jQuery, window));