! function(e) {
    "use strict";
    var t, i, n, a = window.ElementsKitLibreryData || {};
    i = {
        LibraryLayoutView: null,
        LibraryHeaderView: null,
        LibraryLoadingView: null,
        LibraryErrorView: null,
        LibraryBodyView: null,
        LibraryCollectionView: null,
        FiltersCollectionView: null,
        LibraryTabsCollectionView: null,
        LibraryTabsItemView: null,
        FiltersItemView: null,
        LibraryTemplateItemView: null,
        LibraryInsertTemplateBehavior: null,
        LibraryTabsCollection: null,
        LibraryCollection: null,
        CategoriesCollection: null,
        LibraryTemplateModel: null,
        CategoryModel: null,
        TabModel: null,
        KeywordsModel: null,
        KeywordsView: null,
        LibraryPreviewView: null,
        LibraryHeaderBack: null,
        LibraryHeaderInsertButton: null,
        LibraryProButton: null,
        init: function() {
            var e = this;
            e.LibraryTemplateModel = Backbone.Model.extend({
                defaults: {
                    template_id: 0,
                    name: "",
                    title: "",
                    thumbnail: "",
                    preview: "",
                    source: "",
                    "package": "",
                    livelink: "",
                    categories: [],
                    keywords: []
                }
            }), e.CategoryModel = Backbone.Model.extend({
                defaults: {
                    slug: "",
                    title: ""
                }
            }), e.CategoryModel = Backbone.Model.extend({
                defaults: {
                    slug: "",
                    title: ""
                }
            }), e.TabModel = Backbone.Model.extend({
                defaults: {
                    slug: "",
                    title: ""
                }
            }), e.KeywordsModel = Backbone.Model.extend({
                defaults: {
                    keywords: {}
                }
            }), e.LibraryCollection = Backbone.Collection.extend({
                model: e.LibraryTemplateModel
            }), e.CategoriesCollection = Backbone.Collection.extend({
                model: e.CategoryModel
            }), e.LibraryTabsCollection = Backbone.Collection.extend({
                model: e.TabModel
            }), e.LibraryLoadingView = Marionette.ItemView.extend({
                id: "elementskit-template-library-loading",
                template: "#view-elementskit-template-library-loading"
            }), e.LibraryErrorView = Marionette.ItemView.extend({
                id: "elementskit-template-library-error",
                template: "#view-elementskit-template-library-error"
            }), e.LibraryHeaderView = Marionette.LayoutView.extend({
                id: "elementskit-template-library-header",
                template: "#view-elementskit-template-library-header",
                ui: {
                    closeModal: "#elementskit-template-library-header-close-modal"
                },
                events: {
                    "click @ui.closeModal": "onCloseModalClick"
                },
                regions: {
                    headerTabs: "#elementskit-template-library-header-tabs",
                    headerActions: "#elementskit-template-library-header-actions"
                },
                onCloseModalClick: function() {
                    t.closeModal()
                }
            }), e.LibraryPreviewView = Marionette.ItemView.extend({
                template: "#view-elementskit-template-library-preview",
                id: "elementskit-template-library-preview",
                ui: {
                    img: "img"
                },
                onRender: function() {
                    this.ui.img.attr("src", this.getOption("preview"))
                }
            }), e.LibraryHeaderBack = Marionette.ItemView.extend({
                template: "#view-elementskit-template-library-header-back",
                id: "elementskit-template-library-header-back",
                ui: {
                    button: "button"
                },
                events: {
                    "click @ui.button": "onBackClick"
                },
                onBackClick: function() {
                    t.setPreview("back")
                }
            }), e.LibraryInsertTemplateBehavior = Marionette.Behavior.extend({
                ui: {
                    insertButton: ".elementskit-template-library-template-insert"
                },
                events: {
                    "click @ui.insertButton": "onInsertButtonClick"
                },
                onInsertButtonClick: function() {
                    var e = this.view.model;
                    t.layout.showLoadingView();
                    var i, n, a;
                    i = e.get("template_id"), a = {
                        unique_id: i,
                        data: {
                            edit_mode: !0,
                            display: !0,
                            template_id: i
                        }
                    }, (n = {
                        success: function(e) {
                            $e.run("document/elements/import", {
                                model: window.elementor.elementsModel,
                                data: e,
                                options: {}
                            }), t.closeModal()
                        }
                    }) && jQuery.extend(!0, a, n), elementorCommon.ajax.addRequest("get_elementskit_template_data", a)
                }
            }), e.LibraryHeaderInsertButton = Marionette.ItemView.extend({
                template: "#view-elementskit-template-library-insert-button",
                id: "elementskit-template-library-insert-button",
                behaviors: {
                    insertTemplate: {
                        behaviorClass: e.LibraryInsertTemplateBehavior
                    }
                }
            }), e.LibraryProButton = Marionette.ItemView.extend({
                template: "#view-elementskit-template-library-pro-button",
                id: "elementskit-template-library-pro-button"
            }), e.LibraryTemplateItemView = Marionette.ItemView.extend({
                template: "#view-elementskit-template-library-item",
                className: function() {
                    var e = " elementskit-template-has-url",
                        t = " elementor-template-library-template-";
                    return "" === this.model.get("preview") && (e = " elementskit-template-no-url"), "elementskit-local" === this.model.get("source") ? t += "local" : t += "remote", "elementor-template-library-template" + t + e
                },
                ui: function() {
                    return {
                        previewButton: ".elementor-template-library-template-preview"
                    }
                },
                events: function() {
                    return {
                        "click @ui.previewButton": "onPreviewButtonClick"
                    }
                },
                onPreviewButtonClick: function() {
                    "" !== this.model.get("preview") && t.setPreview(this.model)
                },
                behaviors: {
                    insertTemplate: {
                        behaviorClass: e.LibraryInsertTemplateBehavior
                    }
                }
            }), e.FiltersItemView = Marionette.ItemView.extend({
                template: "#view-elementskit-template-library-filters-item",
                className: function() {
                    return "elementskit-filter-item"
                },
                ui: function() {
                    return {
                        filterLabels: ".elementskit-template-library-filter-label"
                    }
                },
                events: function() {
                    return {
                        "click @ui.filterLabels": "onFilterClick"
                    }
                },
                onFilterClick: function(e) {
                    var i = jQuery(e.target);
                    t.setFilter("category", i.val())
                }
            }), e.LibraryTabsItemView = Marionette.ItemView.extend({
                template: "#view-elementskit-template-library-tabs-item",
                className: function() {
                    return "elementor-template-library-menu-item"
                },
                ui: function() {
                    return {
                        tabsLabels: "label",
                        tabsInput: "input"
                    }
                },
                events: function() {
                    return {
                        "click @ui.tabsLabels": "onTabClick"
                    }
                },
                onRender: function() {
                    this.model.get("slug") === t.getTab() && this.ui.tabsInput.attr("checked", "checked")
                },
                onTabClick: function(e) {
                    var i = jQuery(e.target);
                    t.setTab(i.val()), t.setFilter("keyword", "")
                }
            }), e.LibraryCollectionView = Marionette.CompositeView.extend({
                template: "#view-elementskit-template-library-templates",
                id: "elementskit-template-library-templates",
                childViewContainer: "#elementskit-template-library-templates-container",
                initialize: function() {
                    this.listenTo(t.channels.templates, "filter:change", this._renderChildren)
                },
                filter: function(e) {
                    var i = t.getFilter("category"),
                        n = t.getFilter("keyword");
                    return !i && !n || (n && !i ? _.contains(e.get("keywords"), n) : i && !n ? _.contains(e.get("categories"), i) : _.contains(e.get("categories"), i) && _.contains(e.get("keywords"), n))
                },
                getChildView: function(t) {
                    return e.LibraryTemplateItemView
                },
                onRenderCollection: function() {
                    var i = this.$childViewContainer,
                        n = this.$childViewContainer.children(),
                        a = t.getTab();
                    "elementskit_page" !== a && "local" !== a && setTimeout(function() {
                        e.masonry.init({
                            container: i,
                            items: n
                        })
                    }, 200)
                }
            }), e.LibraryTabsCollectionView = Marionette.CompositeView.extend({
                template: "#view-elementskit-template-library-tabs",
                childViewContainer: "#elementskit-template-library-tabs-items",
                initialize: function() {},
                getChildView: function(t) {
                    return e.LibraryTabsItemView
                }
            }), e.FiltersCollectionView = Marionette.CompositeView.extend({
                id: "elementskit-template-library-filters",
                template: "#view-elementskit-template-library-filters",
                childViewContainer: "#elementskit-template-library-filters-container",
                getChildView: function(t) {
                    return e.FiltersItemView
                }
            }), e.LibraryBodyView = Marionette.LayoutView.extend({
                id: "elementskit-template-library-content",
                className: function() {
                    return "library-tab-" + t.getTab()
                },
                template: "#view-elementskit-template-library-content",
                regions: {
                    contentTemplates: ".elementskit-templates-list",
                    contentFilters: ".elementskit-filters-list",
                    contentKeywords: ".elementskit-keywords-list"
                }
            }), e.LibraryLayoutView = Marionette.LayoutView.extend({
                el: "#elementskit-template-library-modal",
                regions: a.modalRegions,
                initialize: function() {
                    this.getRegion("modalHeader").show(new e.LibraryHeaderView), this.listenTo(t.channels.tabs, "filter:change", this.switchTabs), this.listenTo(t.channels.layout, "preview:change", this.switchPreview)
                },
                switchTabs: function() {
                    this.showLoadingView(), t.setFilter("keyword", ""), t.requestTemplates(t.getTab())
                },
                switchPreview: function() {
                    var i = this.getHeaderView(),
                        n = t.getPreview();
                    if ("back" === n) return i.headerTabs.show(new e.LibraryTabsCollectionView({
                        collection: t.collections.tabs
                    })), i.headerActions.empty(), void t.setTab(t.getTab());
                    "initial" !== n ? (this.getRegion("modalContent").show(new e.LibraryPreviewView({
                        preview: n.get("preview")
                    })), i.headerTabs.show(new e.LibraryHeaderBack), "pro" != n.get("package") ? i.headerActions.show(new e.LibraryHeaderInsertButton({
                        model: n
                    })) : i.headerActions.show(new e.LibraryProButton({
                        model: n
                    }))) : i.headerActions.empty()
                },
                getHeaderView: function() {
                    return this.getRegion("modalHeader").currentView
                },
                getContentView: function() {
                    return this.getRegion("modalContent").currentView
                },
                showLoadingView: function() {
                    this.modalContent.show(new e.LibraryLoadingView)
                },
                showLicenseError: function() {
                    this.modalContent.show(new e.LibraryErrorView)
                },
                showTemplatesView: function(i, n, a) {
                    this.getRegion("modalContent").show(new e.LibraryBodyView);
                    var l = this.getContentView(),
                        r = this.getHeaderView();
                    new e.KeywordsModel({
                        keywords: a
                    });
                    t.collections.tabs = new e.LibraryTabsCollection(t.getTabs()), r.headerTabs.show(new e.LibraryTabsCollectionView({
                        collection: t.collections.tabs
                    })), l.contentTemplates.show(new e.LibraryCollectionView({
                        collection: i
                    })), l.contentFilters.show(new e.FiltersCollectionView({
                        collection: n
                    }))
                }
            })
        },
        masonry: {
            self: {},
            elements: {},
            init: function(t) {
                this.settings = e.extend(this.getDefaultSettings(), t), this.elements = this.getDefaultElements(), this.run()
            },
            getSettings: function(e) {
                return e ? this.settings[e] : this.settings
            },
            getDefaultSettings: function() {
                return {
                    container: null,
                    items: null,
                    columnsCount: 3,
                    verticalSpaceBetween: 30
                }
            },
            getDefaultElements: function() {
                return {
                    $container: jQuery(this.getSettings("container")),
                    $items: jQuery(this.getSettings("items"))
                }
            },
            run: function() {
                var e = [],
                    t = this.elements.$container.position().top,
                    i = this.getSettings(),
                    n = i.columnsCount;
                t += parseInt(this.elements.$container.css("margin-top"), 10), this.elements.$container.height(""), this.elements.$items.each(function(a) {
                    var l = Math.floor(a / n),
                        r = a % n,
                        o = jQuery(this),
                        s = o.position(),
                        c = o[0].getBoundingClientRect().height + i.verticalSpaceBetween;
                    if (l) {
                        var m = s.top - t - e[r];
                        m -= parseInt(o.css("margin-top"), 10), m *= -1, o.css("margin-top", m + "px"), e[r] += c
                    } else e.push(c)
                }), this.elements.$container.height(Math.max.apply(Math, e))
            }
        }
    }, n = {
        ElementsKitSearchView: null,
        init: function() {
            this.ElementsKitSearchView = window.elementor.modules.controls.BaseData.extend({
                onReady: function() {
                    var t = this.model.attributes.action,
                        i = this.model.attributes.query_params;
                    this.ui.select.find("option").each(function(t, i) {
                        e(this).attr("selected", !0)
                    }), this.ui.select.select2({
                        ajax: {
                            url: function() {
                                var n = "";
                                return i.length > 0 && e.each(i, function(e, t) {
                                    window.elementor.settings.page.model.attributes[t] && (n += "&" + t + "=" + window.elementor.settings.page.model.attributes[t])
                                }), ajaxurl + "?action=" + t + n
                            },
                            dataType: "json"
                        },
                        placeholder: "Please enter 3 or more characters",
                        minimumInputLength: 3
                    })
                },
                onBeforeDestroy: function() {
                    this.ui.select.data("select2") && this.ui.select.select2("destroy"), this.$el.remove()
                }
            }), window.elementor.addControlView("elementskit_search", this.ElementsKitSearchView)
        }
    }, t = {
        modal: !1,
        layout: !1,
        collections: {},
        tabs: {},
        defaultTab: "",
        channels: {},
        atIndex: null,
        init: function() {
            window.elementor.on("preview:loaded", window._.bind(t.onPreviewLoaded, t)), i.init(), n.init()
        },
        onPreviewLoaded: function() {
            let e = setInterval(() => {
                window.elementor.$previewContents.find(".elementor-add-new-section").length && (this.initAizenButton(), clearInterval(e))
            }, 100);
            window.elementor.$previewContents.on("click", ".elementor-editor-element-setting.elementor-editor-element-add", this.initAizenButton), window.elementor.$previewContents.on("click.addElementsKitTemplate", ".add-elementskit-template", _.bind(this.showTemplatesModal, this)), this.channels = {
                templates: Backbone.Radio.channel("EKIT_THEME_EDITOR:templates"),
                tabs: Backbone.Radio.channel("EKIT_THEME_EDITOR:tabs"),
                layout: Backbone.Radio.channel("EKIT_THEME_EDITOR:layout")
            }, this.tabs = a.tabs, this.defaultTab = a.defaultTab
        },
        initAizenButton: function() {
            var i = window.elementor.$previewContents.find(".elementor-add-new-section"),
                n = '<div class="add-elementskit-template ekit-wid-con"><i class="icon icon-ekit"></i></div>';
            i.find(".add-elementskit-template").length || (i.length && a.libraryButton && e(n).prependTo(i), window.elementor.$previewContents.on("click.addElementsKitTemplate", ".elementor-editor-section-settings .elementor-editor-element-add", function() {
                var i = e(this).closest(".elementor-top-section"),
                    l = i.data("model-cid");
                (window.elementor.sections && window.elementor.sections.currentView.collection.length && e.each(window.elementor.sections.currentView.collection.models, function(e, i) {
                    l === i.cid && (t.atIndex = e)
                }), a.libraryButton) && i.prev(".elementor-add-section").find(".elementor-add-new-section").prepend(n)
            }))
        },
        getFilter: function(e) {
            return this.channels.templates.request("filter:" + e)
        },
        setFilter: function(e, t) {
            this.channels.templates.reply("filter:" + e, t), this.channels.templates.trigger("filter:change")
        },
        getTab: function() {
            return this.channels.tabs.request("filter:tabs")
        },
        setTab: function(e, t) {
            this.channels.tabs.reply("filter:tabs", e), t || this.channels.tabs.trigger("filter:change")
        },
        getTabs: function() {
            var e = [];
            return _.each(this.tabs, function(t, i) {
                e.push({
                    slug: i,
                    title: t.title
                })
            }), e
        },
        getPreview: function(e) {
            return this.channels.layout.request("preview")
        },
        setPreview: function(e, t) {
            this.channels.layout.reply("preview", e), t || this.channels.layout.trigger("preview:change")
        },
        getKeywords: function() {
            return _.each(this.keywords, function(e, t) {
                tabs.push({
                    slug: t,
                    title: e
                })
            }), []
        },
        showTemplatesModal: function() {
            this.getModal().show(), this.layout || (this.layout = new i.LibraryLayoutView, this.layout.showLoadingView()), this.setTab(this.defaultTab, !0), this.requestTemplates(this.defaultTab), this.setPreview("initial")
        },
        requestTemplates: function(t) {
            var n = this,
                a = n.tabs[t];
            n.setFilter("category", !1), a.data.templates && a.data.categories ? n.layout.showTemplatesView(a.data.templates, a.data.categories, a.data.keywords) : e.ajax({
                url: ajaxurl,
                type: "get",
                dataType: "json",
                data: {
                    action: "elementskit_get_layouts",
                    tab: t
                },
                success: function(e) {
                    var a = new i.LibraryCollection(e.data.templates),
                        l = new i.CategoriesCollection(e.data.categories);
                    n.tabs[t].data = {
                        templates: a,
                        categories: l,
                        keywords: e.data.keywords
                    }, n.layout.showTemplatesView(a, l, e.data.keywords)
                }
            })
        },
        closeModal: function() {
            this.getModal().hide()
        },
        getModal: function() {
            return this.modal || (this.modal = elementor.dialogsManager.createWidget("lightbox", {
                id: "elementskit-template-library-modal",
                closeButton: !1
            })), this.modal
        }
    }, e(window).on("elementor:init", t.init)
}(jQuery);