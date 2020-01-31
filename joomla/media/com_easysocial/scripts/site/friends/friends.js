EasySocial.module('site/friends/friends', function($) {

	var module 	= this;

	EasySocial.require()
	.script('site/friends/suggest')
	.done(function($){

		EasySocial.Controller('Friends', {

			defaultOptions: {

				// Get the default active list if there is any.
				activeList: null,

				// Friends filter
				"{filterItem}": "[data-filter-item]",

				// Content area.
				"{wrapper}": "[data-wrapper]",
				"{contents}": "[data-contents]",

				// Result
				"{items}": "[data-items]",
				"{item}": "[data-item]",
				"{pagination}": "[data-pagination]",

				// Counters
				"{counters}": "[data-counter]",

				// Friend list actions
				"{listActions}": "[data-list-actions]",
				"{deleteList}": "[data-list-actions] [data-delete]",
				"{defaultList}": "[data-list-actions] [data-default]",
				"{addToList}": "[data-list-actions] [data-add]",
				"{removeFromList}": "[data-remove-from-list]"
			}
		}, function(self, opts) { return {

			init: function() {
				opts.userId = self.element.data('userid');
			},

			updateCounter: function() {
				EasySocial.ajax('site/controllers/friends/getCounters')
					.done(function(all, pending, requests, suggestions) {

						self.filterItem('[data-type="all"]')
							.find(self.counters.selector)
							.html(all);

						self.filterItem('[data-type="pending"]')
							.find(self.counters.selector)
							.html(pending);

						self.filterItem('[data-type="request"]')
							.find(self.counters.selector)
							.html(requests);

						self.filterItem('[data-type="suggest"]')
							.find(self.counters.selector)
							.html(suggestions);
					});
			},

			updateListCounters: function() {

				EasySocial.ajax('site/controllers/friends/getListCounts')
					.done(function(lists) {

						$(lists).each(function(i, list){

							self.filterItem('[data-type="list"][data-id="' + list.id + '"]')
								.find(self.counters.selector)
								.html(list.count);
						});
					});
			},

			insertItem: function(item) {

				// Hide any empty notices.
				self.items().removeClass('is-empty');

				// Update the counter for the list items.
				self.updateListCounters();

				// Prepend the result back to the list
				$(item).prependTo(self.items());
			},

			removeItem: function(id, source) {
				// Remove item from the list.
				var item = self.item('[data-id="' + id + '"]');

				item.remove();

				if (self.item().length <= 0) {
					self.items().addClass('is-empty');
					self.pagination().remove();
				}

				// Update the counter for the list items.
				if (source == 'list') {
					self.updateListCounters();
				} else {
					self.updateCounter();
				}
			},

			updateFriendRequestCount: function(value) {

				curCount = parseInt(self.requestCount().text(), 10);

				if (curCount != NaN) {
					curCount = curCount + value;
					self.requestCount().text(curCount);
				}
			},

			// Update the content on the friends list.
			updateContents: function(html) {
				self.contents().html(html);

				$('body').trigger('afterUpdatingContents', [html]);
			},

			setActiveFilter: function(item) {

				// Remove all active classes
				self.filterItem().removeClass('active');

				// Add active class on itself
				item.addClass('active');
			},

			"{removeFromList} click" : function(link) {
				var id = link.parents(self.item.selector).data('id');

				EasySocial.ajax('site/controllers/friends/removeFromList', {
					"listId": self.options.activeList,
					"userId": id
				}).done(function() {

					// Remove the item from the list.
					self.removeItem(id, 'list');
				});
			},

			"{addToList} click": function(link) {

				var wrapper = link.parents('[data-list-actions]');
				var id = wrapper.data('id');

				EasySocial.dialog({
					content: EasySocial.ajax('site/views/friends/assignList', {"id" : id}),
					bindings: {

						"{insertButton} click" : function() {
							var items = this.suggest().textboxlist("controller").getAddedItems();

							EasySocial.ajax('site/controllers/friends/assign', {
								"uid": $.pluck(items, "id"),
								"listId": id
							}).done(function(contents) {

								// Hide any notice messages.
								$('[data-assignFriends-notice]').hide();

								$(contents).each(function(i, item) {

									// Pass the item to the parent so it gets inserted into the friends list.
									self.insertItem(item);

									// Close the dialog
									EasySocial.dialog().close();
								});

							}).fail(function(message) {
								$('[data-assignFriends-notice]').addClass('alert alert-error')
									.html(message.message);
							});
						}
					}
				});
			},

			"{deleteList} click" : function(link) {
				var actions = link.parents(self.listActions.selector);
				var id = actions.data('id');

				EasySocial.dialog({
					content: EasySocial.ajax("site/views/friends/confirmDeleteList", {"id": id}),
					bindings: {
						"{deleteButton} click" : function() {
							$('[data-friends-list-delete-form]').submit();
						}
					}
				});
			},

			"{filterItem} click" : function(filterItem, event) {

				// Stop event from bubbling up
				event.preventDefault();
				event.stopPropagation();

				var type = filterItem.data('type');
				var userid = opts.userId;

				// Remove all active state on the filter links.
				self.setActiveFilter(filterItem);

				// Set the browsers attributes
				var anchor = filterItem.find('> a');
				anchor.route();

				// Add loading indicator
				filterItem.addClass('is-loading');

				var options = {
								"filter": type,
								"userid": userid
							};

				// If the type of filter is a list, we need to perform a different action
				if (type == 'list') {
					options.id = filterItem.data('id');
					self.options.activeList = options.id;
				}

				self.wrapper().addClass('is-loading');
				self.contents().empty();

				EasySocial.ajax("site/controllers/friends/filter", options)
					.done(function(html){

						self.updateContents(html);

        				// trigger sidebar toggle for responsive view.
        				self.trigger('onEasySocialFilterClick');

					}).always(function(){

						self.wrapper().removeClass('is-loading');
						filterItem.removeClass("is-loading");
					});
			}
		}});

		module.resolve();
	});
});
