/**
 * UltraAddons - Contact Form 7 Frontend Interactivity
 * Handles interactive micro-animations, AJAX submit state, invalid field shake, and smooth scrolling.
 */

(function ($) {
    'use strict';

    var UAContactForm7 = function ($scope) {
        var $wrapper = $scope.find('.ua-cf7-wrapper');
        if (!$wrapper.length) {
            return;
        }

        var $form = $wrapper.find('.wpcf7-form');
        if (!$form.length) {
            return;
        }

        // Auto Generate Placeholders from Field Labels
        if ($wrapper.hasClass('ua-cf7-auto-placeholders')) {
            $form.find('.wpcf7-form-control-wrap').each(function () {
                var $wrap = $(this);
                var $input = $wrap.find('input:not([type="submit"]):not([type="checkbox"]):not([type="radio"]):not([type="file"]), textarea');
                if ($input.length && !$input.attr('placeholder')) {
                    var $label = $wrap.closest('label');
                    if (!$label.length) {
                        $label = $wrap.prev('label');
                    }
                    if (!$label.length) {
                        $label = $wrap.siblings('label');
                    }
                    if ($label.length) {
                        var labelText = $label.clone().children().remove().end().text().trim();
                        labelText = labelText.replace(/[\*:]+$/, '').trim();
                        if (labelText) {
                            $input.attr('placeholder', labelText);
                        }
                    }
                }
            });
        }

        // 1. Input Focus & Blur Micro-Interactions
        $form.find('input, textarea, select').on('focus', function () {
            $(this).closest('.wpcf7-form-control-wrap').addClass('ua-cf7-focused');
        }).on('blur', function () {
            $(this).closest('.wpcf7-form-control-wrap').removeClass('ua-cf7-focused');
        });

        // 2. CF7 Ajax Before Submit Event
        $form.on('wpcf7beforesubmit', function () {
            var $submit = $form.find('input[type="submit"], button[type="submit"]');
            $submit.addClass('ua-cf7-btn-loading');
        });

        // 3. CF7 Invalid Fields Event - Interactive Micro-Shake
        $form.on('wpcf7invalid', function (e) {
            var $submit = $form.find('input[type="submit"], button[type="submit"]');
            $submit.removeClass('ua-cf7-btn-loading');

            var $invalidInputs = $form.find('.wpcf7-not-valid');
            $invalidInputs.addClass('ua-cf7-shake');
            setTimeout(function () {
                $invalidInputs.removeClass('ua-cf7-shake');
            }, 500);

            // Focus first invalid field
            if ($invalidInputs.length) {
                $invalidInputs.first().focus();
            }
        });

        // 4. CF7 Mail Sent (Success) Event
        $form.on('wpcf7mailsent', function () {
            var $submit = $form.find('input[type="submit"], button[type="submit"]');
            $submit.removeClass('ua-cf7-btn-loading');

            var $response = $wrapper.find('.wpcf7-response-output');
            if ($response.length) {
                $('html, body').animate({
                    scrollTop: $wrapper.offset().top - 100
                }, 400);
            }
        });

        // 5. CF7 Mail Failed Event
        $form.on('wpcf7mailfailed', function () {
            var $submit = $form.find('input[type="submit"], button[type="submit"]');
            $submit.removeClass('ua-cf7-btn-loading');

            var $response = $wrapper.find('.wpcf7-response-output');
            $response.addClass('ua-cf7-shake');
            setTimeout(function () {
                $response.removeClass('ua-cf7-shake');
            }, 500);
        });
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ultraaddons-contact-form7.default',
            UAContactForm7
        );
    });

    $(document).ready(function () {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ultraaddons-contact-form7.default',
                UAContactForm7
            );
        } else {
            $('.ua-cf7-wrapper').each(function () {
                UAContactForm7($(this).closest('.elementor-widget'));
            });
        }
    });

})(jQuery);
