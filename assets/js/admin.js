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

       
        $(document.body).on('click','#ua-add-new-variant',function(){

            var wrapper = $('.all-variant-group-wrapper');
            var count = wrapper.find('.font-variation-wrapper').length;
            var variant_key = count + 5;
            var html = `
                <div class="font-variation-wrapper" data-variant_key="`+ variant_key +`">
                    <span class="ua-close-variant"><i>Delete Variant </i>✂</span>
                    <div class="form-field">
                        <label for="font-weight-`+ variant_key +`">Font Weight</label>
                                <select id="font-weight-`+ variant_key +`" name="ua_fonts[variants][`+ variant_key +`][weight]">
                        <option value="100">Thin 100</option>
                            <option value="200">Extra-Light 200</option>
                            <option value="300">Light 300</option>
                            <option value="400" selected="">Normal 400</option>
                            <option value="500">Medium 500</option>
                            <option value="600">Semi-Bold 600</option>
                            <option value="700">Bold 700</option>
                            <option value="800">Extra-Bold 800</option>
                            <option value="900">Ultra-Bold 900</option>
                        </select>
                                <p class="ua-field-notice">Font weight for this variant.</p>
                    </div> 
                    
                    <div class="fonts-upload-wrapper form-field">
                        <label>Font File Upload <span class="font-upload-add-font-button">+add new font file</span></label>
                        
                        <div class="fonts-upload-wrapper-inside">
                                            <div class="form-file-field font-file-each-wrapper">
                            
                            <input name="ua_fonts[variants][`+ variant_key +`][format][]" type="hidden" class="font-upload-format" value="">
                            <input name="ua_fonts[variants][`+ variant_key +`][url][]" type="text" value="" class="font-upload-url" id="font-url-0" placeholder="Font file URL...">
                            <a href="#" class="ultraaddons-font-upload-button ua-button button">Upload Font</a>
                        </div> 

                                            </div>
                        <p class="ua-field-notice">Upload your webfonts. Supported font type/format: woff2,woff,ttf etc so on.</p>

                    </div>

                </div>
                `; 
                wrapper.append(html).attr('data-count',count+1);


        });

        /**
         * Adding new font file field
         * 
         * @since 1.1.0.7
         */
        $(document.body).on('click','span.font-upload-add-font-button',function(){
            var wrapper = $(this).closest('.font-variation-wrapper');
            var count = wrapper.data('variant_key');

            var html = '<div class="form-file-field font-file-each-wrapper">';     
            html += '<input name="ua_fonts[variants][' + count + '][format][]" type="hidden" class="font-upload-format" value="">';
            html += '<input name="ua_fonts[variants][' + count + '][url][]" type="text" value="" class="font-upload-url" id="font-url-' + count + '">';
            html += '<a href="#" class="ultraaddons-font-upload-button ua-button button">Upload Font</a>';
            html += '</div>';

            wrapper.find('.fonts-upload-wrapper-inside').append(html);
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



