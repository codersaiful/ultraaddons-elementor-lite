/**
 * UltraAddons - Mailchimp Frontend Interactivity
 * Handles AJAX email subscription, client-side validation, GDPR check, and smooth feedback.
 */

(function ($) {
    'use strict';

    var UAMailchimp = function ($scope) {
        var $wrapper = $scope.find('.ua-mailchimp-wrapper');
        if (!$wrapper.length) {
            return;
        }

        var $form = $wrapper.find('.ua-mailchimp-form');
        var $msgBox = $wrapper.find('.ua-mailchimp-message');
        var $submitBtn = $form.find('.ua-mailchimp-submit');

        $form.on('submit', function (e) {
            e.preventDefault();

            // Clear previous alerts
            $msgBox.removeClass('ua-mailchimp-message-success ua-mailchimp-message-error ua-mailchimp-shake').hide().text('');

            // Validate Email
            var $emailInput = $form.find('input[name="ua_mc_email"]');
            var emailVal = $.trim($emailInput.val());
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailVal || !emailRegex.test(emailVal)) {
                $msgBox.addClass('ua-mailchimp-message-error ua-mailchimp-shake')
                    .text($wrapper.data('msg-invalid-email') || 'Please enter a valid email address.')
                    .show();
                $emailInput.focus();
                return;
            }

            // Validate GDPR Checkbox if present
            var $gdprCheck = $form.find('input[name="ua_mc_gdpr"]');
            if ($gdprCheck.length && !$gdprCheck.is(':checked')) {
                $msgBox.addClass('ua-mailchimp-message-error ua-mailchimp-shake')
                    .text($wrapper.data('msg-gdpr-required') || 'You must agree to the terms to subscribe.')
                    .show();
                $gdprCheck.focus();
                return;
            }

            // Start Loading state
            $form.addClass('ua-mailchimp-loading');
            $submitBtn.prop('disabled', true);

            // Collect form data
            var formData = {
                action: 'ultraaddons_mailchimp_subscribe',
                nonce: $wrapper.data('nonce') || '',
                widget_id: $scope.data('id') || '',
                email: emailVal,
                fname: $.trim($form.find('input[name="ua_mc_fname"]').val() || ''),
                lname: $.trim($form.find('input[name="ua_mc_lname"]').val() || ''),
                phone: $.trim($form.find('input[name="ua_mc_phone"]').val() || ''),
                api_key: $wrapper.data('api-key') || '',
                list_id: $wrapper.data('list-id') || '',
                double_optin: $wrapper.data('double-optin') || 'no',
                tags: $wrapper.data('tags') || '',
                redirect_url: $wrapper.data('redirect-url') || ''
            };

            var ajaxUrl = $wrapper.data('ajax-url') || (window.ULTRAADDONS_DATA && window.ULTRAADDONS_DATA.ajax_url) || window.ajaxurl || '/wp-admin/admin-ajax.php';

            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (res) {
                    $form.removeClass('ua-mailchimp-loading');
                    $submitBtn.prop('disabled', false);

                    if (res && res.success) {
                        $msgBox.addClass('ua-mailchimp-message-success')
                            .text(res.data && res.data.message ? res.data.message : 'Thank you for subscribing!')
                            .fadeIn(300);

                        // Reset input fields
                        $form.find('input:not([type="submit"]):not([type="hidden"])').val('');
                        if ($gdprCheck.length) {
                            $gdprCheck.prop('checked', false);
                        }

                        // Redirect if set
                        if (res.data && res.data.redirect) {
                            setTimeout(function () {
                                window.location.href = res.data.redirect;
                            }, 1200);
                        }
                    } else {
                        var errMsg = (res && res.data && res.data.message) ? res.data.message : 'Subscription failed. Please try again.';
                        $msgBox.addClass('ua-mailchimp-message-error ua-mailchimp-shake')
                            .text(errMsg)
                            .fadeIn(300);
                    }
                },
                error: function (xhr) {
                    $form.removeClass('ua-mailchimp-loading');
                    $submitBtn.prop('disabled', false);

                    var errText = 'Server connection error. Please try again later.';
                    if (xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message) {
                        errText = xhr.responseJSON.data.message;
                    } else if (xhr.responseText) {
                        try {
                            var parsed = JSON.parse(xhr.responseText);
                            if (parsed && parsed.data && parsed.data.message) {
                                errText = parsed.data.message;
                            }
                        } catch (e) {}
                    }

                    $msgBox.addClass('ua-mailchimp-message-error ua-mailchimp-shake')
                        .text(errText)
                        .fadeIn(300);
                }
            });
        });
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ultraaddons-mailchimp.default',
            UAMailchimp
        );
    });

    $(document).ready(function () {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ultraaddons-mailchimp.default',
                UAMailchimp
            );
        } else {
            $('.ua-mailchimp-wrapper').each(function () {
                UAMailchimp($(this).closest('.elementor-widget'));
            });
        }
    });

})(jQuery);
