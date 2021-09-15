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
            // console.log(hf_container_size);
            if(typeof hf_container_size !== 'undefined' && hf_container_size === 'php'){
                $('.field-container-size').fadeIn();
            }else{
                $('.field-container-size').fadeOut();
            }
        }
    });


    $(document).ready(function(){
      'use strict';
      var topic = '.ua-admin-welcome-content-area section.faq .faq-nav ul li';
      $('body').on('click', topic, function( event ){

        var target = $(this).data('target');
        var targetBlock = $( '#' + target ).closest('.faq-details').children();
        $(this).closest( 'ul' ).children().each(function( key, value ){
            $( value ).removeClass( 'active' );
        });
        $(this).addClass( 'active' );
        
        // Topic change
        $(targetBlock).each(function( key, value ){
            $(value).removeClass('active');
        });
        $( '#' + target ).addClass('active');

        
      });
      $( '.faq-details .faq-inner-box li.faq-item' ).click( function ( event ) {
          console.log(event.target);
          let targetFaq = $( event.target ).closest( 'ul' ).children();
          $( targetFaq ).each(function( key, value ){
              $( value ).removeClass( 'active' );
          });
          $( event.target ).parent().addClass( 'active' );
      });

      $(".video-gallery").owlCarousel({
        responsiveClass:true,
		margin:20,
        responsive:{
            0:{
                items:1,
            },
            768:{
                items:2,
            },
            992:{
                items:3,
                loop:false
            }
        }
      });
      
  });

} (jQuery, window));



