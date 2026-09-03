/**
 * UltraAddons - Protected Content Frontend Engine
 * Handles Eye Icon Toggle, Instant Ajax Verification, Cookie Unlock, and Shake Animations.
 */

(function ($) {
    'use strict';

    var UAProtectedContent = function ($scope) {
        var $wrapper = $scope.find('.ua-protected-content-wrap');
        if (!$wrapper.length) {
            return;
        }

        // 1. Password Visibility Eye Toggle (👁️)
        $wrapper.find('.ua-pc-eye-btn').off('click').on('click', function (e) {
            e.preventDefault();
            var $btn   = $(this);
            var $input = $btn.siblings('.ua-pc-input');
            var isPass = $input.attr('type') === 'password';

            if (isPass) {
                $input.attr('type', 'text');
                $btn.find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                $btn.find('i').removeClass('eicon-preview-medium').addClass('eicon-preview-light');
            } else {
                $input.attr('type', 'password');
                $btn.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                $btn.find('i').removeClass('eicon-preview-light').addClass('eicon-preview-medium');
            }
        });

        // 2. Ajax Password Form Submission
        $wrapper.find('.ua-pc-form').off('submit').on('submit', function (e) {
            e.preventDefault();

            var $form     = $(this);
            var $btn      = $form.find('.ua-pc-submit-btn');
            var $input    = $form.find('.ua-pc-input');
            var $error    = $form.find('.ua-pc-error');
            var password  = $input.val().trim();
            var widgetId  = $form.data('widget-id');
            var postId    = $form.data('post-id');
            var nonce     = $form.data('nonce');

            if (!password) {
                $error.text($error.data('empty-msg') || 'Please enter a password.').slideDown(200);
                $form.addClass('ua-pc-shake');
                setTimeout(function () {
                    $form.removeClass('ua-pc-shake');
                }, 500);
                $input.focus();
                return;
            }

            // Loading state
            $error.slideUp(150);
            $btn.addClass('ua-pc-btn-loading');
            var origBtnHtml = $btn.html();
            $btn.html('<span class="ua-pc-spinner"></span> ' + ($btn.data('loading-text') || 'Verifying...'));

            var ajaxUrl = (typeof ultraaddons_protected_content !== 'undefined' && ultraaddons_protected_content.ajax_url)
                ? ultraaddons_protected_content.ajax_url
                : (window.location.origin + '/wp-admin/admin-ajax.php');

            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'ultraaddons_verify_protected_content',
                    widget_id: widgetId,
                    post_id: postId,
                    password: password,
                    nonce: nonce
                },
                success: function (res) {
                    $btn.removeClass('ua-pc-btn-loading').html(origBtnHtml);

                    if (res.success && res.data && res.data.content) {
                        var $container = $wrapper.find('.ua-pc-gate-container');

                        $container.fadeOut(300, function () {
                            var $revealed = $('<div class="ua-pc-unlocked-content"></div>').html(res.data.content);
                            $wrapper.html($revealed);

                            // Trigger Elementor nested elements if any (sliders, tabs, tickers)
                            if (typeof elementorFrontend !== 'undefined' && elementorFrontend.elementsHandler) {
                                elementorFrontend.elementsHandler.runReadyTrigger($revealed);
                            }
                        });
                    } else {
                        var errMsg = (res.data && res.data.message) ? res.data.message : 'Incorrect password. Please try again.';
                        $error.text(errMsg).slideDown(200);
                        $form.addClass('ua-pc-shake');
                        setTimeout(function () {
                            $form.removeClass('ua-pc-shake');
                        }, 500);
                        $input.val('').focus();
                    }
                },
                error: function () {
                    $btn.removeClass('ua-pc-btn-loading').html(origBtnHtml);
                    $error.text('Server error. Please try again.').slideDown(200);
                    $form.addClass('ua-pc-shake');
                    setTimeout(function () {
                        $form.removeClass('ua-pc-shake');
                    }, 500);
                }
            });
        });
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ultraaddons-protected-content.default',
            UAProtectedContent
        );
    });

    $(document).ready(function () {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ultraaddons-protected-content.default',
                UAProtectedContent
            );
        } else {
            $('.ua-protected-content-wrap').each(function () {
                UAProtectedContent($(this).closest('.elementor-widget'));
            });
        }
    });

})(jQuery);
