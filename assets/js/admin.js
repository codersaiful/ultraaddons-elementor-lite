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



        /**
         * Custom Fonts Area
         * 
         * @since 1.1.0.5
         */
        $(document.body).on('click','.ultraaddons-font-upload-button',function(e){
            e.preventDefault();
            var button = $(this); //Stil not used yet. Can be need later
            var fontType = $(this).data('font-type');
            if( !fontType ){
                fontType = 'woff2';
            }

            var fontsWrapperFieldArea = $(this).closest('.form-file-field');
            var fontUrlField = fontsWrapperFieldArea.find('.font-upload-url');
            
            var fonts_uploader = wp.media.frames.file_frame = wp.media({
                title: "Fonts Uploader",
                button:{
                    text: "Select Fonts"
                },
                library: {
                    //type: 'application/x-font-woff2,application/x-font-ttf'
                    //type: 'application/x-font-' + fontType,
                    type: 'application/x-font-woff2,application/x-font-woff,application/x-font-ttf,application/x-font-eot,application/x-font-otf'
                },
                multiple: true
            });

            fonts_uploader.on('select',function(){
                var attachment = fonts_uploader.state().get('selection').first().toJSON();

                var url = attachment.url;
                fontUrlField.val(url).change();
            });
            

            fonts_uploader.open();

        });

        /**
         * file extension fill to hidden input of font format field
         * 
         * @since 1.1.0.6
         */
        $(document.body).on('change','.font-upload-url',function(){
            var urlBox = $(this);
            var url = urlBox.val();
            url = url.replace(/\s+/, "");
            if( url == '' ){
                return;
            }
            
            var fontsWrapperFieldArea = $(this).closest('.form-file-field');
            var fontFormatField = fontsWrapperFieldArea.find('.font-upload-format');

            var ext = url.substr(url.lastIndexOf('.') + 1);
            fontFormatField.val(ext);
        });

        /**
         * Deleting any variant
         * using close button of variant
         * 
         * @since 1.1.0.7
         * @author Saiful<codersaiful@gmail.com>
         */
        $(document.body).on('click','.ua-close-variant',function(){
            var wrapper = $(this).closest('.font-variation-wrapper');

            var count = $('.all-variant-group-wrapper .font-variation-wrapper').length;

            if(count == 1){
                alert("Sorry, Unable to delete all Variant.");
                return false;
            }

            var permission = confirm( "Are you sure?" );
            if(permission){
                $('.all-variant-group-wrapper').attr('data-count',count-1);
                wrapper.remove();
            }

        });


    });



    /**
     * This part Actually for Dashboard Welcome Page
     * has done by Mukul, this comment has done by Saiful
     * 
     * No other code will add here.
     * @since 1.1.0.0
     */
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



