EasySocial.module('site/comments/item', function($) {
var module = this;

EasySocial.require()
.library('mentions')
.script('site/comments/form', 'uploader/uploader')
.done(function() {

EasySocial.Controller('Comments.Item', {
	defaultOptions: {
		'id': 0,
		'child': 0,
		'loadedChild': 0,
		'limit': 10,
		'isNew': false,

		// Comment item wrapper
		'{wrapper}': '[data-comment-wrapper]',

		// Content of the comment
		'{content}': '[data-comment-content]',

		// Editing
		'{editor}': '[data-comment-editor]',
		'{edit}': '[data-comment-actions] [data-edit]',
		'{input}'	: '[data-comment-input]',
		'{cancel}': "[data-comment-editor] [data-cancel]",
		'{save}': "[data-comment-editor] [data-save]",

		// Giphy
		'{giphyButton}': '[data-giphy-button]',
		'{giphyPlaceholder}': '[data-giphy-placeholder]',
		'{giphyPreview}': '[data-giphy-preview]',
		'{giphyRemove}': '[data-giphy-remove]',
		'{giphyContainer}': '[data-giphy-container]',

		'{giphyItemPreviewPlaceholder}': '[data-giphy-item-preview-placeholder]',
		'{giphyItemPreview}': '[data-giphy-item-preview]',
		'{giphyPermalink}': '[data-giphy-permalink]',

		// Actions
		'{delete}': '[data-comment-actions] [data-delete]',
		'{loadReplies}': '[data-comments-item-loadReplies]',
		'{readMore}': '[data-es-comment-readmore]',
		'{fullContent}': '[data-es-comment-full]',

		// Attachments
		"{attachmentQueue}": "[data-comment-attachment-queue]",
		"{attachmentProgress}": "[data-comment-attachment-progress-bar]",
		"{attachmentBackground}": "[data-comment-attachment-background]",
		"{attachmentRemove}": "[data-comment-attachment-remove]",
		"{attachmentItem}": "[data-comment-attachment-item]",
		"{attachmentButton}": "[data-uploader-browse]",
		"{attachmentDelete}": "[data-comment-attachment-delete]",

		"{uploaderForm}": "[data-uploader-form]",
		"{itemTemplate}": "[data-comment-attachment-template]",

		"{dismissError}": "[data-comment-error-dismiss]",
		"{errorMessage}": "[data-comment-error]",
		"{errorCommentMessage}": "[data-comment-error-message]",

		"{uploadItem}": "[data-comment-photo-upload-item]",

		attachmentIds:[],
		emoticons: []
	}
}, function(self, opts) { return {
	init: function() {

		// Get available hints for friend suggestions and hashtags
		opts.hints = {
				"friends": $('[data-hints-friends]'),
				"hashtags": $('[data-hints-hashtags]'),
				"emoticons": $('[data-hints-emoticons]')
		};

		// Initialise comment id
		opts.id = self.element.data('id');

		// Initialise child count
		opts.child = self.element.data('child');

		// Register self into the registry of comments
		self.parent.registerComment(self);
	},

	giphyURL: null,
	currentGiphyURL: null,
	attachmentTemplate: null,

	removeGiphy: function() {
		self.giphyPreview().attr('src', '');

		// Hide preview in comment form
		self.giphyPlaceholder().addClass('t-hidden');

		self.giphyURL = null;
		self.currentGiphyURL = null;
	},

	showGiphy: function(url) {
		self.giphyItemPreviewPlaceholder().removeClass('t-hidden');
		self.giphyItemPreview().prop('src', url);
		self.giphyPermalink().prop('href', url);
	},

	updateGiphy: function(item) {
		var imageUrl = item.data('original');

		// Ensure that the old giphy item has been removed
		self.removeGiphy();

		self.giphyPlaceholder().removeClass('t-hidden');
		self.giphyPreview().prop('src', imageUrl);

		// Set the width given the width of the image
		self.setGiphyImageWidth();

		self.giphyURL = imageUrl;
	},

	setGiphyImageWidth: function() {
		self.giphyPlaceholder().css('width', '100%');
		self.giphyRemove().addClass('t-hidden');
		self.giphyContainer().addClass('is-loading');

		self.giphyPreview().off('load').on('load', function() {
			self.giphyContainer().removeClass('is-loading');

			var parent = $(this).parent();

			// Display the placeholder and set the width given the width of the image
			setTimeout(function() {
				var computedWidth = parent.css('width');

				self.giphyPlaceholder().css('width', computedWidth);
			}, 10);

			self.giphyRemove().removeClass('t-hidden');
		});
	},

	bindClickGiphyItem: function(popbox) {
		popbox.tooltip
			.find('[data-giphy-item]')
			.off('click.giphy.comment.edit')
			.on('click.giphy.comment.edit', function() {
				var item = $(this);

				self.updateGiphy(item);

				// Hide the popbox once an item is selected
				popbox.hide();
			});
	},

	"{giphyButton} popboxActivate": function(element, event, popbox) {
		// Ensure event is exclusive to edit comment only
		event.stopPropagation();

		popbox.tooltip
			.find('[data-popbox-content]')
			.off('giphyAfterSearch')
			.on('giphyAfterSearch', function(event, query) {
				self.bindClickGiphyItem(popbox);
			});

		self.bindClickGiphyItem(popbox);
	},

	"{giphyRemove} click": function() {
		self.removeGiphy();
	},

	getAttachmentTemplate: function() {

		if (!self.attachmentTemplate) {
			self.attachmentTemplate = self.itemTemplate().detach();
		}

		var tpl = $(self.attachmentTemplate).clone().html();

		return $(tpl);
	},

	populateAttachmentOnEdit: function(curEditor, attachmentFiles) {

		// find the attachment queue data attribute
		var attachmentQueue = curEditor.find(self.attachmentQueue().selector);

		$.each(attachmentFiles, function(index, attachmentFile) {

			// Get the attachment template
			var item = self.getAttachmentTemplate();

			// Set the queue to use has-attachments class
			attachmentQueue
				.addClass('has-attachments');

			// Insert the item into the queue
			item.attr('id', attachmentFile.id)
				// .prependTo(attachmentQueue);
				.appendTo(attachmentQueue);

			// show the attachment during editing operation
			var item = self.attachmentQueue().find('#' + attachmentFile.id);

			// Add preview image here
			self.attachmentBackground.inside(item)
				.css('background-image', 'url("' + attachmentFile.permalink + '")');

			// find the delete icon for each attachment item
			var deleteButton = item.find(self.attachmentRemove);

			// insert attachment data id for those attachment already stored in database
			$(deleteButton).attr('data-id', attachmentFile.id);
			$(deleteButton).attr('data-edit-comment-attachment', '');

			// the behavior is if the user delete those attachment which already stored in database
			// we need to show the delete dialog pop up
			$(deleteButton).removeAttr('data-comment-attachment-remove');
			$(deleteButton).attr('data-comment-attachment-delete', '');

		});
	},

	'{edit} click': function(link, event) {

		if (!link.enabled()) {
			return;
		}

		// Get the editor
		var editor = self.editor();
		var mentions = editor.controller('mentions');

		// Manually clear out the html and destroy the mentions controller to prevent conflict of loading the editFrame again.
		editor.empty();

		if (mentions) {
			mentions.destroy();
		}

		// Disable the edit link
		link.disabled(true);

		// Trigger commentEditLoading event
		// self.trigger('commentEditLoading', [self.options.id]);

		EasySocial.ajax('site/controllers/comments/edit', {
			id: self.options.id
		}).done(function(contents, attachmentFiles) {

			// Hide the current contents and show the editor
			self.wrapper().hide();

			self.editor().show();

			// Append the form to the edit frame
			self.editor().html(contents).show();

			// Implement uploader controller during edit comment
			var curEditor = self.editor().find('[data-comments-editor]')
				.implement(EasySocial.Controller.Uploader, {
					'temporaryUpload': true,
					'query': 'type=comments',
					'type': 'comments',
					extensionsAllowed: 'jpg,jpeg,png,gif'
				});

			self.uploader = curEditor.controller('uploader');
			self.addPlugin("uploader", self.uploader);

			self.setMentionsLayout();

			self.input().focus();

			// Get the current giphy url
			self.giphyURL = self.editor().find('[data-giphy-preview]').attr('src');

			// hide attachment button if there got giphyURL
			if (self.giphyURL) {
				self.attachmentButton().addClass('t-hidden');
			}

			// Set the width given the width of the image
			self.setGiphyImageWidth();

			// Just in case the giphy URL is invalid, we just use show back the current giphy
			self.currentGiphyURL = self.giphyURL;

			// Process this if this comment has attachment
			if (attachmentFiles) {

				// Hide giphy button
				self.giphyButton().addClass('t-hidden');

				self.populateAttachmentOnEdit(curEditor, attachmentFiles);
			}
		});
	},

	setMentionsLayout: function() {
		var editor = self.editor();
		var mentions = editor.controller("mentions");

		if (mentions) {
			mentions.cloneLayout();
			return;
		}

		editor.mentions({
			triggers: {
				"@": {
					type: "entity",
					wrap: false,
					stop: "",
					allowSpace: true,
					finalize: true,
					query: {
						loadingHint	: true,
						searchHint: opts.hints.friends.find('[data-search]').html(),
						emptyHint: opts.hints.friends.find('[data-empty]').html(),
						data: function (keyword) {

							var task = $.Deferred();

							EasySocial.ajax( "site/controllers/friends/suggest" , {
								"search": keyword,
								clusterId: self.parent.options.clusterid
							}).done(function(items) {

								if (!$.isArray(items)) {
									task.reject();
								}

								var items = $.map(items, function(item){

									var html = $('<div/>').html(item);
									var title = html.find('[data-suggest-title]').val();
									var id = html.find('[data-suggest-id]').val();

									return {
										"id": id,
										"title": title,
										"type": "user",
										"menuHtml": item
									};
								});

								task.resolve(items);
							})
							.fail(task.reject);

							return task;
						},
						use: function(item) {
							return item.type + ":" + item.id;
						}
					}
				},
				"#": {
					type: "hashtag",
					wrap: true,
					stop: " #",
					allowSpace: false,
					query: {
						loadingHint	: true,
						searchHint: opts.hints.hashtags.find('[data-search]').html(),
						emptyHint: opts.hints.hashtags.find('[data-empty]').html(),

						data: function(keyword) {

							var task = $.Deferred();

							EasySocial.ajax("site/controllers/hashtags/suggest", {
								search: keyword
							}).done(function(items) {
								if (!$.isArray(items)) {
									task.reject();
								}

								var items = $.map(items, function(item) {

									return {
										"title": "#" + $.trim(item),
										"type": "hashtag",
										"menuHtml": item
									};
								});

								task.resolve(items);
							}).fail(task.reject);

							return task;
						}
					}
				},
				":": {
						type: "emoticon",
						wrap: true,
						stop: "",
						allowSpace: false,
						query: {
							loadingHint: true,
							searchHint: opts.hints.emoticons.find('[data-search]').html(),
							emptyHint: opts.hints.emoticons.find('[data-empty]').html(),
							data: $.parseJSON(self.parent.options.emoticons),
							renderAll: true
						}
					}
			},
			plugin: {
				autocomplete: {
					id: "es",
					component: "es",
					position: {
						my: 'left top',
						at: 'left bottom',
						of: editor,
						collision: 'none'
					},
					size: {
						width: function() {
							return editor.width();
						}
					}
				}
			}
		});
	},

	"{input} keyup": function(element, event) {
		// Prevent cursor keys from moving between photos
		event.preventDefault();
		event.stopPropagation();
	},

	'{cancel} click': function() {

		// Destroy the editor
		self.editor().empty();
		self.wrapper().show();

		self.edit().enabled(true);

		// Trigger editCommentTrigger event
		self.trigger('editCommentTrigger');

		// clear the attachment queue
		self.clearAttachmentForm();
	},

	// When the file is uploaded, we need to remove the uploading state
	"{uploader} FileUploaded": function(el, event, uploader, file, response) {

		// keep track local attachment id here.
		opts.attachmentIds.push(response.id);
	},

	"{attachmentRemove} click": function(removeLink, event) {

		var item = removeLink.parents(self.attachmentItem.selector);

		// Remove the item from the attachment ids
		opts.attachmentIds = $.without(opts.attachmentIds, item.data('id'));
	},

	'{save} click': function(el) {

		// Get and trim the edit value
		var input = self.input().val();

		// Do not proceed if value is emptycommentDeletedcommentDeleted
		if (input == '' && (!self.giphyURL || self.giphyURL == undefined)) {
			return false;
		}

		// Trigger commentEditSaving event
		// self.trigger('commentEditSaving', [self.options.id, input]);

		var mentions = self.editor().mentions("controller");

		EasySocial.ajax('site/controllers/comments/update', {
			"id": self.options.id,
			"input": self.input().val(),
			"giphy": self.giphyURL ? self.giphyURL : self.currentGiphyURL,
			"attachmentIds": opts.attachmentIds,
			"mentions": mentions ? mentions.toArray() : []
		}).done(function(comment, giphyInValid) {

			// Update the comment content
			// self.content().html(comment);

			// Trigger editCommentTrigger event
			// Need to trigger this before commentUpdated event
			self.trigger('editCommentTrigger');

			// comment id
			var commentId = self.options.id;

			// trigger the comment update event for implment the controller
			// Because need to render the comment item again after comment update
			self.parent.trigger('commentUpdated', [commentId, comment]);

			if (giphyInValid) {
				self.giphyURL = self.currentGiphyURL;
			}

			// If there is any giphy url, it should be updated on screen
			if (self.giphyURL) {
				self.showGiphy(self.giphyURL);
			}

			// There is a possibility that user removed their giphy
            if (!self.giphyURL) {
            	// Hide preview in comments listing
            	self.giphyItemPreviewPlaceholder().addClass('t-hidden');
                self.removeGiphy();
            }

			// Hide the editor
			self.editor().empty().hide();
			self.wrapper().show();

			self.edit().enabled(true);

			// clear the attachment queue
			self.clearAttachmentForm();
		});
	},

	'{delete} click': function(el) {

		EasySocial.dialog({
			content: EasySocial.ajax('site/views/comments/confirmDelete', {
				id: self.options.id
			}),
			bindings: {
				"{deleteButton} click": function() {

					// Close the dialog
					EasySocial.dialog().close();

					EasySocial.ajax('site/controllers/comments/delete', {
						id: self.options.id
					}).done(function() {

						// Trigger commentDeleted event on parent, since this element will be remove, no point triggering on self
						self.parent.trigger('commentDeleted', [self.options.id]);

					});
				}
			}
		});
	},

	'{loadReplies} click': function(el) {
		// Hide the loadReplies button
		el.hide();

		// Add a loader after this comment first

		// Calculate the start
		var start = Math.max(self.options.child - self.options.loadedChild - self.options.limit, 0);

		// Get the child comments
		EasySocial.ajax()
			.done(function(comments) {

				// Append the comments below the current comment item
				$.each(comments, function(index, comment) {
					self.parent.$List.addToList(comment, 'child', false);
				});

				// Trigger oldCommentsLoaded event
				self.parent.trigger('oldCommentsLoaded', [comments]);

				// Check if we need to show the load more replies button in the current item
				start > 0 && self.loadMoreReplies().show();
			});
	},

	'{readMore} click': function(el, ev) {
		self.content().html(self.fullContent().html());
	},

	clearAttachmentForm: function() {

		// Reset the attachments
		opts.attachmentIds = [];

		// Get all the attachment items in the queue
		var attachmentItems = self.attachmentItem.inside(self.attachmentQueue.selector);

		attachmentItems.remove();

		self.attachmentQueue().removeClass('has-attachments');
	}
}});

module.resolve();

});

});
