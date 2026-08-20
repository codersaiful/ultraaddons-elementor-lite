/**
 * UltraAddons Countdown Timer Widget JavaScript
 * 
 * @since 2.0.3
 */
;(function ($, window) {
    'use strict';

    // Global registry for active timer intervals by widget ID
    window.uaCountdownTimers = window.uaCountdownTimers || {};

    var UltraAddonsCountdown = {
        init: function ($scope) {
            var $countDownWrap = $scope.find('.ua-countdown-wrap');
            if (!$countDownWrap.length) {
                return;
            }

            var widgetId = $scope.data('id') || $countDownWrap.attr('id') || 'ua-timer-' + Math.floor(Math.random() * 100000);

            // ALWAYS clear any existing global interval for this widget instance
            if (window.uaCountdownTimers[widgetId]) {
                clearInterval(window.uaCountdownTimers[widgetId]);
                delete window.uaCountdownTimers[widgetId];
            }

            var type = $countDownWrap.data('type') || 'due-date';
            var dataInterval = parseInt($countDownWrap.data('interval'), 10) || 0;
            var dataShowAgain = parseInt($countDownWrap.data('show-again'), 10) || 0;
            var actionsData = $countDownWrap.data('actions') || {};
            var isEditor = $('body').hasClass('elementor-editor-active') || (typeof elementor !== 'undefined');
            var endTime;

            if (type === 'evergreen') {
                var storageKey = 'ua_countdown_' + widgetId;
                var storedData = null;
                var now = new Date().getTime();

                if (!isEditor) {
                    try {
                        storedData = JSON.parse(localStorage.getItem(storageKey));
                    } catch (e) {
                        storedData = null;
                    }
                }

                if (storedData && storedData.endTime && storedData.interval === dataInterval) {
                    if (storedData.endTime < now) {
                        var delayMs = dataShowAgain * 60 * 60 * 1000;
                        if (dataShowAgain > 0 && (now - storedData.endTime) >= delayMs) {
                            endTime = now + (dataInterval * 1000);
                            try {
                                localStorage.setItem(storageKey, JSON.stringify({ endTime: endTime, interval: dataInterval }));
                            } catch (e) {}
                        } else {
                            endTime = storedData.endTime;
                        }
                    } else {
                        endTime = storedData.endTime;
                    }
                } else {
                    endTime = now + (dataInterval * 1000);
                    if (!isEditor) {
                        try {
                            localStorage.setItem(storageKey, JSON.stringify({ endTime: endTime, interval: dataInterval }));
                        } catch (e) {}
                    }
                }
            } else {
                // Fixed Due Date timestamp (milliseconds)
                endTime = dataInterval * 1000;
            }

            function updateTimer() {
                var currentTime = new Date().getTime();
                var remaining = endTime - currentTime;

                if (remaining <= 0) {
                    if (window.uaCountdownTimers[widgetId]) {
                        clearInterval(window.uaCountdownTimers[widgetId]);
                        delete window.uaCountdownTimers[widgetId];
                    }
                    remaining = 0;
                    handleExpiredActions();
                }

                var seconds = Math.floor((remaining / 1000) % 60);
                var minutes = Math.floor((remaining / (1000 * 60)) % 60);
                var hours = Math.floor((remaining / (1000 * 60 * 60)) % 24);
                var days = Math.floor(remaining / (1000 * 60 * 60 * 24));

                var timeObj = {
                    days: days < 10 ? '0' + days : '' + days,
                    hours: hours < 10 ? '0' + hours : '' + hours,
                    minutes: minutes < 10 ? '0' + minutes : '' + minutes,
                    seconds: seconds < 10 ? '0' + seconds : '' + seconds
                };

                $scope.find('.ua-countdown-number').each(function () {
                    var $this = $(this);
                    var item = $this.data('item');
                    if (item && timeObj.hasOwnProperty(item)) {
                        var currentText = $this.text();
                        if (currentText !== timeObj[item]) {
                            $this.text(timeObj[item]);
                        }

                        // Dynamic singular / plural label switching
                        var $label = $this.siblings('.ua-countdown-label');
                        if ($label.length && $label.data('labels')) {
                            var labelData = $label.data('labels');
                            if (typeof labelData === 'string') {
                                try {
                                    labelData = JSON.parse(labelData);
                                } catch (e) {}
                            }
                            if (labelData && labelData.singular && labelData.plural) {
                                var targetLabel = (timeObj[item] === '01') ? labelData.singular : labelData.plural;
                                if ($label.text() !== targetLabel) {
                                    $label.text(targetLabel);
                                }
                            }
                        }
                    }
                });
            }

            function handleExpiredActions() {
                if (isEditor) {
                    return; // Do not redirect or hide while editing
                }

                if (actionsData['hide-timer']) {
                    $countDownWrap.hide();
                }

                if (actionsData['hide-element'] && actionsData['hide-element'].length) {
                    $(actionsData['hide-element']).hide();
                }

                if (actionsData['message']) {
                    if (!$scope.find('.ua-countdown-message').length) {
                        $countDownWrap.after('<div class="ua-countdown-message">' + actionsData['message'] + '</div>');
                    }
                }

                if (actionsData['load-template']) {
                    $scope.find('.ua-countdown-template-wrap').show();
                }

                if (actionsData['redirect'] && actionsData['redirect'] !== '#' && actionsData['redirect'].length > 1) {
                    try {
                        var targetUrl = actionsData['redirect'];
                        window.location.href = targetUrl;
                    } catch (e) {}
                }
            }

            // Run first tick immediately
            updateTimer();

            // Set single clean interval and register globally
            var timerInterval = setInterval(updateTimer, 1000);
            window.uaCountdownTimers[widgetId] = timerInterval;
        }
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-countdown-timer.default', function ($scope) {
            UltraAddonsCountdown.init($scope);
        });
    });

})(jQuery, window);
