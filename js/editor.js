/*

How does editor work?
First, onload function inject all the control or callback into buttons on pages.
The information is almost entirely store on the DOM itself, execpt some bools that indicate "things were changed".

savePage() grab all the information before making the final submission via ajax.

Meaning, nothing is going to send to the users table unless s/he save the page.

Avatars and addons does upload/send to the server for processing and checking vaildity,
but one still have to save the page in order to keep avatars s/he uploaded and addons s/he included.

*/

gfx.editor = {
	'bind' : {
		'click' : {
			'#editor_save_button' : function () {
				//Dumb, but we cannot self-reference at this point.
				gfx.editor.savePage();
			},
			'#save_page' : function () {
				gfx.editor.savePage();
			},
			'#title-name' : function () {
				var t = $(this);
				t.css('display', 'none');
				$('#title-name-edit input')
				.css('display', 'block')
				.val(t.text()).focus();
			},
			'#featureselection_save button:only-child' : function () {
				gfx.editor.changeFeatureSelection();
			},
			'#title-avatar' : function () {
				gfx.openWindow('avatar');
			},
			'#avatar_glavatar' : function () {
				gfx.editor.changeAvatar('(gravatar)', $(this).children()[0].src);
			},
			'#avatar_default' : function () {
				gfx.editor.changeAvatar('(default)', './images/keyhole.gif');
			},
			'#groups input' : function () {
				if (this.checked) $(this).parent().removeClass('not-selected');
				else $(this).parent().addClass('not-selected');
				gfx.editor.groupChanged = true;
			},
			'#groups .group-add-addon a' : function () {
				gfx.editor.currentGroup = this.parentNode.parentNode.id.substr(2);
				gfx.openWindow('addons');
				$('#addon_query').val('').focus();
				gfx.editor.suggestAddon();
				return false;
			},
			/*'.addon p > a' : function () {
				window.open(this.href);
				return false;
			},*/
			'.del-addon' : function () {
				$(this).parent('.addon').remove();
				gfx.editor.addonChanged = true;
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
					$(this).css('display', null);
					gfx.editor.infoChanged = true;
				} else {
					$('#title-name').text(this.value);
					$(this).addClass('empty').val(T['EDITOR_EMPTY_TITLE']);
				}
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
				gfx.editor.queryAddon();
				return false;
			},
			'#title-name-form' : function () {
				$('#title-name-edit input').blur();
				return false;
			}
		}
	},
	'onload' : function () {
		$.each(
			gfx.editor.bind,
			function(e, o) {
				$.extend(
					gfx.bind[e] || (gfx.bind[e] = {}),
					o
				);
			}
		);
		$.extend(
			gfx.windowOption || (gfx.windowOption = {}),
			{
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
					'position' : ['center', 150]
				},
				'editcomplete' : {
					'width' : 400,
					'height' : 200,
					'position' : ['center', 200]
				},
				'addons' : {
					'width' : 800,
					'height': 500,
					'buttons' : {},
					'position' : ['center', 50]
				}
			}
		);
		//Javascript language structure bug?
		gfx.windowOption.progress.buttons[T['PROGRESS_FORCESTOP']] = gfx.editor.forceStop;
		gfx.windowOption.almostdone.buttons[T['ALMOSTDONE_OK']] = gfx.editor.savePage;
		gfx.windowOption.addons.buttons[T['ADDON_ADD_CONFIRM']] = gfx.editor.addAddon;

		$(window).bind(
			'scroll',
			function (e) {
				offset = $(document).scrollTop();
				if (offset > 50) {
					if (!$('#editor_save_placeholder').length) {
						$('#editor_save').after(
							$(document.createElement('div'))
							.attr('id', 'editor_save_placeholder')
							.css('height', $('#editor_save').height())
						);
					}
					//This will break in IE6
					$('#editor_save').css(
						{
							'position' : 'fixed',
							'top' : '0'
						}
					);
					//non position: fixed solution.
					/*
					$('#editor_save').css(
						{
							'position' : 'absolute',
							'top' : offset
						}
					);
					*/
				} else {
					$('#editor_save').css(
						{
							'position' : null, //This breaks IE7 ('static' works but not null )
							'top' : null
						}
					);
					$('#editor_save_placeholder').remove();
				}
			}
		).scroll();
		window.onbeforeunload = function (e) {
			if (
				gfx.editor.infoChanged ||
				gfx.editor.avatar ||
				gfx.editor.featureChanged ||
				gfx.editor.groupChanged ||
				gfx.editor.addonChanged
			) {
				return T.EDITOR_CONFIRM_QUIT;
			} else {
				return null;
			}
		};

		$('#title-name, #addon_query').attr('autocomplete','off');

		if ($('#title-name').text() === '') {
			$('#title-name').css('display', 'none');
			$('#title-name-edit input').css('display', 'block').addClass('empty').val(T['EDITOR_EMPTY_TITLE']);
		}
		$('#groups input').each(
			function (i) {
				if (this.checked) $(this).parent().removeClass('not-selected');
				else $(this).parent().addClass('not-selected');
			}
		);
		gfx.editor.swfupload = new SWFUpload(
			{
				'upload_url': location.href + '/upload',
				// File Upload Settings
				file_size_limit : 1024,	// 1MB
				file_types : '*.jpg;*.jpeg;*.gif;*.png',
				file_types_description : 'Images',
				file_upload_limit : '0',
				button_placeholder_id : 'avatar_spfupload_replace',
				button_width: 65,
				button_height: 65,
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,
				flash_url : './swfupload/swfupload.swf',	// Relative to this file
				debug : false,
				// Event Handler Settings
				'file_dialog_complete_handler' : function (n, q) {
					if (n === 1 && q === 1) {
						this.setButtonDisabled(true);
						setTimeout(
							function () {
								if (gfx.editor.swfupload.getStats().in_progress !== 0) {
									gfx.openWindow('progress');
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
						window.alert(T['SWFUPLOAD_ZERO_BYTE_FILE'] || msg);
						break;
						case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
						window.alert(T['SWFUPLOAD_FILE_EXCEEDS_SIZE_LIMIT'] || msg);
						break;
						case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
						window.alert(T['SWFUPLOAD_INVALID_FILETYPE'] || msg);
						break;
						default:
						window.alert(msg);
						break;
					}
				},
				'upload_error_handler' : function (file, error, msg) {
					this.setButtonDisabled(false);
					gfx.closeWindow('progress');
					if (error !== SWFUpload.UPLOAD_ERROR.FILE_CANCELLED) {
						window.alert(msg);
					}
				},
				'upload_success_handler' : function (file, result) {
					this.setButtonDisabled(false);
					gfx.closeWindow('progress');
					//Great, jQuery doen't have a JSON.decode function w/o HTTP request.
					//We get this from mootools source.
					var JSONdecode = function (string) {
						if (!(/^[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]*$/).test(string.replace(/\\./g, '@').replace(/"[^"\\\n\r]*"/g, ''))) return null;
						return eval('(' + string + ')');
					}
					result = JSONdecode(result);
					if (!result) {
						window.alert(T['EMPTY_ERROR']);
					} else if (result.error) {
						window.alert(T[result.tag] || result.error);
					} else {
						gfx.editor.changeAvatar(result.img, './useravatars/' + result.img);
					}
				}
			}
		);
		$('#features').sortable(
			{
				containment: 'document',
				revert: 250,
				update: function (e, ui) {
					gfx.editor.featureChanged = true;
				}
			}
		);
		$('#groups').sortable(
			{
				handle: '.group-title',
				containment: 'document',
				revert: 250,
				update: function () {
					gfx.editor.groupChanged = true;
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
					gfx.editor.addonChanged = true;
				}
			}
		);
		$.ajaxSetup(
			{
				type: 'POST',
				timeout: 20000,
				dataType: 'json',
				beforeSend : function (xhr) {
					setTimeout(
						function () {
							if (gfx.xhr.readyState !== 4) {
								gfx.openWindow('progress');
							}
						},
						400
					);
				},
				complete : function (xhr, status) {
					gfx.closeWindow('progress');
				},
				error: function (xhr, status, error) {
					gfx.closeWindow('progress');
					switch (status) {
						case 'timeout':
						window.alert(T['TIMEOUT']);
						break;
						case 'parsererror':
						window.alert(T['EMPTY_ERROR']);
						break;
						case 'error':
						default:
						window.alert(T['ERROR']);
					}
				}
			}
		);
	},
	'forceStop' : function () {
		if (gfx.xhr) gfx.xhr.abort();
		//because Flash object doesn't init before openwindow(avatar);
		try {
			gfx.editor.swfupload.cancelUpload();
		} catch (e) {
		}
		gfx.closeWindow('progress');
	},
	'changeFeatureSelection' : function () {
		var s = [];
		gfx.editor.featureChanged = true;
		$('#featureselection input:checked').each(
			function (i) {
				var t = $(this);
				s[s.length] = [
					this.name.substr(3),
					this.id.substr(3),
					t.next().text(),
					t.next().attr('title')
				];
			}
		);
		if (s.length !== 3) {
			window.alert(T['EDITOR_FEATURES_COUNT']);
			return;
		}
		var Feature = function (d) {
			return $(document.createElement('div'))
			.attr({
				'id' : d[1],
				'class' : 'feature'
			})
			.append(
				$(document.createElement('h2'))
				.attr('id', 'featureid-' + d[0])
				.text(d[2])
			).append(
				$(document.createElement('p'))
				.text(d[3])
			).append(
				$(document.createElement('p'))
				.addClass('link')
				.append(
					$(document.createElement('a'))
					.attr('href', './features/' + d[1])
					.text('More...')
				)
			);
		}
		$('.feature').each(
			function (i) {
				if (!$('#fs_' + this.id + ':checked').length) {
					$(this).remove();
				}
			}
		);
		var f = $('#features');
		$.each(
			s,
			function (i, d) {
				if (!$('#' + d[1]).length) {
					f.append(new Feature(d));
				}
			}
		);
		f.sortable("refresh");
		return false;
	},
	'changeAvatar' : function (avatar, url) {
		gfx.editor.avatar = avatar;
		$('#title-avatar img:only-child').attr('src', url);
		gfx.closeWindow('avatar');
	},
	'savePage' : function () {
		//Gather data
		var d = {
			'token' : $('#token').val(),
			'title' : $('#title-name').text(),
			'name' : $('#name').val()
		}
		if (d['title'] === '') {
			window.alert(T['EDITOR_NO_TITLE']);
			$('#title-name-edit input').focus();
			return;
		}
		if (d['name'] === '') {
			gfx.openWindow('almostdone');
			$('#name').focus();
			return;
		}
		if (gfx.editor.avatar) {
			d.avatar = gfx.editor.avatar;
		}
		if (gfx.editor.featureChanged) {
			$('.feature h2').each(
				function (i) {
					d['features[' + (i+1) + ']'] = this.id.substr(10);
				}
			);
		}
		if (gfx.editor.groupChanged) {
			$('.group-title input:checked').each(
				function (i) {
					d['groups[' + (i+1) + ']'] = $(this).parent().attr('id').substr(2);
				}
			);
		}
		if (gfx.editor.addonChanged) {
			$('#groups .addon').each(
				function (i) {
					d['addons[' + (i+1) + '][id]'] = this.id.substr(2);
					d['addons[' + (i+1) + '][group]'] = $(this).parent().prev().attr('id').substr(2);
				}
			);
		}
		//check for errors
		if (d.title.length > 128) {
			window.alert(T['EDITOR_TITLE_LENGTH']);
			return;
		}
		if (d.name.length > 60) {
			window.alert(T['EDITOR_NAME_LENGTH']);
			return;
		}
		if (!/^[a-zA-Z0-9_\-]+$/.test(d.name)) {
			window.alert(T['EDITOR_BAD_NAME']);
			return;
		}
		//ajax send
		gfx.xhr = $.ajax(
			{
				url: './editor/save',
				data: d,
				success: function (result, status) {
					if (result.error) {
						window.alert(T[result.tag] || result.error);
						return;
					}

					gfx.editor.infoChanged
					= gfx.editor.avatar
					= gfx.editor.featureChanged
					= gfx.editor.groupChanged
					= gfx.editor.addonChanged = null;

					$('#window_userpage_url').attr('href', './' + result.name);
					gfx.closeWindow('almostdone');
					gfx.openWindow('editcomplete');
				}
			}
		);
	},
	'Addon' : function (d, add, del) {
		var o = $(document.createElement('div'))
			.attr(
				{
					'class' : 'addon',
					'id' : 'a_' + d['id']
				}
			).append(
				$(document.createElement('p'))
				.append(
					$(document.createElement('a'))
					.attr('href', d['url']).append(
						$(document.createElement('img')).attr(
							{
								'src' : d['icon_url'] || 'images/addon_default_icon.png',
								'alt' : ''
							}
						)
					).append(
						$(document.createElement('span')).text(d['title'])
					)
				)
			.append(
				$(document.createElement('p'))
				.attr('class', 'desc')
				.text(d['description'])
			)
			).bind(
				'mousedown',
				function () {
					$('#groups').sortable('disable');
				}
			).bind(
				'mouseup',
				function () {
					$('#groups').sortable('enable');
				}
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
							'id' : 'addon_add_' + d['id'],
							'type' : 'checkbox'
						}
					).data('addon', d)
				).append(
					$(document.createElement('label')).attr(
						{
							'for' : 'addon_add_' + d['id']
						}
					).text(T.ADDON_ADD)
				)
			);
			o.find('a').bind(
				'click',
				function () {
					window.open(this.href);
					return false;
				}
			);
			if ($('#a_' + d['id']).length) {
				o.find('input').attr('disabled', 'disabled').next().attr('title', T.ADDON_CANNOT_ADD);
			}
		}
		if (del) {
			o.prepend(
				$(document.createElement('p')).attr(
					{
						'class' : 'del-addon'
					}
				).text(
					T.ADDON_DEL
				).bind(
					'click',
					function () {
						$(this).parent('.addon').remove();
						gfx.editor.addonChanged = true;
						return false;
					}
				)
			);
		}
		return o;
	},
	'queryAddon' : function () {
		var r = $('#addon_query_result').empty();
		$('#addon_query_desc').show().text(String.fromCharCode(160)); // &nbsp;
		$('#addon_query_notfound').hide();
		gfx.xhr = $.ajax(
			{
				url: './addon/query',
				data: {
					'q' : $('#addon_query').val().replace(/^https:\/\/addons.mozilla.org\/[\w-]{5}\/firefox\/addon\/(\d+)$/, '$1')
				},
				success: function (result, status) {
					if (result.error) {
						window.alert(T[result.tag] || result.error);
						return;
					}
					if (!result.addons.length) {
						$('#addon_query_notfound').show();
						$('#addon_query_desc').hide();
						return;
					}
					$('#addon_query_desc').text(T['ADDON_SEARCH_RESULT']);
					$.each(
						result.addons,
						function (i, d) {
							r.append(
								new gfx.editor.Addon(d, true, false)
							);
						}
					);
					if (result.addons.length === 1) {
						r.find('input[disabled!=true]').attr('checked', true);
					}
				}
			}
		);
	},
	'suggestAddon' : function () {
		var r = $('#addon_query_result').empty();
		$('#addon_query_desc').show().text(String.fromCharCode(160)); // &nbsp;
		$('#addon_query_notfound').hide();
		gfx.xhr = $.ajax(
			{
				url: './addon/suggest',
				data: {
					'g' : gfx.editor.currentGroup
				},
				success: function (result, status) {
					/*if (result.error) {
						window.alert(T[result.tag] || result.error);
						return;
					}*/
					if (!result.addons.length) {
					/*	$('#addon_query_notfound').show();
						$('#addon_query_desc').hide();*/
						return;
					}
					$('#addon_query_desc').text(T.ADDON_SUGGEST_LIST);
					$.each(
						result.addons,
						function (i, d) {
							r.append(
								new gfx.editor.Addon(d, true, false)
							);
						}
					);
				}
			}
		);
	},
	'addAddon' : function () {
		$('#addon_query_result .add-addon input:checked').each(
			function () {
				$('#g_' + gfx.editor.currentGroup + ' + div.group-addons').append(
					new gfx.editor.Addon($(this).data('addon'), false, true)
				).sortable('refresh');
			}
		);
		gfx.closeWindow('addons');
		gfx.editor.addonChanged = true;
	}
};
