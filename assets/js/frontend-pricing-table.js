/**
 * UltraAddons - Frontend Pricing Table Switcher JS
 *
 * Handles smooth live toggle between Monthly and Yearly pricing cycles.
 *
 * @package UltraAddons
 * @version 2.0.3.6
 */
(function ($) {
    'use strict';

    var UltraAddonsPricingTable = function ($scope) {
        var $wrapper = $scope.find('.ua-pricing-table-wrapper');
        if (!$wrapper.length) return;

        var $switcher = $wrapper.find('.ua-pricing-toggle-checkbox');
        if (!$switcher.length) return;

        var $card = $wrapper.find('.ua-pricing-card');
        var $amount = $wrapper.find('.ua-pricing-amount');
        var $subPrice = $wrapper.find('.ua-pricing-sub-price');
        var $period = $wrapper.find('.ua-pricing-period');
        var $origPrice = $wrapper.find('.ua-pricing-original-price');
        var $btn = $wrapper.find('.ua-pricing-btn');
        var $labelMonthly = $wrapper.find('.ua-switch-monthly');
        var $labelYearly = $wrapper.find('.ua-switch-yearly');

        function updatePricing(isYearly) {
            var targetAmount = isYearly ? $card.data('yearly-price') : $card.data('monthly-price');
            var targetSub = isYearly ? $card.data('yearly-sub') : $card.data('monthly-sub');
            var targetPeriod = isYearly ? $card.data('yearly-period') : $card.data('monthly-period');
            var targetOrig = isYearly ? $card.data('yearly-orig') : $card.data('monthly-orig');
            var targetUrl = isYearly ? $card.data('yearly-url') : $card.data('monthly-url');

            $amount.text(targetAmount);
            if ($subPrice.length) {
                $subPrice.text(targetSub !== undefined ? targetSub : '');
            }
            $period.text(targetPeriod);

            if (targetOrig && targetOrig.length) {
                $origPrice.text(targetOrig).show();
            } else {
                $origPrice.hide();
            }

            if (targetUrl && targetUrl.length) {
                $btn.attr('href', targetUrl);
            }

            if (isYearly) {
                $labelMonthly.removeClass('is-active');
                $labelYearly.addClass('is-active');
            } else {
                $labelMonthly.addClass('is-active');
                $labelYearly.removeClass('is-active');
            }
        }

        $switcher.on('change', function () {
            updatePricing($(this).is(':checked'));
        });

        $labelMonthly.on('click', function () {
            if ($switcher.is(':checked')) {
                $switcher.prop('checked', false).trigger('change');
            }
        });

        $labelYearly.on('click', function () {
            if (!$switcher.is(':checked')) {
                $switcher.prop('checked', true).trigger('change');
            }
        });
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-pricing-table.default', UltraAddonsPricingTable);
    });

})(jQuery);