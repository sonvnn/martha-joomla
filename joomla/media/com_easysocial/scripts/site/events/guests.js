EasySocial.module('site/events/guests', function($) {

	var module = this;

	EasySocial.Controller('Events.App.Guests', {
		defaultOptions: {

			// Wrapper
			"{wrapper}": "[data-wrapper]",
			"{contents}": "[data-contents]",

			// Item
			"{item}": "[data-item]",

			// Actions
			"{remove}": "[data-guest-remove]",
			"{approve}": "[data-guest-approve]",
			"{promote}": "[data-guest-promote]",
			"{demote}": "[data-guest-demote]",
			"{reject}": "[data-guest-reject]",

			"{searchInput}": "[data-search-input]",

			// Filters
			"{filter}": "[data-filter]",

			// Checkbox
			'{checkbox}': '[data-event-item-checkbox]',
			'{checkAll}': '[data-event-item-checkall]',

			// Actions button
			'{actionsWrapper}': '[data-event-actions-wrapper]',
			'{actionsApply}': '[data-event-actions-apply]',
			"{actionsTask}": '[data-event-actions-task]'
		}
	}, function(self, opts) { return {

		init : function() {
			// Get the id of the page
			opts.id = self.element.data('id');
			opts.returnUrl = self.element.data('return');
		},

		clearSelectedCheckboxes: function() {
			self.checkAll().prop('checked', false);
			self.checkAll().trigger('change');
		},

		updateCheckboxSelection: function() {
			// Check if all checkbox is selected
			if (self.isAllCheckboxSelected()) {
				self.checkAll().prop('checked', true);
			} else {
				self.checkAll().prop('checked', false);
			}
		},

		isAllCheckboxSelected: function() {
			var totalSelected = self.getSelectedCheckbox().length;
			var totalCheckbox = self.checkbox().length;

			if (totalSelected == totalCheckbox) {
				return true;
			}

			return false;
		},

		getSelectedCheckbox: function() {
			var items = [];
			var selected = self.checkbox(':checked');

			selected.each(function(i, el) {
				items.push($(el).val());
			});

			return items;
		},

		"{checkbox} change" : function(input, event) {
			var selected = self.getSelectedCheckbox();

			var parent = input.parents(self.item.selector);

			parent.removeClass('is-selected');

			// Check if all checkbox is selected
			self.updateCheckboxSelection();

			if (selected.length > 0) {
				self.actionsWrapper().removeClass('t-hidden');
				parent.addClass('is-selected');
				return;
			}

			self.actionsWrapper().addClass('t-hidden');
		},

		'{checkAll} change': function(input, event) {
			var checked = input.is(':checked');

			if (checked) {
				self.actionsWrapper().removeClass('t-hidden');
			} else {
				self.actionsWrapper().addClass('t-hidden');
			}

			self.checkbox().not(':disabled').prop('checked', checked);
			self.checkbox().not(':disabled').trigger('change');
		},

		"{actionsApply} click": function(button, event) {
			var controllerTask = $.trim(self.actionsTask().val());

			if (controllerTask == '') {
				return false;
			}

			var confirmation = self.actionsTask().find(':selected').data('confirmation');

			// If there is no confirmation, just submit the form
			if (!confirmation) {
				self.taskInput().val(controllerTask);
				self.submitForm();

				return false;
			}

			// Get selected items
			var items = self.getSelectedCheckbox();
			var returnUrl = $('[data-item]').data('return');

			EasySocial.dialog({
				"content": EasySocial.ajax(confirmation, {'userId': items, 'id': opts.id, 'return': returnUrl})

			});
		},

		search: function(keyword) {
			var type = $('[data-filter].active').data('type');

			self.updatingContents();

			EasySocial.ajax('apps/event/guests/controllers/events/getGuests', {
				"id": opts.id,
				"keyword": keyword,
				"filter": type
			}).done(function(contents) {

				self.updateContents(contents);

				if (!self.item().length) {
					self.contents().addClass('is-empty');
				}
			});
		},

		setActiveFilter: function(filter) {
			self.filter().removeClass('active');
			filter.addClass('active');
		},

		getItems: function(type, clusterId, callback) {

			if (self.searchInput().val() != '') {
				this.search(self.searchInput().val());
				return;
			}

			var id = clusterId ? clusterId : opts.id;

			self.updatingContents();

			EasySocial.ajax('apps/event/guests/controllers/events/getGuests', {
				"id": id,
				"filter": type
			}).done(function(contents) {

				if ($.isFunction(callback)) {
					callback.call(this, contents);
				}

				self.updateContents(contents);

				// Show empty if necessary
				self.contents().toggleClass('is-empty', !self.item().length);

				$('body').trigger('afterUpdatingContents', [contents]);

			});
		},

		updatingContents: function() {
			self.wrapper().empty();
			self.contents().addClass('is-loading');
		},

		updateContents: function(html) {
			self.contents().removeClass('is-loading');
			self.wrapper().replaceWith(html);
		},

		"{remove} click" : function(link, event) {

			// Get the user id
			var userId = link.closest(self.item.selector).data('id');
			var returnUrl = link.closest(self.item.selector).data('return');

			EasySocial.dialog({
				content: EasySocial.ajax('site/views/events/confirmRemoveGuest', {"id": userId, "return": returnUrl})
			});
		},

		// Approve a follower
		"{approve} click" : function(link, event) {
			// Get the user id
			var userId = link.closest(self.item.selector).data('id');
			var returnUrl = link.closest(self.item.selector).data('return');

			EasySocial.dialog({
				content: EasySocial.ajax('site/views/events/confirmApproveGuest', {
					"userId": userId,
					"id": opts.id,
					"return": returnUrl
				})
			});
		},

		"{promote} click": function(link, event) {
			// Get the user id
			var userId = link.closest(self.item.selector).data('id');
			var returnUrl = link.closest(self.item.selector).data('return');

			EasySocial.dialog({
				content: EasySocial.ajax('site/views/events/confirmPromoteGuest', {
					"uid" : userId,
					"id" : opts.id,
					"return" : returnUrl
				})
			});
		},

		"{demote} click": function(link, event) {

			// Get the user id
			var userId = link.closest(self.item.selector).data('id');
			var returnUrl = link.closest(self.item.selector).data('return');

			EasySocial.dialog({
				content: EasySocial.ajax('site/views/events/confirmDemoteGuest', {
					"uid" : userId,
					"id" : opts.id,
					"return" : returnUrl
				})
			});
		},

		"{reject} click" : function(link, event) {
			// Get the user id
			var userId = link.closest(self.item.selector).data('id');
			var returnUrl = link.closest(self.item.selector).data('return');

			EasySocial.dialog({
				content: EasySocial.ajax('site/views/events/confirmRejectGuest', {
					"userId": userId,
					"id": opts.id,
					"return": returnUrl
				})
			});
		},

		"{searchInput} keyup": $.debounce(function(textInput){
			var keyword = $.trim(textInput.val());
			self.search(keyword);
		}, 250),
	}});

	module.resolve();
});

