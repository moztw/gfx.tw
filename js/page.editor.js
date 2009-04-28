/*global window, document, $, T, gfx, SWFUpload */

gfx.page = {
	'bind' : {
		'click' : {
			'#editor-save-button' : function () {
				//Dumb, but we cannot self-reference at this point.
				gfx.page.savePage();
			},
			'#save_page' : function () {
				gfx.page.savePage();
			},
			'#title-name' : function () {
				var t = $(this);
				t.css('display', 'none');
				$('#title-name-edit input')
				.css('display', 'block')
				.val(t.text()).focus();
			},
			'#featureselection-clear button:only-child' : function () {
				$('#features .feature').attr('id', null).addClass('box');
				$('#featureselection li').removeClass('selected');
			},
			'#title-avatar' : function () {
				gfx.openDialog('avatar');
			},
			'#avatar_glavatar' : function () {
				gfx.page.changeAvatar('(gravatar)', $(this).children()[0].src);
			},
			'#avatar_default' : function () {
				gfx.page.changeAvatar('(default)', './images/avatar-default.png');
			},
			'#groups input' : function () {
				$(this).parent().toggleClass('not-selected', !this.checked);
				gfx.page.groupChanged = true;
				$('#editor-save button').attr('disabled', null);
			},
			'#groups .group-add-addon a' : function () {
				gfx.page.currentGroup = this.parentNode.parentNode.id.substr(2);
				gfx.openDialog('addons');				
				return false;
			},
			'#userinfo button, #change-email' : function () {
				gfx.openDialog('info');
				return false;
			},
			'.download a' : function () {
				gfx.openDialog('download');
				return false;
			},
			'#forum_auth' : function () {
				$('#info_forum').val('');
				$('#forum_auth_iframe')
					.css('display', 'block')
					.empty()
					.append($(document.createElement('iframe')).attr('src', this.href));
				return false;
			},
			'#info_delete_account' : function () {
				gfx.openDialog('delete');
				return false;
			}
		},
		'focus' : {
			'#title-name-edit input' : function () {
				if ($(this).hasClass('empty')) {
					$(this).removeClass('empty').val('');
				}
			}
		},
		'blur' : {
			'#title-name-edit input' : function () {
				if (this.value !== '') {
					$('#title-name').text(this.value).css('display', null);
					$('span.title-placeholder').removeClass('title-empty').text(this.value);
					$(this).css('display', null);
					gfx.page.infoChanged = true;
					$('#editor-save button').attr('disabled', null);
				} else {
					$('#title-name').text(this.value);
					$('span.title-placeholder').addClass('title-empty').text(T.UI.TITLE_PLACEHOLDER);
					$(this).addClass('empty').val(T.UI.EMPTY_TITLE);
				}
			}
		},
		'mouseover' : {
			'#title-name.editable' : function () {
				$(this).addClass('bright');
			}
		},
		'mouseout' : {
			'#title-name.editable' : function () {
				$(this).removeClass('bright');
			}
		},
		'submit' : {
			'#addon_query_form' : function () {
				var r = $('#addon_query_result').empty();
				$('#addon_query_desc').show().text(String.fromCharCode(160)); // &nbsp;
				$('#addon_query_notfound').hide();
				gfx.xhr = $.ajax(
					{
						url: './addon/query',
						data: {	
							'token' : $('#token').val(),
							'q' : $('#addon_query').val().replace(/^https:\/\/addons.mozilla.org\/[\w\-]{5}\/firefox\/addon\/(\d+)$/, '$1')
						},
						success: function (result, status) {
							$('#addon_query').focus();
							if (gfx.ajaxError(result)) {
								return;
							}
							if (!result.addons.length) {
								$('#addon_query_notfound').show();
								$('#addon_query_desc').hide();
								return;
							}
							$('#addon_query_desc').text(T.UI.ADDON_SEARCH_RESULT);
							$.each(
								result.addons,
								function (i, d) {
									r.append(
										new gfx.page.Addon(d, true, false)
									);
								}
							);
							if (result.addons.length === 1) {
								r.find('input[disabled!=true]').attr('checked', true);
							}
						}
					}
				);
				return false;
			},
			'#title-name-form' : function () {
				$('#title-name-edit input').blur();
				return false;
			}
		}
	},
	'live' : {
		'click' : {
			/*'.addon p > a' : function () {
				window.open(this.href);
				return false;
			},*/
			'.del-addon' : function () {
				$(this).parent('.addon').slideUp(
					500,
					function () {
						$(this).remove();
					}
				);
				gfx.page.addonChanged = true;
				$('#editor-save button').attr('disabled', null);
				return false;
			}
		},
		'mousedown' : {
			'.addon' : function () {
				$('#groups').sortable('disable');
			}
		},
		'mouseup' : {
			'.addon' : function () {
				$('#groups').sortable('enable');
			}
		}
	},
	'dialog' : {
		'avatar' : {
			'width' : 500,
			'height' : 200,
			'position' : ['center', 150]
		},
		'progress' : {
			'width' : 244,
			'height' : 40,
			'buttons' : {},
			'position' : [100, 100]
		},
		'almostdone' : {
			'width' : 350,
			'height' : 250,
			'buttons' : {},
			'position' : ['center', 150],
			'open' : function () {
				$('#name').focus();
			},
			'close' : function () {
				$('#name').val('');
			}
		},
		'editcomplete' : {
			'width' : 400,
			'height' : 200,
			'position' : ['center', 200]
		},
		'info' : {
			'width' : 600,
			'height': 400,
			'buttons' : {},
			'position' : ['center', 120],
			'open' : function () {
				gfx.page.info = {};
				$.each(
					['name', 'email', 'web', 'blog', 'forum', 'bio'],
					function () {
						gfx.page.info[this] = $('#info_' + this).val();
					}
				);
				$('#info_name').focus();
			},
			'close' : function () {
				if (gfx.page.info) {
					$.each(
						['name', 'email', 'web', 'blog', 'forum', 'bio'],
						function () {
							$('#info_' + this).val(gfx.page.info[this]);
						}
					);
					delete gfx.page.info;
				}
			}
		},
		'addons' : {
			'width' : 800,
			'height': 500,
			'buttons' : {},
			'position' : ['center', 50],
			'open' : function () {
				$('#addon_query').val('').focus();
				
				//suggest addon
				var r = $('#addon_query_result').empty();
				$('#addon_query_desc').show().text(String.fromCharCode(160)); // &nbsp;
				$('#addon_query_notfound').hide();
				gfx.xhr = $.ajax(
					{
						url: './addon/suggest',
						data: {
							'token' : $('#token').val(),
							'g' : gfx.page.currentGroup
						},
						/* don't show progress window  */
						beforeSend : function (xhr) { },
						complete : function (xhr, status) { },
						error: function (xhr, status, error) { },
						success: function (result, status) {
							/*if (gfx.ajaxError(result)) {
								return;
							}*/
							/* don't alert ajax error */
							if (result.message) {
								return;
							}
							if (!result.addons.length) {
							/*	$('#addon_query_notfound').show();
								$('#addon_query_desc').hide();*/
								return;
							}
							$('#addon_query_desc').text(T.UI.ADDON_SUGGEST_LIST);
							$.each(
								result.addons,
								function (i, d) {
									r.append(
										new gfx.page.Addon(d, true, false)
									);
								}
							);
						}
					}
				);
			}
		},
		'delete' : {
			'width' : 400,
			'height': 350,
			'buttons' : {},
			'position' : ['center', 200],
			'open' : function () {
				$('#delete-url-notice').toggle($('.name-placeholder:first').text() !== '');
			}
		}
	},
	'onload' : function () {
		this.dialog.progress.buttons[T.BUTTONS.PROGRESS_FORCESTOP] = function () {
			if (gfx.xhr) {
				gfx.xhr.abort();
			}
			//because Flash object doesn't init before openDialog(avatar);
			try {
				gfx.page.swfupload.cancelUpload();
			} catch (e) {
			}
			gfx.closeDialog('progress');
		};
		this.dialog.almostdone.buttons[T.BUTTONS.ALMOSTDONE_OK] = gfx.page.savePage;
		this.dialog.info.buttons[T.BUTTONS.INFO_SAVE] = function () {
			if (!$('#info_name').val()) {
				gfx.alert(T.ALERT.EDITOR_NAME_EMPTY);
				$('#info_name').focus();
				return;
			}
			//Gather data
			//Save title because we have to make sure name and title are vaild at same time.
			var d = {
				'token' : $('#token').val()
			};
			$.each(
				['name', 'email', 'web', 'blog', 'forum', 'bio'],
				function () {
					d[this] = $('#info_' + this).val();
				}
			);
			//check for errors
			if (!gfx.page.validate.name(d.name)) {
				return;
			}
			//ajax send
			gfx.xhr = $.ajax(
				{
					url: './editor/save',
					data: d,
					success: function (result, status) {
						if (gfx.ajaxError(result)) {
							return;
						}
						$.each(
							['name', 'email', 'web', 'blog', 'forum', 'bio'],
							function () {
								gfx.page.info[this] = $('#info_' + this).val();
							}
						);
						$('.name-placeholder').text(result.name);
						gfx.message('highlight', 'info', T.UI.INFO_UPDATED);
						gfx.closeDialog('info');
					}
				}
			);
			
		};
		this.dialog.info.buttons[T.BUTTONS.INFO_CANCEL] = function () {
			gfx.closeDialog('info');
		};
		this.dialog.addons.buttons[T.BUTTONS.ADDON_ADD_OK] = function () {
			$('#addon_query_result .add-addon input:checked').each(
				function () {
					$('#g_' + gfx.page.currentGroup + ' + div.group-addons').append(
						new gfx.page.Addon($(this).data('addon'), false, true)
					).sortable('refresh');
				}
			);
			gfx.closeDialog('addons');
			gfx.page.addonChanged = true;
			$('#editor-save button').attr('disabled', null);
		};
		this.dialog['delete'].buttons[T.BUTTONS.DELETE_OK] = function () {
			window.onbeforeunload = function (e) {
				return null;
			}
			$('#delete_post').submit();
		};

		if (window.postMessage) {
			window.addEventListener(
				'message',
				function (e) {
					$('#info_forum').val(e.data);
					$('#forum_auth_iframe').css('display', null).empty();
				},
				false
			);
		}

		$('#forum_auth').get(0).href += '?token=' + $('#token').val().substr(0, 16);

		if ($.browser.msie) {
			gfx.message('error', 'alert', T.UI.USING_IE_TO_EDIT);
		}

		var bar = {
			el : $('#editor-save'),
			pl : $(document.createElement('div')),
			doc : $(document)
		};
		bar.el.after(
			bar.pl.attr({'id':'editor-save-placeholder'})
			.css('height', bar.el.height())
			.hide()
		);
		
		$(window).bind(
			'scroll',
			function (e) {
				var pos;
				if (bar.el.css('position') === 'fixed') {
					pos = bar.pl.offset().top;
				} else {
					pos = bar.el.offset().top;
				}
				if (bar.doc.scrollTop() > pos) {
					if (bar.el.is(':visible')) {
						bar.pl.show();
					}
					//This will break in IE6
					bar.el.css(
						{
							'position' : 'fixed',
							'top' : '0'
						}
					);
					//non position: fixed solution.
					/*
					bar.el.css(
						{
							'position' : 'absolute',
							'top' : bar.doc.scrollTop()
						}
					);
					*/
				} else {
					bar.el.css(
						{
							'position' : null, //This breaks IE7 ('static' works but not null )
							'top' : null
						}
					);
					bar.pl.hide();
				}
			}
		).scroll();
		
		$('#editor-save button').attr('disabled', 'disabled');

		window.onbeforeunload = function (e) {
			if (
				gfx.page.infoChanged ||
				gfx.page.avatar ||
				gfx.page.featureChanged ||
				gfx.page.groupChanged ||
				gfx.page.addonChanged
			) {
				return T.UI.CONFIRM_QUIT;
			} else {
				return null;
			}
		};

		$('#title-name, #addon_query').attr('autocomplete','off');

		if ($('#title-name').text() === '') {
			$('#title-name').css('display', 'none');
			$('#title-name-edit input').css('display', 'block').addClass('empty').val(T.UI.EMPTY_TITLE);
			$('span.title-placeholder').addClass('title-empty').text(T.UI.TITLE_PLACEHOLDER);
		} else {
			$('span.title-placeholder').text($('#title-name').text());
		}
		$('#groups input').each(
			function (i) {
				$(this).parent().toggleClass('not-selected', !this.checked);
			}
		);
		gfx.page.swfupload = new SWFUpload(
			{
				'upload_url': window.location.href + '/upload',
				// File Upload Settings
				file_size_limit : 1024,	// 1MB
				file_types : '*.jpg;*.jpeg;*.gif;*.png',
				file_types_description : 'Images',
				file_upload_limit : '0',
				button_placeholder_id : 'avatar_spfupload_replace',
				button_width: 69,
				button_height: 69,
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,
				flash_url : './swfupload/swfupload.swf',	// Relative to this file
				debug : false,
				// Event Handler Settings
				'file_dialog_complete_handler' : function (n, q) {
					if (n === 1 && q === 1) {
						this.setButtonDisabled(true);
						window.setTimeout(
							function () {
								if (gfx.page.swfupload.getStats().in_progress !== 0) {
									gfx.openDialog('progress');
								}
							},
							400
						);
						this.startUpload();
					}
				},
				'file_queue_error_handler' : function (file, error, msg) {
					switch (error) {
						case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
						gfx.alert(T.SWFUPLOAD.ZERO_BYTE_FILE || msg, 'SWFUPLOAD_ZERO_BYTE_FILE');
						break;
						case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
						gfx.alert(T.SWFUPLOAD.FILE_EXCEEDS_SIZE_LIMIT || msg, 'SWFUPLOAD_FILE_EXCEEDS_SIZE_LIMIT');
						break;
						case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
						gfx.alert(T.SWFUPLOAD.INVALID_FILETYPE || msg, 'SWFUPLOAD_INVALID_FILETYPE');
						break;
						default:
						gfx.alert(msg, 'SWFUPLOAD_UNKNOWN_QUERE_ERROR');
						break;
					}
				},
				'upload_error_handler' : function (file, error, msg) {
					this.setButtonDisabled(false);
					gfx.closeDialog('progress');
					if (error !== SWFUpload.UPLOAD_ERROR.FILE_CANCELLED) {
						gfx.alert(msg, 'SWFUPLOAD_UNKNOWN_UPLOAD_ERROR');
					}
				},
				'upload_success_handler' : function (file, result) {
					this.setButtonDisabled(false);
					gfx.closeDialog('progress');
					if (JSON && JSON.parse) {
						/* Fx 3.1 Native JSON parser */
						try {
							result = JSON.parse(result);
						} catch (e) {
							result = null;
						}						
					} else {
						//Great, jQuery doen't have a JSON.decode function w/o HTTP request.
						//We get this from mootools source.
						if (JSON && JSON.parse) {
							//Firefox 3.1 JSON parser
							try {
								result = JSON.parse(result);
							} catch (e) {
								result = null;
							}
						} else {
							var decode = function (string) {
								if (!(/^[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]*$/).test(string.replace(/\\./g, '@').replace(/"[^"\\\n\r]*"/g, ''))) {
									return null;
								}
								return eval('(' + string + ')');
							};
							result = decode(result);
						}
					}
					if (!result) {
						gfx.alert(T.AJAX_ERROR.PARSE_RESPONSE, 'AJAX_ERROR_PARSE_RESPONSE');
					} else {
						if (gfx.ajaxError(result)) {
							return;
						}
						gfx.page.changeAvatar(result.img, './useravatars/' + result.img);
					}
				}
			}
		);
		
		$('#featureselection li').draggable(
			{
				revert: 'invalid',
				helper: 'clone',
				zIndex: 5,
				cancel: '.selected',
				start: function () {
					$('.feature').css('opacity', 0.5);
				},
				stop: function () {
					$('.feature').css('opacity', 1);
				}
			}
		);
		
		$('#features').sortable(
			{
				containment: 'document',
				revert: 250,
				update: function (e, ui) {
					gfx.page.featureChanged = true;
					$('#editor-save button').attr('disabled', null);
				}
			}
		)
		$('.feature').droppable(
			{
				accept: '#featureselection li:not(.selected)',
				drop: function (event, ui) {
					var s = $(ui.draggable);
					var d = {
						'name' : s.attr('id').substr(3),
						'id' : s.attr('rel').substr(4),
						'title' : s.text(),
						'description' : s.attr('title')
					};
					var b = $(this);
					if (!b.hasClass('box')) {
						$('#fs-' + b.attr('id')).removeClass('selected');
					} else {
						b.removeClass('box');
					}
					s.addClass('selected');
					b.attr('id', d.name);
					b.find('h2')
					.attr('id', 'featureid-' + d.id)
					.text(d.title);
					b.find('p:first').text(d.description);
					b.find('.link a').attr('href', './feature/' + d.name);
					gfx.page.featureChanged = true;
					$('#editor-save button').attr('disabled', null);
				},
				over : function () {
					$(this).css('opacity', 1);
				},
				out : function () {
					$(this).css('opacity', 0.5);
				}
			}
		);
		$('#groups').sortable(
			{
				handle: '.group-title',
				containment: 'document',
				revert: 250,
				update: function () {
					gfx.page.groupChanged = true;
					$('#editor-save button').attr('disabled', null);
				}
			}
		);
		$('#groups .group-addons').sortable(
			{
				containment: 'document',
				revert: 250,
				//handle: 'p > a',
				connectWith: ['#groups .group-addons'],
				update: function () {
					gfx.page.addonChanged = true;
					$('#editor-save button').attr('disabled', null);
				}
			}
		);
	},
	'validate' : {
		'name' : function (name) {
			if (name.length > 60) {
				gfx.alert('EDITOR_NAME_LENGTH');
				return false;
			}
			if (!/^[a-zA-Z0-9_\-]+$/.test(name) || name.length < 3) {
				gfx.alert('EDITOR_NAME_BAD');
				return false;
			}
			return true;
		},
		'title' : function (title) {
			if (title === '') {
				gfx.alert('EDITOR_TITLE_EMPTY');
				//Scroll to top.
				if ($(window).scrollTop() > 70) {
					$(window).scrollTop(70);
				}
				$('#title-name-edit input').focus();
				return false;
			}
			if (title.length > 128) {
				gfx.alert('EDITOR_TITLE_LENGTH');
				return false;
			}
			return true;
		}
	},
	'changeAvatar' : function (avatar, url) {
		gfx.page.avatar = avatar;
		$('#editor-save button').attr('disabled', null);
		$('#title-avatar img:only-child').attr('src', url);
		gfx.closeDialog('avatar');
	},
	'savePage' : function () {
		//Gather data
		var d = {
			'token' : $('#token').val(),
			'title' : $('#title-name').text(),
			'name' : $('#info_name').val() || $('#name').val(),
			'ready' : 'Y' /* indicate all informations checks out and the page is available to everyone */
		};
		var g = $('.group-title input:checked');
		if (!g.length) {
			gfx.alert('EDITOR_GROUP_EMPTY');
			return;
		}
		if ($('.feature.box').length) {
			gfx.alert('EDITOR_FEATURE_COUNT');
			return;
		}
		if (d.name === '') {
			gfx.openDialog('almostdone');
			return;
		}
		if (gfx.page.avatar) {
			d.avatar = gfx.page.avatar;
		}
		if (gfx.page.featureChanged) {
			$('.feature h2').each(
				function (i) {
					d['features[' + (i+1) + ']'] = this.id.substr(10);
				}
			);
		}
		if (gfx.page.groupChanged) {
			g.each(
				function (i) {
					d['groups[' + (i+1) + ']'] = $(this).parent().attr('id').substr(2);
				}
			);
		}
		if (gfx.page.addonChanged) {
			$('#groups .addon').each(
				function (i) {
					d['addons[' + (i+1) + '][id]'] = this.id.substr(2);
					d['addons[' + (i+1) + '][group]'] = $(this).parent().prev().attr('id').substr(2);
				}
			);
		}
		//check for errors
		if (!gfx.page.validate.name(d.name)) {
			return;
		}
		if (!gfx.page.validate.title(d.title)) {
			return;
		}
		//ajax send
		gfx.xhr = $.ajax(
			{
				url: './editor/save',
				data: d,
				success: function (result, status) {
					if (gfx.ajaxError(result)) {
						return;
					}
					$('#info_name').val(result.name);

					gfx.page.infoChanged
					= gfx.page.avatar
					= gfx.page.featureChanged
					= gfx.page.groupChanged
					= gfx.page.addonChanged = null;
					$('#editor-save button').attr('disabled', 'disabled');

					$('#window_userpage_url').attr('href', './' + result.name);
					$('.name-placeholder').text(result.name);
					gfx.closeDialog('almostdone');
					gfx.openDialog('editcomplete');
				}
			}
		);
	},
	'showBar' : function () {
		var el = $('#editor-save').show();
		$(window).scroll();
	},
	'hideBar' : function () {
		var el = $('#editor-save').hide();
		if (el.css('position') === 'fixed') {
			$('#editor-save-placeholder').hide();
			$(document).scrollTop(
				$(document).scrollTop()
				- el.height()
		//		- parseInt(el.css('margin-top'))
				- parseInt(el.css('margin-bottom'))
			);
		}
	},
	'Addon' : function (d, add, del) {
		var o = $(document.createElement('div'))
			.attr(
				{
					'class' : 'addon',
					'id' : 'a_' + d.id
				}
			).append(
				$(document.createElement('p'))
				.append(
					$(document.createElement('a'))
					.attr('href', d.url).append(
						$(document.createElement('img')).attr(
							{
								'src' : d.icon_url || 'images/addon_default_icon.png',
								'alt' : ''
							}
						)
					).append(
						$(document.createElement('span')).text(d.title)
					)
				).append(
					$(document.createElement('p'))
					.attr('class', 'desc')
					.text(d.description)
				)
			);
		if (add) {
			o.prepend(
				$(document.createElement('p'))
				.attr(
					{
						'class' : 'add-addon'
					}
				).append(
					$(document.createElement('input')).attr(
						{
							'id' : 'addon_add_' + d.id,
							'type' : 'checkbox'
						}
					).data('addon', d)
				).append(
					$(document.createElement('label')).attr(
						{
							'for' : 'addon_add_' + d.id
						}
					).text(T.UI.ADDON_ADD)
				)
			);
			if ($('#a_' + d.id).length) {
				o.find('input').attr('disabled', 'disabled').next().text(T.UI.ADDON_ADD_CANT_DUP);
			}
		}
		if (del) {
			o.prepend(
				$(document.createElement('p')).attr(
					{
						'class' : 'del-addon ui-icon ui-icon-close',
						'title' : T.UI.ADDON_DEL
					}
				).text(
					T.UI.ADDON_DEL
				)
			);
		}
		return o;
	}
};
