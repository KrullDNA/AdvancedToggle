(function ($) {
	"use strict";

	var AdvToggleHandler = elementorModules.frontend.handlers.Base.extend({
		$activeContent: null,

		getDefaultSettings: function () {
			return {
				selectors: {
					tabTitle: ".adv-toggle__item-title",
					tabContent: ".adv-toggle__item-content",
				},
				classes: {
					active: "adv-toggle__item--active",
				},
				showTabFn: "slideDown",
				hideTabFn: "slideUp",
				toggleSelf: false,
				hidePrevious: false,
				autoExpand: "editor",
			};
		},

		getDefaultElements: function () {
			var selectors = this.getSettings("selectors");
			return {
				$tabTitles: this.findElement(selectors.tabTitle),
				$tabContents: this.findElement(selectors.tabContent),
			};
		},

		activateDefaultTab: function () {
			var settings = this.getSettings();

			// autoExpand: "editor" means only expand in the Elementor editor, not on frontend
			if (
				!settings.autoExpand ||
				("editor" === settings.autoExpand && !this.isEdit)
			) {
				return;
			}

			var defaultActiveTab =
					this.getEditSettings("activeItemIndex") || 1,
				originalToggleMethods = {
					showTabFn: settings.showTabFn,
					hideTabFn: settings.hideTabFn,
				};

			// Toggle tabs without animation to avoid jumping
			this.setSettings({
				showTabFn: "show",
				hideTabFn: "hide",
			});

			this.changeActiveTab(defaultActiveTab);

			// Return back original toggle effects
			this.setSettings(originalToggleMethods);
		},

		deactivateActiveTab: function (tabIndex) {
			var settings = this.getSettings(),
				activeClass = settings.classes.active,
				activeFilter = tabIndex
					? '[data-tab="' + tabIndex + '"]'
					: "." + activeClass,
				$activeTitle =
					this.elements.$tabTitles.filter(activeFilter),
				$activeContent =
					this.elements.$tabContents.filter(activeFilter);

			$activeTitle.add($activeContent).removeClass(activeClass);
			$activeContent[settings.hideTabFn]();
		},

		activateTab: function (tabIndex) {
			var settings = this.getSettings(),
				activeClass = settings.classes.active,
				$requestedTitle = this.elements.$tabTitles.filter(
					'[data-tab="' + tabIndex + '"]'
				),
				$requestedContent = this.elements.$tabContents.filter(
					'[data-tab="' + tabIndex + '"]'
				);

			$requestedTitle.add($requestedContent).addClass(activeClass);
			$requestedContent[settings.showTabFn]();
		},

		isActiveTab: function (tabIndex) {
			return this.elements.$tabTitles
				.filter('[data-tab="' + tabIndex + '"]')
				.hasClass(this.getSettings("classes.active"));
		},

		bindEvents: function () {
			var _this = this;

			this.elements.$tabTitles.on({
				keydown: function (event) {
					if ("Enter" === event.key) {
						event.preventDefault();
						_this.changeActiveTab(
							event.currentTarget.getAttribute("data-tab")
						);
					}
				},
				click: function (event) {
					event.preventDefault();
					_this.changeActiveTab(
						event.currentTarget.getAttribute("data-tab")
					);
				},
			});
		},

		onInit: function () {
			elementorModules.frontend.handlers.Base.prototype.onInit.apply(
				this,
				arguments
			);
			this.activateDefaultTab();
		},

		onEditSettingsChange: function (propertyName) {
			if ("activeItemIndex" === propertyName) {
				this.activateDefaultTab();
			}
		},

		changeActiveTab: function (tabIndex) {
			var isActiveTab = this.isActiveTab(tabIndex),
				settings = this.getSettings();

			if (
				(settings.toggleSelf || !isActiveTab) &&
				settings.hidePrevious
			) {
				this.deactivateActiveTab();
			}

			if (!settings.hidePrevious && isActiveTab) {
				this.deactivateActiveTab(tabIndex);
			}

			if (!isActiveTab) {
				this.activateTab(tabIndex);
			}

			$(window).trigger("resize");
		},
	});

	$(window).on("elementor/frontend/init", function () {
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/advanced-toggle.default",
			function ($scope) {
				elementorFrontend.elementsHandler.addHandler(
					AdvToggleHandler,
					{
						$element: $scope,
					}
				);
			}
		);
	});
})(jQuery);
