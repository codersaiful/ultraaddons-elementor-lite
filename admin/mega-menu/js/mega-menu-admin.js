/**
 * UltraAddons - Mega Menu Admin Controller
 * 
 * Injects trigger buttons onto menu items in Appearance > Menus,
 * manages modal dialog settings, and handles Ajax template creation and saves.
 * 
 * @package UltraAddons
 * @since 3.1.0
 */
(function ($) {
    'use strict';

    var currentItemId = 0;
    var currentItemTitle = '';
    var itemsData = (window.uaMegaMenuAdmin && window.uaMegaMenuAdmin.items_data) ? window.uaMegaMenuAdmin.items_data : {};

    // -------------------------------------------------------------
    // 1. Inject "Ultra Mega Menu" Button on Menu Items
    // -------------------------------------------------------------
    var injectTriggerButtons = function () {
        $('#menu-to-edit .menu-item').each(function () {
            var $item = $(this);
            var idAttr = $item.attr('id') || '';
            var itemId = parseInt(idAttr.replace('menu-item-', ''), 10);
            if (!itemId) return;

            // Prevent duplicate buttons
            if ($item.find('.ua-mega-trigger-btn').length) {
                return;
            }

            var itemData = itemsData[itemId] || {};
            var hasMega = itemData.has_mega || (itemData.settings && itemData.settings.enable === '1');
            var btnClass = hasMega ? 'ua-mega-trigger-btn ua-is-active' : 'ua-mega-trigger-btn';
            var btnText = hasMega ? uaMegaMenuAdmin.i18n.btn_enabled : uaMegaMenuAdmin.i18n.btn_text;

            var $btn = $('<button type="button" class="' + btnClass + '" data-item-id="' + itemId + '">' +
                '<span class="dashicons dashicons-screenoptions" style="font-size:13px;width:13px;height:13px;line-height:1.4;"></span> ' +
                btnText +
                '</button>');

            // Insert into the menu item title bar
            var $titleBar = $item.find('.item-title:first');
            if ($titleBar.length) {
                $titleBar.append($btn);
            } else {
                $item.find('.menu-item-bar .menu-item-handle:first').append($btn);
            }
        });
    };

    // -------------------------------------------------------------
    // 2. Open Settings Modal
    // -------------------------------------------------------------
    var openModal = function (itemId) {
        currentItemId = itemId;
        var $item = $('#menu-item-' + itemId);
        currentItemTitle = $item.find('.menu-item-title').text() || ('Item #' + itemId);

        $('#ua-modal-item-title').text(currentItemTitle);
        $('.ua-status-msg').hide().removeClass('ua-success ua-error').text('');

        // Populate fields with saved or default settings
        var itemData = itemsData[itemId] || {};
        var s = itemData.settings || {
            enable: '0',
            width_type: 'container',
            custom_width: 760,
            position: 'center',
            mobile_mode: 'mega',
            icon: '',
            icon_color: '#4f46e5',
            icon_size: 16,
            badge_text: '',
            badge_style: 'pill',
            badge_bg: '#4f46e5',
            badge_color: '#ffffff',
            badge_animation: 'none'
        };

        // General
        $('#ua-setting-enable').prop('checked', s.enable === '1').trigger('change');
        $('#ua-setting-width-type').val(s.width_type || 'container').trigger('change');
        $('#ua-setting-custom-width').val(s.custom_width || 760);
        $('#ua-setting-position').val(s.position || 'center');
        $('#ua-setting-mobile-mode').val(s.mobile_mode || 'mega');

        // Icon
        $('#ua-setting-icon').val(s.icon || '');
        setColorPickerValue('#ua-setting-icon-color', s.icon_color || '#4f46e5');
        $('#ua-setting-icon-size').val(s.icon_size || 16);

        // Badge
        $('#ua-setting-badge-text').val(s.badge_text || '');
        $('#ua-setting-badge-style').val(s.badge_style || 'pill');
        setColorPickerValue('#ua-setting-badge-bg', s.badge_bg || '#4f46e5');
        setColorPickerValue('#ua-setting-badge-color', s.badge_color || '#ffffff');
        $('#ua-setting-badge-animation').val(s.badge_animation || 'none');

        // Reset to first tab
        $('.ua-mega-tab-btn[data-tab="general"]').trigger('click');

        $('#ua-mega-modal-overlay').fadeIn(180);
    };

    var closeModal = function () {
        $('#ua-mega-modal-overlay').fadeOut(150);
    };

    var setColorPickerValue = function (selector, color) {
        var $el = $(selector);
        if ($el.hasClass('wp-color-picker')) {
            $el.wpColorPicker('color', color);
        } else {
            $el.val(color);
        }
    };

    // -------------------------------------------------------------
    // 3. Document Ready Initialization
    // -------------------------------------------------------------
    $(function () {
        injectTriggerButtons();

        // Re-inject on WP nav-menu dynamic item additions
        $(document).on('menu-item-added', function () {
            setTimeout(injectTriggerButtons, 300);
        });

        // Initialize color pickers
        if ($.fn.wpColorPicker) {
            $('.ua-color-field').wpColorPicker();
        }

        // Click trigger button
        $(document).on('click', '.ua-mega-trigger-btn', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var itemId = $(this).data('item-id');
            openModal(itemId);
        });

        // Close modal
        $('.ua-mega-modal-close, .ua-modal-btn-cancel').on('click', function (e) {
            e.preventDefault();
            closeModal();
        });

        // Close on background overlay click
        $('#ua-mega-modal-overlay').on('click', function (e) {
            if ($(e.target).is('#ua-mega-modal-overlay')) {
                closeModal();
            }
        });

        // Close on ESC key
        $(document).on('keyup', function (e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                if ($('#ua-mega-editor-overlay').is(':visible')) {
                    closeEditorOverlay();
                } else if ($('#ua-mega-modal-overlay').is(':visible')) {
                    closeModal();
                }
            }
        });

        // Tab switching
        $('.ua-mega-tab-btn').on('click', function () {
            var $btn = $(this);
            var tab = $btn.data('tab');

            $('.ua-mega-tab-btn').removeClass('ua-tab-active');
            $btn.addClass('ua-tab-active');

            $('.ua-mega-tab-content').hide();
            $('#ua-tab-' + tab).show();
        });

        // Toggle enable switch
        $('#ua-setting-enable').on('change', function () {
            var isChecked = $(this).is(':checked');
            if (isChecked) {
                $('.ua-mega-dependent-options').slideDown(180);
            } else {
                $('.ua-mega-dependent-options').slideUp(180);
            }
        });

        // Dropdown width type switch
        $('#ua-setting-width-type').on('change', function () {
            if ($(this).val() === 'custom') {
                $('.ua-custom-width-row').slideDown(180);
            } else {
                $('.ua-custom-width-row').slideUp(180);
            }
        });

        // -------------------------------------------------------------
        // 4. Edit with Elementor Action
        // -------------------------------------------------------------
        $('#ua-btn-edit-elementor').on('click', function (e) {
            e.preventDefault();
            var $btn = $(this);
            $btn.prop('disabled', true).addClass('updating-message');

            $.ajax({
                url: uaMegaMenuAdmin.ajax_url,
                type: 'POST',
                data: {
                    action: 'ua_create_mega_template',
                    item_id: currentItemId,
                    security: uaMegaMenuAdmin.security
                },
                success: function (res) {
                    $btn.prop('disabled', false).removeClass('updating-message');
                    if (res.success && res.data.editor_url) {
                        openEditorOverlay(res.data.editor_url);
                    } else {
                        alert(res.data && res.data.message ? res.data.message : uaMegaMenuAdmin.i18n.error);
                    }
                },
                error: function () {
                    $btn.prop('disabled', false).removeClass('updating-message');
                    alert(uaMegaMenuAdmin.i18n.error);
                }
            });
        });

        // -------------------------------------------------------------
        // 5. Save Mega Menu Settings
        // -------------------------------------------------------------
        $('#ua-btn-save-settings').on('click', function (e) {
            e.preventDefault();
            var $btn = $(this);
            var $msg = $('.ua-status-msg');

            $btn.prop('disabled', true).text(uaMegaMenuAdmin.i18n.saving);
            $msg.hide();

            var settings = {
                enable: $('#ua-setting-enable').is(':checked') ? '1' : '0',
                width_type: $('#ua-setting-width-type').val(),
                custom_width: $('#ua-setting-custom-width').val(),
                position: $('#ua-setting-position').val(),
                mobile_mode: $('#ua-setting-mobile-mode').val(),
                icon: $('#ua-setting-icon').val(),
                icon_color: $('#ua-setting-icon-color').val(),
                icon_size: $('#ua-setting-icon-size').val(),
                badge_text: $('#ua-setting-badge-text').val(),
                badge_style: $('#ua-setting-badge-style').val(),
                badge_bg: $('#ua-setting-badge-bg').val(),
                badge_color: $('#ua-setting-badge-color').val(),
                badge_animation: $('#ua-setting-badge-animation').val()
            };

            $.ajax({
                url: uaMegaMenuAdmin.ajax_url,
                type: 'POST',
                data: {
                    action: 'ua_save_mega_settings',
                    item_id: currentItemId,
                    settings: settings,
                    security: uaMegaMenuAdmin.security
                },
                success: function (res) {
                    $btn.prop('disabled', false).text('Save Mega Menu');

                    if (res.success) {
                        $msg.removeClass('ua-error').addClass('ua-success').text(uaMegaMenuAdmin.i18n.saved).fadeIn();

                        // Update local cache
                        if (!itemsData[currentItemId]) {
                            itemsData[currentItemId] = {};
                        }
                        itemsData[currentItemId].settings = res.data.settings;
                        itemsData[currentItemId].has_mega = (res.data.settings.enable === '1');

                        // Update trigger button state in DOM
                        var $triggerBtn = $('.ua-mega-trigger-btn[data-item-id="' + currentItemId + '"]');
                        if (res.data.settings.enable === '1') {
                            $triggerBtn.addClass('ua-is-active').html(
                                '<span class="dashicons dashicons-screenoptions" style="font-size:13px;width:13px;height:13px;line-height:1.4;"></span> ' +
                                uaMegaMenuAdmin.i18n.btn_enabled
                            );
                        } else {
                            $triggerBtn.removeClass('ua-is-active').html(
                                '<span class="dashicons dashicons-screenoptions" style="font-size:13px;width:13px;height:13px;line-height:1.4;"></span> ' +
                                uaMegaMenuAdmin.i18n.btn_text
                            );
                        }

                        setTimeout(function () {
                            closeModal();
                        }, 600);
                    } else {
                        $msg.removeClass('ua-success').addClass('ua-error').text(res.data && res.data.message ? res.data.message : uaMegaMenuAdmin.i18n.error).fadeIn();
                    }
                },
                error: function () {
                    $btn.prop('disabled', false).text('Save Mega Menu');
                    $msg.removeClass('ua-success').addClass('ua-error').text(uaMegaMenuAdmin.i18n.error).fadeIn();
                }
            });
        });

        // -------------------------------------------------------------
        // 6. Elementor Editor Overlay Handling
        // -------------------------------------------------------------
        var openEditorOverlay = function (url) {
            var $overlay = $('#ua-mega-editor-overlay');
            var $iframe = $('#ua-mega-editor-iframe');
            var $spinner = $('.ua-editor-loading-spinner');

            $spinner.show();
            $iframe.attr('src', url).on('load', function () {
                $spinner.fadeOut(200);
            });

            $overlay.fadeIn(200);
        };

        var closeEditorOverlay = function () {
            var $overlay = $('#ua-mega-editor-overlay');
            var $iframe = $('#ua-mega-editor-iframe');

            $overlay.fadeOut(200, function () {
                $iframe.attr('src', '');
            });
        };

        $('.ua-editor-close-btn').on('click', function () {
            closeEditorOverlay();
        });

    });

})(jQuery);
