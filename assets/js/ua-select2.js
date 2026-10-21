/**
 * UltraAddons Select2 Editor Control Script
 *
 * @package UltraAddons
 * @since 2.0.4.0
 */
(function ($) {
    'use strict';

    $(document).on('ua_select2_init', function (event, obj) {
        if (!obj || !obj.data) {
            return;
        }

        var ID = '#elementor-control-default-' + obj.data._cid;

        var attempts = 0;
        function tryInit() {
            var $target = $(ID);
            if (!$target.length && obj.data.name) {
                $target = $('select.ua-select2[data-setting="' + obj.data.name + '"]');
            }

            if ($target.length && typeof $target.select2 === 'function') {
                if ($target.hasClass('select2-hidden-accessible')) {
                    return;
                }

                var localize = window.ua_select2_localize || {};
                var ajaxurl = localize.ajaxurl || window.ajaxurl || '/wp-admin/admin-ajax.php';
                var nonce = localize.nonce || '';

                var IDSelect2 = $target.select2({
                    minimumInputLength: 3,
                    placeholder: obj.data.placeholder || localize.search_text || 'Search by Product Name',
                    allowClear: true,
                    ajax: {
                        type: 'POST',
                        url: ajaxurl,
                        dataType: 'json',
                        data: function (params) {
                            return {
                                action: 'ua_select2_search_post',
                                post_type: obj.data.source_type || 'product',
                                source_name: obj.data.source_name || 'post_type',
                                term: params.term,
                                nonce: nonce,
                            };
                        },
                    },
                    initSelection: function (element, callback) {
                        var ids = [];
                        if (!Array.isArray(obj.currentID) && obj.currentID != '' && obj.currentID != null && typeof obj.currentID !== 'undefined') {
                            ids = [obj.currentID];
                        } else if (Array.isArray(obj.currentID)) {
                            ids = obj.currentID.filter(function (el) {
                                return el != null && el != '' && typeof el !== 'undefined';
                            });
                        }

                        // If no IDs are selected, return empty result to avoid phantom empty choice
                        if (!ids.length) {
                            if (!obj.multiple) {
                                callback({ id: '', text: localize.search_text || 'Search products...' });
                            } else {
                                callback([]);
                            }
                            return;
                        }

                        var label = $("label[for='elementor-control-default-" + obj.data._cid + "']");
                        label.after('<span class="elementor-control-spinner">&nbsp;<i class="eicon-spinner eicon-animation-spin"></i>&nbsp;</span>');

                        $.ajax({
                            method: 'POST',
                            url: ajaxurl,
                            data: {
                                action: 'ua_select2_get_title',
                                post_type: obj.data.source_type || 'product',
                                source_name: obj.data.source_name || 'post_type',
                                id: ids,
                                nonce: nonce,
                            },
                        }).done(function (response) {
                            if (response && response.success && typeof response.data.results !== 'undefined') {
                                var optionsHtml = '';
                                var callbackData = [];
                                ids.forEach(function (item) {
                                    if (typeof response.data.results[item] !== 'undefined') {
                                        var key = item;
                                        var value = response.data.results[item];
                                        optionsHtml += '<option selected="selected" value="' + key + '">' + value + '</option>';
                                        callbackData.push({ id: key, text: value });
                                    }
                                });
                                element.html(optionsHtml);
                                if (!obj.multiple) {
                                    callback(callbackData[0] || { id: '', text: '' });
                                } else {
                                    callback(callbackData);
                                }
                            } else {
                                callback(obj.multiple ? [] : { id: '', text: '' });
                            }
                            label.siblings('.elementor-control-spinner').remove();
                        }).fail(function () {
                            callback(obj.multiple ? [] : { id: '', text: '' });
                            label.siblings('.elementor-control-spinner').remove();
                        });
                    },
                });

                // Drag-and-drop sortable for multiple select items
                setTimeout(function () {
                    if (obj.multiple && $.fn.sortable) {
                        var $rendered = IDSelect2.next('.select2-container').find('.select2-selection__rendered');
                        if (!$rendered.length) {
                            $rendered = IDSelect2.next().children().children().children();
                        }
                        if ($rendered.length) {
                            $rendered.sortable({
                                containment: 'parent',
                                stop: function () {
                                    $rendered.children('[title]').each(function () {
                                        var title = $(this).attr('title');
                                        var original = $('option:contains(' + title + ')', IDSelect2).first();
                                        original.detach();
                                        IDSelect2.append(original);
                                    });
                                    IDSelect2.trigger('change');
                                },
                            });
                        }
                    }

                    $target.on('select2:select', function (evt) {
                        var element = evt.params && evt.params.data && evt.params.data.element;
                        if (element) {
                            var $element = $(element);
                            $element.detach();
                            $(this).append($element);
                            $(this).trigger('change');
                        }
                    });
                }, 200);

            } else if (attempts < 25) {
                attempts++;
                setTimeout(tryInit, 50);
            }
        }

        setTimeout(tryInit, 50);
    });
})(jQuery);
