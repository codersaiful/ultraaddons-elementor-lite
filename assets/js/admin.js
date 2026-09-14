;(function ($, w) {
    'use strict';
    var $window = $(w);

    $($window).ready(function(){
        $(document.body).on('click','.ua-option-item-wrappper .ua-option-item.item_on_off_disable,.ultraaddons-wrap button.ua-primary.ua-no-update',function(e){
            e.preventDefault();
        });

        // Prevent live preview and doc button clicks from toggling the checkbox
        $(document.body).on('click', '.ua-item-action-btn', function(e){
            e.stopPropagation();
        });
        
        /**
         * Do something
         * When something will be change on Form
         */
        $(document.body).on('change','.ua-option-item-wrappper,.ua-form-wrappper',function(e){
            $('.ultraaddons-wrap button.ua-primary').removeClass('ua-no-update'); //remove class from that submit button
            //Container Size field wrapper hide or show on Change anything of Field
            ua_container_size_update();
        });
        
        /**
         * For Widget and Extensions Page
         * Basically for On Off Feature Box/Widget Box/ Extensions Box
         */
        $(document.body).on('change','.ua-checkbox-hidden',function(){
            $(this).closest('.ua-option-item').toggleClass('disabled');
        });
        
        //By default check for Container Size field wrapper of Header Footer page
        ua_container_size_update();
        function ua_container_size_update(){
            
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
                    type: 'application/x-font-woff2,application/x-font-woff,application/x-font-ttf,application/x-font-otf' //application/x-font-eot, //eot has removed
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
            var thisObject = $(this);
            var urlBox = thisObject;
            var url = urlBox.val();
            url = url.replace(/\s+/, "");
            if( url == '' ){
                return;
            }
            
            var fontsWrapperFieldArea = thisObject.closest('.form-file-field');
            var fontFormatField = fontsWrapperFieldArea.find('.font-upload-format');

            var ext = url.substr(url.lastIndexOf('.') + 1);
            console.log(ext);
            if('ttf' == ext){
                ext = 'TrueType';
            }else if('otf' == ext){
                ext = 'OpenType';
            }
            console.log(ext);
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

      /**
       * Fot Header Footer
       * Display On Options
       */
      $('.ua-target_rule-condition').select2();
  });


  /**
   * Modern Elements Toolbar Filters (Search, Category, All/Free/Pro Tabs, Enable All)
   */
  function initElementsToolbar() {
    var $search = $('#ua-widget-search');
    var $categorySelect = $('#ua-widget-category-select');
    var $filterTabs = $('.ua-tab-pill, .ua-filter-tab');
    var $enableAllSwitch = $('#ua-enable-all-elements');
    var $toggleLabel = $('#ua-toggle-all-label');
    var $items = $('.ua-option-item-wrappper .ua-option-item');
    var $noFound = $('.ua-no-widgets-found');

    if (!$items.length) return;

    // Load saved filters if available
    var savedType = localStorage.getItem('ua_filter_type') || 'free-pro-all';
    var savedCat = localStorage.getItem('ua_filter_cat') || 'category-all';

    if (savedType) {
        $filterTabs.removeClass('active');
        var $targetTab = $filterTabs.filter('[data-target="' + savedType + '"]');
        if ($targetTab.length) {
            $targetTab.addClass('active');
        } else {
            $filterTabs.filter('[data-target="free-pro-all"]').addClass('active');
        }
    }

    if (savedCat && $categorySelect.find('option[value="' + savedCat + '"]').length) {
        $categorySelect.val(savedCat);
    }

    // Filter function
    function applyWidgetFilters() {
        var searchTerm = ($search.val() || '').toLowerCase().trim();
        var selectedCat = $categorySelect.val() || 'category-all';
        var selectedType = $filterTabs.filter('.active').data('target') || 'free-pro-all';

        var visibleCount = 0;

        $items.each(function () {
            var $item = $(this);
            var name = ($item.data('name') || '').toString().toLowerCase();
            var cats = ($item.data('category') || '').toString().toLowerCase().split(',');
            var type = ($item.data('type') || '').toString().toLowerCase();

            // 1. Search matching
            var matchSearch = !searchTerm || name.indexOf(searchTerm) !== -1;

            // 2. Category matching
            var matchCat = (selectedCat === 'category-all') || (cats.indexOf(selectedCat.toLowerCase()) !== -1) || $item.hasClass(selectedCat);

            // 3. Free / Pro matching
            var matchType = (selectedType === 'free-pro-all') || (type === selectedType) || $item.hasClass(selectedType);

            if (matchSearch && matchCat && matchType) {
                $item.show();
                visibleCount++;
            } else {
                $item.hide();
            }
        });

        if (visibleCount === 0) {
            $noFound.show();
        } else {
            $noFound.hide();
        }
    }

    // Search event
    $search.on('input keyup search', function () {
        applyWidgetFilters();
    });

    // Category select event
    $categorySelect.on('change', function () {
        localStorage.setItem('ua_filter_cat', $(this).val());
        applyWidgetFilters();
    });

    // All / Free / Pro Tab Click event
    $filterTabs.on('click', function (e) {
        e.preventDefault();
        $filterTabs.removeClass('active');
        $(this).addClass('active');
        localStorage.setItem('ua_filter_type', $(this).data('target'));
        applyWidgetFilters();
    });

    // Sync master "Enable All Elements" initial state
    function syncMasterToggleState() {
        var $freeItems = $items.not('.item_on_off_disable');
        if (!$freeItems.length) return;

        var disabledCount = $freeItems.filter('.disabled').length;
        var totalCount = $freeItems.length;

        // If all enabled, switch is ON. If more than half disabled, switch is OFF.
        var isAllEnabled = (disabledCount === 0);
        $enableAllSwitch.prop('checked', !isAllEnabled ? (disabledCount < totalCount / 2) : true);
        updateToggleLabel($enableAllSwitch.is(':checked'));
    }

    function updateToggleLabel(isEnabled) {
        if ($toggleLabel.length) {
            var isExtension = $('.ua-extensions-page').length > 0;
            var itemName = isExtension ? 'Extensions' : 'Elements';
            $toggleLabel.text(isEnabled ? ('Disable All ' + itemName) : ('Enable All ' + itemName));
        }
    }

    syncMasterToggleState();

    // Enable All Elements Switch Change
    $enableAllSwitch.on('change', function () {
        var enableAll = $(this).is(':checked');
        updateToggleLabel(enableAll);

        // Target active/changeable items (skip pro items if user doesn't have pro)
        var $targetItems = $items.not('.item_on_off_disable');

        if (enableAll) {
            // Enable all: uncheck hidden checkbox, remove disabled class
            $targetItems.find('.ua-checkbox-hidden').prop('checked', false);
            $targetItems.removeClass('disabled').addClass('enabled');
        } else {
            // Disable all: check hidden checkbox, add disabled class
            $targetItems.find('.ua-checkbox-hidden').prop('checked', true);
            $targetItems.removeClass('enabled').addClass('disabled');
        }

        // Activate submit button
        $('.ultraaddons-wrap button.ua-primary').removeClass('ua-no-update');
    });

    // Also update master switch when individual widget is toggled
    $(document.body).on('change', '.ua-checkbox-hidden', function () {
        setTimeout(syncMasterToggleState, 50);
    });

    // Initial filter execution
    applyWidgetFilters();
  }

  initElementsToolbar();
    
/**
   * Alert for Pro Widget 
   * @author B M Rafiul Alam
   * @Since 1.1.0.11
*/
  var inst = $('[data-remodal-id=modal]').remodal();
  $('.ua-version-free .pro').click(function(e){
     e.preventDefault();
     inst.open();
  });

  /**
   * Interactive FAQ Accordion on Welcome Page
   */
  $(document).on('click', '.ua-faq-question', function() {
      var $item = $(this).closest('.ua-faq-accordion-item');
      var $answer = $item.find('.ua-faq-answer');
      
      if ($item.hasClass('active')) {
          $item.removeClass('active');
          $answer.slideUp(200);
      } else {
          $item.addClass('active');
          $answer.slideDown(200);
      }
  });

} (jQuery, window));



