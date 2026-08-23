/**
 * UltraAddons Event Calendar Frontend Script
 * 
 * Interactive calendar engine supporting Month, Week, Day, and List views,
 * multi-language localization, custom start dates, hiding old events,
 * heading formats, date formatters, Google Calendar integration,
 * category filtering, live search, and modal popups.
 * 
 * @package UltraAddons
 * @version 1.0.0
 */
(function ($) {
    'use strict';

    var LOCALES = {
        en: {
            months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            daysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
        },
        bn: {
            months: ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'],
            monthsShort: ['জানু', 'ফেব্রু', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টে', 'অক্টো', 'নভে', 'ডিসে'],
            days: ['রবিবার', 'সোমবার', 'মঙ্গলবার', 'বুধবার', 'বৃহস্পতিবার', 'শুক্রবার', 'শনিবার'],
            daysShort: ['রবি', 'সোম', 'মঙ্গল', 'বুধ', 'বৃহঃ', 'শুক্র', 'শনি']
        },
        es: {
            months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            daysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']
        },
        fr: {
            months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthsShort: ['Janv', 'Févr', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
            days: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            daysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam']
        },
        de: {
            months: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
            monthsShort: ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'],
            days: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
            daysShort: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa']
        },
        ar: {
            months: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
            monthsShort: ['ينا', 'فبر', 'مار', 'أبر', 'ماي', 'يون', 'يول', 'أغس', 'سبت', 'أكت', 'نوف', 'ديس'],
            days: ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'],
            daysShort: ['أحد', 'اثنين', 'ثلاثاء', 'أربعاء', 'خميس', 'جمعة', 'سبت']
        },
        hi: {
            months: ['जनवरी', 'फ़रवरी', 'मार्च', 'अप्रैल', 'मई', 'जून', 'जुलाई', 'अगस्त', 'सितंबर', 'अक्टूबर', 'नवंबर', 'दिसंबर'],
            monthsShort: ['जन', 'फ़र', 'मार्च', 'अप्रै', 'मई', 'जून', 'जुला', 'अग', 'सित', 'अक्टू', 'नव', 'दिस'],
            days: ['रविवार', 'सोमवार', 'मंगलवार', 'बुधवार', 'गुरुवार', 'शुक्रवार', 'शनिवार'],
            daysShort: ['रवि', 'सोम', 'मंगल', 'बुध', 'गुरु', 'शुक्र', 'शनि']
        }
    };

    var UAEventCalendar = function ($wrapper) {
        this.$wrapper = $wrapper;
        this.$container = $wrapper.find('.ua-calendar-body');
        this.$title = $wrapper.find('.ua-calendar-title');
        this.$modal = $wrapper.find('.ua-event-modal');

        this.settings = $wrapper.data('settings') || {};
        this.firstDay = parseInt(this.settings.first_day, 10) || 0; // 0=Sun, 1=Mon, etc.
        this.currentView = this.settings.default_view || 'month';
        this.events = $wrapper.data('events') || [];
        this.activeCategory = 'all';
        this.searchQuery = '';
        this.eventLimit = parseInt(this.settings.event_limit, 10) || 3;

        var lang = this.settings.language || 'en';
        this.locale = LOCALES[lang] || LOCALES.en;

        // Date initialization
        if (this.settings.start_date_type === 'custom' && this.settings.custom_start_date) {
            var parts = this.settings.custom_start_date.split('-');
            if (parts.length === 3) {
                this.currentDate = new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
            } else {
                this.currentDate = new Date();
            }
        } else {
            this.currentDate = new Date();
        }

        this.init();
    };

    UAEventCalendar.prototype = {
        init: function () {
            var self = this;
            if (this.settings.event_source === 'google' && this.settings.google_api_key && this.settings.google_calendar_id) {
                this.fetchGoogleEvents(function () {
                    self.bindEvents();
                    self.render();
                });
            } else {
                this.bindEvents();
                this.render();
            }
        },

        fetchGoogleEvents: function (callback) {
            var self = this;
            var apiKey = this.settings.google_api_key;
            var calId = encodeURIComponent(this.settings.google_calendar_id);
            var maxResults = this.settings.google_max_results || 50;

            var url = 'https://www.googleapis.com/calendar/v3/calendars/' + calId + '/events?key=' + apiKey + '&singleEvents=true&orderBy=startTime&maxResults=' + maxResults;

            $.getJSON(url).done(function (data) {
                if (data && data.items) {
                    self.events = data.items.map(function (item) {
                        var start = item.start.dateTime || item.start.date || '';
                        var end = item.end.dateTime || item.end.date || '';
                        var isAllDay = !item.start.dateTime;

                        return {
                            title: item.summary || 'Google Event',
                            category: 'Google',
                            color: '#4a2db6',
                            text_color: '#ffffff',
                            ribbon_color: '#4a2db6',
                            start_date: start,
                            end_date: end,
                            all_day: isAllDay ? 'yes' : 'no',
                            location: item.location || '',
                            description: item.description || '',
                            image: '',
                            link: item.htmlLink || '',
                            redirect_to_link: 'no',
                            btn_text: 'View in Google Calendar',
                            btn_url: item.htmlLink || '#'
                        };
                    });
                }
                callback();
            }).fail(function () {
                callback();
            });
        },

        bindEvents: function () {
            var self = this;

            // Navigation buttons
            this.$wrapper.on('click', '.ua-cal-btn-prev', function () {
                self.navigate(-1);
            });

            this.$wrapper.on('click', '.ua-cal-btn-next', function () {
                self.navigate(1);
            });

            this.$wrapper.on('click', '.ua-cal-btn-today', function () {
                self.currentDate = new Date();
                self.render();
            });

            // View switch buttons
            this.$wrapper.on('click', '.ua-view-btn', function () {
                var view = $(this).data('view');
                if (view) {
                    self.currentView = view;
                    self.$wrapper.find('.ua-view-btn').removeClass('ua-active');
                    $(this).addClass('ua-active');
                    self.render();
                }
            });

            // Category filter buttons
            this.$wrapper.on('click', '.ua-cal-cat-btn', function () {
                var cat = $(this).data('category');
                self.activeCategory = cat || 'all';
                self.$wrapper.find('.ua-cal-cat-btn').removeClass('ua-active');
                $(this).addClass('ua-active');
                self.render();
            });

            // Search input
            this.$wrapper.on('input', '.ua-calendar-search-input', function () {
                self.searchQuery = $(this).val().toLowerCase().trim();
                self.render();
            });

            // Open event modal or redirect
            this.$wrapper.on('click', '.ua-event-pill, .ua-cal-list-item, .ua-event-card-item', function (e) {
                var eventIndex = $(this).data('event-index');
                if (typeof eventIndex !== 'undefined' && self.events[eventIndex]) {
                    var ev = self.events[eventIndex];
                    if (ev.redirect_to_link === 'yes' && ev.link) {
                        window.open(ev.link, '_blank');
                        return;
                    }
                    e.preventDefault();
                    self.openModal(ev);
                }
            });

            // Close modal
            this.$modal.on('click', '.ua-event-modal-close, .ua-event-modal-overlay', function () {
                self.closeModal();
            });

            $(document).on('keydown', function (e) {
                if (e.key === 'Escape' && self.$modal.hasClass('ua-open')) {
                    self.closeModal();
                }
            });
        },

        navigate: function (direction) {
            if (this.currentView === 'month') {
                this.currentDate.setMonth(this.currentDate.getMonth() + direction);
            } else if (this.currentView === 'week') {
                this.currentDate.setDate(this.currentDate.getDate() + (direction * 7));
            } else if (this.currentView === 'day') {
                this.currentDate.setDate(this.currentDate.getDate() + direction);
            } else if (this.currentView === 'list') {
                this.currentDate.setMonth(this.currentDate.getMonth() + direction);
            }
            this.render();
        },

        getDayNamesOrder: function (useFull) {
            var list = useFull ? this.locale.days : this.locale.daysShort;
            var names = [];
            for (var i = 0; i < 7; i++) {
                names.push(list[(this.firstDay + i) % 7]);
            }
            return names;
        },

        filterEvents: function (events) {
            var self = this;
            var todayStr = new Date().toISOString().split('T')[0];
            var startStr = self.settings.custom_start_date || todayStr;

            return events.filter(function (ev) {
                // Hide old events filter
                if (self.settings.hide_old_events === 'current') {
                    if (ev.start_date && ev.start_date.substring(0, 10) < todayStr) {
                        return false;
                    }
                } else if (self.settings.hide_old_events === 'start') {
                    if (ev.start_date && ev.start_date.substring(0, 10) < startStr) {
                        return false;
                    }
                }

                // Category filter
                if (self.activeCategory !== 'all' && ev.category !== self.activeCategory) {
                    return false;
                }

                // Search query
                if (self.searchQuery) {
                    var matchTitle = ev.title && ev.title.toLowerCase().indexOf(self.searchQuery) !== -1;
                    var matchDesc = ev.description && ev.description.toLowerCase().indexOf(self.searchQuery) !== -1;
                    var matchLocation = ev.location && ev.location.toLowerCase().indexOf(self.searchQuery) !== -1;
                    if (!matchTitle && !matchDesc && !matchLocation) {
                        return false;
                    }
                }
                return true;
            });
        },

        render: function () {
            this.updateHeaderTitle();

            if (this.currentView === 'month') {
                this.renderMonthView();
            } else if (this.currentView === 'week') {
                this.renderWeekView();
            } else if (this.currentView === 'day') {
                this.renderDayView();
            } else if (this.currentView === 'list') {
                this.renderListView();
            }
        },

        updateHeaderTitle: function () {
            var year = this.currentDate.getFullYear();
            var month = this.locale.months[this.currentDate.getMonth()];
            var date = this.currentDate.getDate();

            if (this.currentView === 'month' || this.currentView === 'list') {
                this.$title.html('<span class="ua-title-month">' + month + '</span> <span class="ua-title-year">' + year + '</span>');
            } else if (this.currentView === 'day') {
                this.$title.html('<span class="ua-title-month">' + month + ' ' + date + '</span> <span class="ua-title-year">' + year + '</span>');
            } else if (this.currentView === 'week') {
                var weekStart = new Date(this.currentDate);
                var dayIndex = weekStart.getDay();
                var diff = (dayIndex < this.firstDay ? 7 : 0) + dayIndex - this.firstDay;
                weekStart.setDate(weekStart.getDate() - diff);

                var weekEnd = new Date(weekStart);
                weekEnd.setDate(weekEnd.getDate() + 6);

                var startStr = this.locale.monthsShort[weekStart.getMonth()] + ' ' + weekStart.getDate();
                var endStr = this.locale.monthsShort[weekEnd.getMonth()] + ' ' + weekEnd.getDate() + ', ' + weekEnd.getFullYear();
                this.$title.html('<span class="ua-title-month">' + startStr + ' – ' + endStr + '</span>');
            }
        },

        renderMonthView: function () {
            var year = this.currentDate.getFullYear();
            var month = this.currentDate.getMonth();

            var firstDayOfMonth = new Date(year, month, 1);
            var lastDayOfMonth = new Date(year, month + 1, 0);
            var totalDays = lastDayOfMonth.getDate();

            var startDayIndex = firstDayOfMonth.getDay();
            var dayOffset = (startDayIndex < this.firstDay ? 7 : 0) + startDayIndex - this.firstDay;

            var prevMonthLastDay = new Date(year, month, 0).getDate();
            var useFullDays = this.settings.heading_format_month === 'dddd';
            var dayNames = this.getDayNamesOrder(useFullDays);

            var html = '<div class="ua-calendar-month-view">';
            
            // Weekday header
            html += '<div class="ua-calendar-weekdays">';
            for (var d = 0; d < 7; d++) {
                html += '<div class="ua-cal-weekday">' + dayNames[d] + '</div>';
            }
            html += '</div>';

            // Days grid
            html += '<div class="ua-calendar-days-grid">';

            var today = new Date();
            var isThisMonth = today.getFullYear() === year && today.getMonth() === month;

            // Previous month trailing days
            for (var p = dayOffset - 1; p >= 0; p--) {
                var prevDayNum = prevMonthLastDay - p;
                html += '<div class="ua-cal-day-cell ua-cal-day-other-month"><div class="ua-cal-day-header"><span class="ua-cal-day-num">' + prevDayNum + '</span></div></div>';
            }

            // Current month days
            var filteredEvents = this.filterEvents(this.events);

            for (var day = 1; day <= totalDays; day++) {
                var isToday = isThisMonth && today.getDate() === day;
                var cellClass = 'ua-cal-day-cell' + (isToday ? ' ua-cal-day-today' : '');

                var cellDateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');

                html += '<div class="' + cellClass + '" data-date="' + cellDateStr + '">';
                html += '<div class="ua-cal-day-header"><span class="ua-cal-day-num">' + day + '</span></div>';
                html += '<div class="ua-cal-day-events">';

                // Find matching events
                var dayEvents = filteredEvents.filter(function (ev) {
                    return ev.start_date && ev.start_date.indexOf(cellDateStr) === 0;
                });

                var maxVisible = this.eventLimit;
                for (var e = 0; e < dayEvents.length && e < maxVisible; e++) {
                    var ev = dayEvents[e];
                    var origIndex = this.events.indexOf(ev);
                    var pillStyle = 'style="';
                    if (ev.color) pillStyle += 'background-color:' + ev.color + ';';
                    if (ev.text_color) pillStyle += 'color:' + ev.text_color + ';';
                    pillStyle += '"';

                    var label = ev.title || 'Event';

                    html += '<div class="ua-event-pill" data-event-index="' + origIndex + '" ' + pillStyle + ' title="' + (ev.title || '') + '">';
                    html += '<span class="ua-event-pill-title">' + label + '</span>';
                    html += '</div>';
                }

                if (dayEvents.length > maxVisible) {
                    html += '<button type="button" class="ua-event-more-btn">+' + (dayEvents.length - maxVisible) + ' more</button>';
                }

                html += '</div>';
                html += '</div>';
            }

            // Next month days to fill grid
            var totalCells = dayOffset + totalDays;
            var nextMonthDays = (7 - (totalCells % 7)) % 7;
            for (var n = 1; n <= nextMonthDays; n++) {
                html += '<div class="ua-cal-day-cell ua-cal-day-other-month"><div class="ua-cal-day-header"><span class="ua-cal-day-num">' + n + '</span></div></div>';
            }

            html += '</div>'; // .ua-calendar-days-grid
            html += '</div>'; // .ua-calendar-month-view

            this.$container.html(html);
        },

        renderWeekView: function () {
            var weekStart = new Date(this.currentDate);
            var dayIndex = weekStart.getDay();
            var diff = (dayIndex < this.firstDay ? 7 : 0) + dayIndex - this.firstDay;
            weekStart.setDate(weekStart.getDate() - diff);

            var dayNames = this.getDayNamesOrder(false);
            var today = new Date();
            var filteredEvents = this.filterEvents(this.events);

            var html = '<div class="ua-calendar-week-view">';

            for (var i = 0; i < 7; i++) {
                var currentDay = new Date(weekStart);
                currentDay.setDate(weekStart.getDate() + i);

                var isToday = today.toDateString() === currentDay.toDateString();
                var dateStr = currentDay.getFullYear() + '-' + String(currentDay.getMonth() + 1).padStart(2, '0') + '-' + String(currentDay.getDate()).padStart(2, '0');

                var colClass = 'ua-cal-week-col' + (isToday ? ' ua-cal-day-today' : '');

                html += '<div class="' + colClass + '">';
                html += '<div class="ua-cal-week-col-head">';
                html += '<div class="ua-cal-week-col-name">' + dayNames[i] + '</div>';
                html += '<div class="ua-cal-week-col-date">' + currentDay.getDate() + '</div>';
                html += '</div>';

                html += '<div class="ua-cal-week-col-events">';

                var dayEvents = filteredEvents.filter(function (ev) {
                    return ev.start_date && ev.start_date.indexOf(dateStr) === 0;
                });

                if (dayEvents.length === 0) {
                    html += '<div class="ua-calendar-no-events" style="padding:15px 0;font-size:12px;">No events</div>';
                } else {
                    for (var e = 0; e < dayEvents.length; e++) {
                        var ev = dayEvents[e];
                        var origIndex = this.events.indexOf(ev);
                        var pillStyle = 'style="';
                        if (ev.color) pillStyle += 'background-color:' + ev.color + ';';
                        if (ev.text_color) pillStyle += 'color:' + ev.text_color + ';';
                        pillStyle += '"';

                        html += '<div class="ua-event-pill" data-event-index="' + origIndex + '" ' + pillStyle + '>';
                        html += '<span class="ua-event-pill-title">' + (ev.title || 'Event') + '</span>';
                        html += '</div>';
                    }
                }

                html += '</div>';
                html += '</div>';
            }

            html += '</div>';
            this.$container.html(html);
        },

        renderDayView: function () {
            var year = this.currentDate.getFullYear();
            var month = this.currentDate.getMonth();
            var day = this.currentDate.getDate();
            var dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');

            var weekdayName = this.locale.days[this.currentDate.getDay()];

            var filteredEvents = this.filterEvents(this.events);
            var dayEvents = filteredEvents.filter(function (ev) {
                return ev.start_date && ev.start_date.indexOf(dateStr) === 0;
            });

            var html = '<div class="ua-calendar-day-view">';
            html += '<div class="ua-cal-day-view-header">';
            html += '<div class="ua-cal-day-view-date">' + this.locale.months[month] + ' ' + day + ', ' + year + '</div>';
            html += '<div class="ua-cal-day-view-weekday">' + weekdayName + '</div>';
            html += '</div>';

            html += '<div class="ua-cal-day-view-events-list">';
            if (dayEvents.length === 0) {
                html += '<div class="ua-calendar-no-events">No events scheduled for this day.</div>';
            } else {
                for (var e = 0; e < dayEvents.length; e++) {
                    var ev = dayEvents[e];
                    var origIndex = this.events.indexOf(ev);

                    html += '<div class="ua-cal-list-item ua-event-card-item" data-event-index="' + origIndex + '">';
                    html += '<div class="ua-cal-list-left">';
                    html += '<div class="ua-cal-list-info">';
                    html += '<h4 class="ua-cal-list-title">' + (ev.title || 'Event') + '</h4>';
                    html += '<div class="ua-cal-list-meta">';
                    if (ev.location) {
                        html += '<span>📍 ' + ev.location + '</span>';
                    }
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                    if (ev.category) {
                        var catStyle = ev.color ? 'style="background-color:' + ev.color + ';"' : '';
                        html += '<span class="ua-cal-list-cat-badge" ' + catStyle + '>' + ev.category + '</span>';
                    }
                    html += '</div>';
                }
            }

            html += '</div>';
            html += '</div>';

            this.$container.html(html);
        },

        renderListView: function () {
            var filteredEvents = this.filterEvents(this.events);

            if (filteredEvents.length === 0) {
                this.$container.html('<div class="ua-calendar-no-events">No events found matching your search.</div>');
                return;
            }

            filteredEvents.sort(function (a, b) {
                return (a.start_date || '').localeCompare(b.start_date || '');
            });

            var html = '<div class="ua-calendar-list-view">';

            for (var i = 0; i < filteredEvents.length; i++) {
                var ev = filteredEvents[i];
                var origIndex = this.events.indexOf(ev);

                var evDate = ev.start_date ? new Date(ev.start_date) : new Date();
                var monthShort = !isNaN(evDate.getTime()) ? this.locale.monthsShort[evDate.getMonth()] : '';
                var dayNum = !isNaN(evDate.getTime()) ? evDate.getDate() : '';

                html += '<div class="ua-cal-list-item" data-event-index="' + origIndex + '">';
                html += '<div class="ua-cal-list-left">';
                html += '<div class="ua-cal-list-date-box">';
                html += '<span class="ua-cal-list-month">' + monthShort + '</span>';
                html += '<span class="ua-cal-list-day">' + dayNum + '</span>';
                html += '</div>';

                html += '<div class="ua-cal-list-info">';
                html += '<h4 class="ua-cal-list-title">' + (ev.title || 'Event') + '</h4>';
                html += '<div class="ua-cal-list-meta">';
                if (ev.location) {
                    html += '<span>📍 ' + ev.location + '</span>';
                }
                html += '</div>';
                html += '</div>';
                html += '</div>';

                html += '<div style="display:flex;align-items:center;gap:10px;">';
                if (ev.category) {
                    var catStyle = ev.color ? 'style="background-color:' + ev.color + ';"' : '';
                    html += '<span class="ua-cal-list-cat-badge" ' + catStyle + '>' + ev.category + '</span>';
                }
                if (ev.btn_text && !this.settings.hide_popup_link) {
                    html += '<a href="' + (ev.btn_url || '#') + '" class="ua-cal-list-btn" target="_blank">' + ev.btn_text + '</a>';
                }
                html += '</div>';

                html += '</div>';
            }

            html += '</div>';
            this.$container.html(html);
        },

        formatPopupDate: function (dateStr) {
            if (!dateStr) return '';
            var d = new Date(dateStr);
            if (isNaN(d.getTime())) return dateStr;

            var monthNames = this.locale.months;
            var monthShort = this.locale.monthsShort;
            var day = d.getDate();
            var month = d.getMonth();
            var year = d.getFullYear();

            var suffix = 'th';
            if (day === 1 || day === 21 || day === 31) suffix = 'st';
            else if (day === 2 || day === 22) suffix = 'nd';
            else if (day === 3 || day === 23) suffix = 'rd';

            var fmt = this.settings.popup_date_format || 'MMM Do';

            switch (fmt) {
                case 'MMM Do':
                    return monthShort[month] + ' ' + day + suffix;
                case 'MMMM Do':
                    return monthNames[month] + ' ' + day + suffix;
                case 'Do MMM':
                    return day + suffix + ' ' + monthShort[month];
                case 'Do MMMM':
                    return day + suffix + ' ' + monthNames[month];
                case 'MM-DD-YYYY':
                    return String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0') + '-' + year;
                case 'YYYY-DD-MM':
                    return year + '-' + String(day).padStart(2, '0') + '-' + String(month + 1).padStart(2, '0');
                case 'YYYY-MM-DD':
                    return year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
                case 'DD/MM/YYYY':
                    return String(day).padStart(2, '0') + '/' + String(month + 1).padStart(2, '0') + '/' + year;
                case 'MM/DD/YYYY':
                    return String(month + 1).padStart(2, '0') + '/' + String(day).padStart(2, '0') + '/' + year;
                case 'YYYY/MM/DD':
                    return year + '/' + String(month + 1).padStart(2, '0') + '/' + String(day).padStart(2, '0');
                case 'D-MMM-YYYY':
                    return day + '-' + monthShort[month] + '-' + year;
                case 'MMMM YYYY':
                    return monthNames[month] + ' ' + year;
                case 'MMM YYYY':
                    return monthShort[month] + ' ' + year;
                default:
                    return monthShort[month] + ' ' + day + suffix;
            }
        },

        openModal: function (eventData) {
            var $dialog = this.$modal.find('.ua-event-modal-dialog');

            var bannerHtml = '';
            if (eventData.image) {
                bannerHtml = '<img src="' + eventData.image + '" alt="' + (eventData.title || '') + '" class="ua-event-modal-banner" />';
            }

            var ribbonColor = this.settings.popup_ribbon_color || eventData.ribbon_color || eventData.color || '#10ecab';
            var catHtml = '';
            if (eventData.category) {
                catHtml = '<span class="ua-event-modal-category" style="background-color:' + ribbonColor + ';">' + eventData.category + '</span>';
            }

            var formattedDate = this.formatPopupDate(eventData.start_date);
            var metaHtml = '<div class="ua-event-modal-meta-list">';
            if (formattedDate) {
                metaHtml += '<div class="ua-event-modal-meta-item"><span>📅</span><span>' + formattedDate + '</span></div>';
            }
            if (eventData.location) {
                metaHtml += '<div class="ua-event-modal-meta-item"><span>📍</span><span>' + eventData.location + '</span></div>';
            }
            metaHtml += '</div>';

            var descHtml = '';
            if (eventData.description) {
                descHtml = '<div class="ua-event-modal-desc">' + eventData.description + '</div>';
            }

            var btnHtml = '';
            if (!this.settings.hide_popup_link && eventData.btn_text && eventData.btn_url) {
                btnHtml = '<a href="' + eventData.btn_url + '" class="ua-event-modal-btn" target="_blank">' + eventData.btn_text + '</a>';
            }

            var bodyHtml = '<button type="button" class="ua-event-modal-close">✕</button>' +
                bannerHtml +
                '<div class="ua-event-modal-body">' +
                catHtml +
                '<h3 class="ua-event-modal-title">' + (eventData.title || 'Event Details') + '</h3>' +
                metaHtml +
                descHtml +
                btnHtml +
                '</div>';

            $dialog.html(bodyHtml);
            this.$modal.addClass('ua-open');
        },

        closeModal: function () {
            this.$modal.removeClass('ua-open');
        }
    };

    // Elementor Hook Initialization
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/ultraaddons-event-calendar.default', function ($scope) {
            var $wrapper = $scope.find('.ua-event-calendar');
            if ($wrapper.length) {
                new UAEventCalendar($wrapper);
            }
        });
    });

    // Standalone fallback initialization
    $(document).ready(function () {
        $('.ua-event-calendar').each(function () {
            if (!$(this).data('ua-calendar-initialized')) {
                $(this).data('ua-calendar-initialized', true);
                new UAEventCalendar($(this));
            }
        });
    });

})(jQuery);
