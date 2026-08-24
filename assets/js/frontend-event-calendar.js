/**
 * UltraAddons Event Calendar Frontend Script
 * 
 * Full interactive calendar engine supporting:
 * - Month Grid View (with today highlight & multi-event popover)
 * - Week TimeGrid View (with hourly slots, all-day row & 7 day columns)
 * - Day TimeGrid View (with hourly slots & all-day row)
 * - List / Agenda View (with date badge & meta)
 * - Table Layout (with live search & pagination)
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

    var HOURS_12 = ['12am', '1am', '2am', '3am', '4am', '5am', '6am', '7am', '8am', '9am', '10am', '11am', '12pm', '1pm', '2pm', '3pm', '4pm', '5pm', '6pm', '7pm', '8pm', '9pm', '10pm', '11pm'];
    var HOURS_24 = ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'];

    var UAEventCalendar = function ($wrapper) {
        this.$wrapper = $wrapper;
        this.$container = $wrapper.find('.ua-calendar-body');
        this.$title = $wrapper.find('.ua-calendar-title');
        this.$modal = $wrapper.find('.ua-event-modal');

        this.settings = $wrapper.data('settings') || {};
        this.layout = this.settings.layout || 'calendar';
        this.firstDay = parseInt(this.settings.first_day, 10) || 0; // 0=Sun, 1=Mon
        this.currentView = this.settings.default_view || 'month';
        this.events = $wrapper.data('events') || [];
        this.activeCategory = 'all';
        this.searchQuery = '';
        this.eventLimit = parseInt(this.settings.event_limit, 10) || 3;
        this.currentPage = 1;

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
            this.$wrapper.on('input', '.ua-calendar-search-input, .ua-table-search-input', function () {
                self.searchQuery = $(this).val().toLowerCase().trim();
                self.currentPage = 1;
                self.render();
            });

            // Table pagination
            this.$wrapper.on('click', '.ua-table-page-btn', function () {
                var p = $(this).data('page');
                if (p) {
                    self.currentPage = parseInt(p, 10);
                    self.render();
                }
            });

            // Open event modal or redirect
            this.$wrapper.on('click', '.ua-event-pill, .ua-timegrid-event, .ua-cal-list-item, .ua-event-row-title, .ua-table-details-btn', function (e) {
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

        parseEventHour: function (ev) {
            if (!ev.start_date) return 0;
            // e.g. 2026-08-24 10:00 or 2026-08-24T10:00:00 or 10:00 AM
            var str = ev.start_date;
            var timeMatch = str.match(/(\d{1,2}):(\d{2})(?::\d{2})?\s*(AM|PM)?/i);
            if (timeMatch) {
                var hour = parseInt(timeMatch[1], 10);
                var isPM = timeMatch[3] && timeMatch[3].toUpperCase() === 'PM';
                var isAM = timeMatch[3] && timeMatch[3].toUpperCase() === 'AM';
                if (isPM && hour < 12) hour += 12;
                if (isAM && hour === 12) hour = 0;
                return hour;
            }
            return 0;
        },

        render: function () {
            if (this.layout === 'table') {
                this.renderTableView();
                return;
            }

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
                this.$title.html('<span class="ua-title-month">' + month + ' ' + year + '</span>');
            } else if (this.currentView === 'day') {
                this.$title.html('<span class="ua-title-month">' + month + ' ' + date + ', ' + year + '</span>');
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

        renderDayView: function () {
            var year = this.currentDate.getFullYear();
            var month = this.currentDate.getMonth();
            var day = this.currentDate.getDate();
            var dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');

            var weekdayName = this.locale.days[this.currentDate.getDay()];
            var today = new Date();
            var isToday = today.toDateString() === this.currentDate.toDateString();

            var filteredEvents = this.filterEvents(this.events);
            var dayEvents = filteredEvents.filter(function (ev) {
                return ev.start_date && ev.start_date.indexOf(dateStr) === 0;
            });

            var hoursList = this.settings.time_format_24 ? HOURS_24 : HOURS_12;
            // Start from 6am (hour 6) through 11pm (hour 23)
            var startHour = 6;

            var html = '<div class="ua-timegrid-view ua-timegrid-day">';
            
            // Header table
            html += '<table class="ua-timegrid-header-table">';
            html += '<thead><tr>';
            html += '<th class="ua-timegrid-axis-cell"></th>';
            html += '<th class="ua-timegrid-col-header' + (isToday ? ' ua-today-col' : '') + '">' + weekdayName + '</th>';
            html += '</tr></thead>';
            html += '</table>';

            // All-day row
            var allDayEvents = dayEvents.filter(function (ev) { return ev.all_day === 'yes'; });
            html += '<div class="ua-timegrid-allday-row">';
            html += '<div class="ua-timegrid-allday-label">all-day</div>';
            html += '<div class="ua-timegrid-allday-content' + (isToday ? ' ua-today-cell' : '') + '">';
            for (var a = 0; a < allDayEvents.length; a++) {
                var aEv = allDayEvents[a];
                var aIdx = this.events.indexOf(aEv);
                var aStyle = aEv.color ? 'background-color:' + aEv.color + ';' : '';
                if (aEv.text_color) aStyle += 'color:' + aEv.text_color + ';';
                html += '<div class="ua-timegrid-event ua-event-pill" data-event-index="' + aIdx + '" style="' + aStyle + '">';
                html += '<span class="ua-event-pill-title">' + (aEv.title || 'Event') + '</span>';
                html += '</div>';
            }
            html += '</div>';
            html += '</div>';

            // Scrollable timegrid body
            html += '<div class="ua-timegrid-scroll-body">';
            html += '<table class="ua-timegrid-body-table">';
            html += '<tbody>';

            var timedEvents = dayEvents.filter(function (ev) { return ev.all_day !== 'yes'; });

            for (var h = startHour; h < 24; h++) {
                var hourLabel = hoursList[h];
                html += '<tr class="ua-timegrid-hour-row">';
                html += '<td class="ua-timegrid-time-label"><span>' + hourLabel + '</span></td>';
                html += '<td class="ua-timegrid-slot-cell' + (isToday ? ' ua-today-cell' : '') + '" data-hour="' + h + '">';

                // Find events for this hour
                for (var t = 0; t < timedEvents.length; t++) {
                    var tEv = timedEvents[t];
                    var evH = this.parseEventHour(tEv);
                    if (evH === h) {
                        var tIdx = this.events.indexOf(tEv);
                        var tStyle = tEv.color ? 'background-color:' + tEv.color + ';' : '';
                        if (tEv.text_color) tStyle += 'color:' + tEv.text_color + ';';
                        html += '<div class="ua-timegrid-event" data-event-index="' + tIdx + '" style="' + tStyle + '">';
                        html += '<div class="ua-timegrid-event-title">' + (tEv.title || 'Event') + '</div>';
                        if (tEv.location) {
                            html += '<div class="ua-timegrid-event-loc">📍 ' + tEv.location + '</div>';
                        }
                        html += '</div>';
                    }
                }

                html += '</td>';
                html += '</tr>';
            }

            html += '</tbody>';
            html += '</table>';
            html += '</div>'; // .ua-timegrid-scroll-body
            html += '</div>'; // .ua-timegrid-view

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
            var hoursList = this.settings.time_format_24 ? HOURS_24 : HOURS_12;
            var startHour = 6;

            var daysData = [];
            for (var d = 0; d < 7; d++) {
                var cDay = new Date(weekStart);
                cDay.setDate(weekStart.getDate() + d);
                var isToday = today.toDateString() === cDay.toDateString();
                var dateStr = cDay.getFullYear() + '-' + String(cDay.getMonth() + 1).padStart(2, '0') + '-' + String(cDay.getDate()).padStart(2, '0');
                
                daysData.push({
                    dateObj: cDay,
                    name: dayNames[d],
                    dateNum: cDay.getDate(),
                    dateStr: dateStr,
                    isToday: isToday,
                    events: filteredEvents.filter(function (ev) {
                        return ev.start_date && ev.start_date.indexOf(dateStr) === 0;
                    })
                });
            }

            var html = '<div class="ua-timegrid-view ua-timegrid-week">';

            // Header table with 7 columns
            html += '<table class="ua-timegrid-header-table">';
            html += '<thead><tr>';
            html += '<th class="ua-timegrid-axis-cell"></th>';
            for (var i = 0; i < 7; i++) {
                var dInfo = daysData[i];
                html += '<th class="ua-timegrid-col-header' + (dInfo.isToday ? ' ua-today-col' : '') + '">';
                html += '<div class="ua-week-hdr-name">' + dInfo.name + ' ' + dInfo.dateNum + '</div>';
                html += '</th>';
            }
            html += '</tr></thead>';
            html += '</table>';

            // All-day row
            html += '<div class="ua-timegrid-allday-row">';
            html += '<div class="ua-timegrid-allday-label">all-day</div>';
            html += '<div class="ua-timegrid-allday-cols">';
            for (var j = 0; j < 7; j++) {
                var dayJ = daysData[j];
                var allDayJ = dayJ.events.filter(function (ev) { return ev.all_day === 'yes'; });
                html += '<div class="ua-timegrid-allday-cell' + (dayJ.isToday ? ' ua-today-cell' : '') + '">';
                for (var aj = 0; aj < allDayJ.length; aj++) {
                    var ajEv = allDayJ[aj];
                    var ajIdx = this.events.indexOf(ajEv);
                    var ajStyle = ajEv.color ? 'background-color:' + ajEv.color + ';' : '';
                    if (ajEv.text_color) ajStyle += 'color:' + ajEv.text_color + ';';
                    html += '<div class="ua-timegrid-event ua-event-pill" data-event-index="' + ajIdx + '" style="' + ajStyle + '">';
                    html += '<span class="ua-event-pill-title">' + (ajEv.title || 'Event') + '</span>';
                    html += '</div>';
                }
                html += '</div>';
            }
            html += '</div>';
            html += '</div>';

            // Scrollable timegrid body
            html += '<div class="ua-timegrid-scroll-body">';
            html += '<table class="ua-timegrid-body-table">';
            html += '<tbody>';

            for (var h = startHour; h < 24; h++) {
                var hLabel = hoursList[h];
                html += '<tr class="ua-timegrid-hour-row">';
                html += '<td class="ua-timegrid-time-label"><span>' + hLabel + '</span></td>';

                for (var c = 0; c < 7; c++) {
                    var dayC = daysData[c];
                    var timedC = dayC.events.filter(function (ev) { return ev.all_day !== 'yes'; });
                    html += '<td class="ua-timegrid-slot-cell' + (dayC.isToday ? ' ua-today-cell' : '') + '">';

                    for (var tc = 0; tc < timedC.length; tc++) {
                        var evC = timedC[tc];
                        if (this.parseEventHour(evC) === h) {
                            var cIdx = this.events.indexOf(evC);
                            var cStyle = evC.color ? 'background-color:' + evC.color + ';' : '';
                            if (evC.text_color) cStyle += 'color:' + evC.text_color + ';';
                            html += '<div class="ua-timegrid-event" data-event-index="' + cIdx + '" style="' + cStyle + '">';
                            html += '<div class="ua-timegrid-event-title">' + (evC.title || 'Event') + '</div>';
                            html += '</div>';
                        }
                    }

                    html += '</td>';
                }

                html += '</tr>';
            }

            html += '</tbody>';
            html += '</table>';
            html += '</div>'; // .ua-timegrid-scroll-body
            html += '</div>'; // .ua-timegrid-view

            this.$container.html(html);
        },

        renderListView: function () {
            var filteredEvents = this.filterEvents(this.events);

            if (filteredEvents.length === 0) {
                this.$container.html('<div class="ua-calendar-no-events">No events found.</div>');
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

        renderTableView: function () {
            var filteredEvents = this.filterEvents(this.events);
            var perPage = this.settings.table_item_per_page || 10;
            var showSearch = this.settings.table_show_search !== false;
            var showPagination = this.settings.table_show_pagination !== false;

            var html = '<div class="ua-event-table-wrapper">';

            if (showSearch) {
                html += '<div class="ua-table-search-wrap">';
                if (this.settings.table_search_label) {
                    html += '<label class="ua-table-search-label">' + this.settings.table_search_label + '</label>';
                }
                html += '<input type="text" class="ua-table-search-input" placeholder="' + (this.settings.table_search_placeholder || 'Search') + '" value="' + this.searchQuery + '" />';
                html += '</div>';
            }

            var totalItems = filteredEvents.length;
            var totalPages = Math.ceil(totalItems / perPage) || 1;
            if (this.currentPage > totalPages) this.currentPage = 1;

            var startIndex = (this.currentPage - 1) * perPage;
            var pageEvents = filteredEvents.slice(startIndex, startIndex + perPage);

            html += '<div class="ua-table-responsive">';
            html += '<table class="ua-event-table">';
            html += '<thead><tr>';

            if (this.settings.table_show_date !== false) {
                html += '<th>' + (this.settings.table_date_label || 'Date') + '</th>';
            }
            if (this.settings.table_show_title !== false) {
                html += '<th>' + (this.settings.table_title_label || 'Title') + '</th>';
            }
            if (this.settings.table_show_description !== false) {
                html += '<th>' + (this.settings.table_desc_label || 'Description') + '</th>';
            }
            html += '<th>Category</th>';
            html += '<th>Action</th>';
            html += '</tr></thead>';

            html += '<tbody>';
            if (pageEvents.length === 0) {
                html += '<tr><td colspan="5" style="text-align:center;padding:20px;">No events found</td></tr>';
            } else {
                for (var i = 0; i < pageEvents.length; i++) {
                    var ev = pageEvents[i];
                    var origIdx = this.events.indexOf(ev);

                    html += '<tr>';
                    if (this.settings.table_show_date !== false) {
                        html += '<td><strong>' + (ev.start_date || '') + '</strong></td>';
                    }
                    if (this.settings.table_show_title !== false) {
                        html += '<td><a href="#" class="ua-event-row-title" data-event-index="' + origIdx + '"><strong>' + (ev.title || '') + '</strong></a></td>';
                    }
                    if (this.settings.table_show_description !== false) {
                        var desc = ev.description || '';
                        var limit = this.settings.table_desc_limit || 20;
                        var words = desc.split(' ');
                        if (words.length > limit) {
                            desc = words.slice(0, limit).join(' ') + '...';
                        }
                        html += '<td>' + desc + '</td>';
                    }
                    html += '<td><span class="ua-cal-cat-btn" style="padding:4px 10px;font-size:12px;background:' + (ev.color || '#4a2db6') + ';color:#fff;">' + (ev.category || 'Event') + '</span></td>';
                    html += '<td><button type="button" class="ua-table-details-btn" data-event-index="' + origIdx + '">View Details</button></td>';
                    html += '</tr>';
                }
            }
            html += '</tbody>';
            html += '</table>';
            html += '</div>'; // .ua-table-responsive

            // Pagination
            if (showPagination && totalPages > 1) {
                html += '<div class="ua-table-pagination">';
                for (var p = 1; p <= totalPages; p++) {
                    var pClass = 'ua-table-page-btn' + (p === this.currentPage ? ' ua-active' : '');
                    html += '<button type="button" class="' + pClass + '" data-page="' + p + '">' + p + '</button>';
                }
                html += '</div>';
            }

            html += '</div>'; // .ua-event-table-wrapper

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

        formatEventTime: function (startStr, endStr, allDay) {
            if (allDay === 'yes') return 'All Day';
            if (!startStr) return '';
            
            var s = new Date(startStr);
            if (isNaN(s.getTime()) && startStr.indexOf(' ') !== -1) {
                s = new Date(startStr.replace(' ', 'T'));
            }
            if (isNaN(s.getTime())) return '';

            var formatOptions = { hour: 'numeric', minute: '2-digit', hour12: !this.settings.time_format_24 };
            var startTimeStr = s.toLocaleTimeString([], formatOptions);

            if (endStr) {
                var e = new Date(endStr);
                if (isNaN(e.getTime()) && endStr.indexOf(' ') !== -1) {
                    e = new Date(endStr.replace(' ', 'T'));
                }
                if (!isNaN(e.getTime())) {
                    var endTimeStr = e.toLocaleTimeString([], formatOptions);
                    return startTimeStr + ' – ' + endTimeStr;
                }
            }
            return startTimeStr;
        },

        openModal: function (eventData) {
            var $dialog = this.$modal.find('.ua-event-modal-dialog');

            var bannerHtml = '';
            if (eventData.image) {
                bannerHtml = '<img src="' + eventData.image + '" alt="' + (eventData.title || '') + '" class="ua-event-modal-banner" />';
            }

            var ribbonColor = eventData.ribbon_color || eventData.color;
            var catHtml = '';
            if (eventData.category) {
                var catStyle = (ribbonColor && ribbonColor !== '#10ecab' && ribbonColor !== '#4a2db6') ? 'style="background-color:' + ribbonColor + ';"' : '';
                catHtml = '<span class="ua-event-modal-category" ' + catStyle + '>' + eventData.category + '</span>';
            }

            var formattedDate = this.formatPopupDate(eventData.start_date);
            var formattedTime = this.formatEventTime(eventData.start_date, eventData.end_date, eventData.all_day);

            var metaHtml = '<div class="ua-event-modal-meta-list">';
            if (formattedDate) {
                metaHtml += '<div class="ua-event-modal-meta-item"><span>📅</span><span>' + formattedDate + '</span></div>';
            }
            if (formattedTime) {
                metaHtml += '<div class="ua-event-modal-meta-item"><span>⏰</span><span>' + formattedTime + '</span></div>';
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
