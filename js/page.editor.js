/*global window, document, $, T, gfx */

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
			'#title-avatar, #title-avatar-textarea' : function () {
				gfx.openDialog('avatar');
				return false;
			},
			'#avatar_gravatar' : function () {
				if ($(this).parent().is('.disabled')) return false;
				gfx.page.changeAvatar('(gravatar)', $(this).children()[0].src);
			},
			'#avatar_default' : function () {
				gfx.page.changeAvatar('(default)', './images/avatar-default.gif');
			},
			'#avatar_myidtw' : function () {
				if ($(this).parent().is('.disabled')) return false;
				gfx.page.changeAvatar('(myidtw)', $(this).children()[0].src);
			},
			'#groups input' : function () {
				$(this).parent().toggleClass('not-selected', !this.checked);
				gfx.page.groupChanged = true;
				gfx.page.blinkBar();
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
					$('#title-name').text(this.value); /* .css('display', null); */
					$('span.title-placeholder').removeClass('title-empty').text(this.value);
					/* $(this).css('display', null); */
					gfx.page.infoChanged = true;
					gfx.page.blinkBar();
				} else {
					$('#title-name').text(this.value);
					$('span.title-placeholder').addClass('title-empty').text(T.UI.TITLE_PLACEHOLDER);
					$(this).addClass('empty').val(T.UI.EMPTY_TITLE);
				}
			}
		},
		'change' : {
			'#avatar_fileupload input' : function () {
				gfx.page.uploadFile(this.files);
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
							'q' : $('#addon_query').val().replace(/^https:\/\/addons.mozilla.org\/[\w\-]{2,5}\/firefox\/addon\/(\d+)$/, '$1')
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
		},
		'dragenter' : {
			'body' : function (ev) {
				if (window.FileReader) {
					$('#window_avatar').addClass('dropindication').removeClass('removepending');
					$('#title-avatar').addClass('dropindation');
				}
			},
			'#window_avatar' : function () {
				$('#window_avatar').addClass('dropenter');
			}
		},
		'dragleave' : {
			'body' : function (ev) {
				if (window.FileReader) {
					$('#window_avatar').addClass('removepending');
					//Must use setTimeout and "pending" condition otherwise ondrop event won't fire
					setTimeout(
						function () {
							$('#window_avatar.removepending').removeClass('dropindication removepending');
						},
						0
					);
					$('#title-avatar').removeClass('dropindation');
				}
			},
			'#window_avatar' : function () {
				$('#window_avatar').removeClass('dropenter');
			}
		},
		'dragover' : {
			'#title-avatar, #window_avatar' : function (ev) {
				return false;
			}
		},
		'drop' : {
			'#title-avatar, #window_avatar' : function (ev) { 
				// No '#avatar_fileupload input' coz file input cannot receive drop event.
				// No '#dropzone' coz it disappears as soon as removeClass runs.
				if (!ev.originalEvent.dataTransfer.files) {
					//Supports drag drop events but not file reading. (Safari as of now)
					return false;
				}
				// $('#title-avatar').addClass('dropindation'); //cannot do that coz abort()
				$('#window_avatar').removeClass('dropindication');
				gfx.page.uploadFile(ev.originalEvent.dataTransfer.files);
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
				gfx.page.blinkBar();
				return false;
			}
		}
	},
	'dialog' : {
		'avatar' : {
			'width' : 600,
			'height' : 300,
			'position' : ['center', 150]
		},
		'almostdone' : {
			'width' : 350,
			'height' : 290,
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
			'height' : 240,
			'position' : ['center', 200]
		},
		'info' : {
			'width' : 600,
			'height': 440,
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
			'height': 540,
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
			'height': 390,
			'buttons' : {},
			'position' : ['center', 200],
			'open' : function () {
				$('#delete-url-notice').toggle($('.name-placeholder:first').text() !== '');
			}
		}
	},
	'onload' : function () {
		this.dialog.almostdone.buttons[T.BUTTONS.ALMOSTDONE_OK] = gfx.page.savePage;
		this.dialog.info.buttons[T.BUTTONS.INFO_SAVE] = function () {
			if (!$('#info_name').val()) {
				gfx.alert('EDITOR_NAME_EMPTY');
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
					d[this] = $.trim($('#info_' + this).val());
				}
			);
			//check for errors
			if (!gfx.page.validate.name(d.name)) {
				return;
			}
			if (!gfx.page.validate.url(d.web)) {
				return;
			}
			if (!gfx.page.validate.url(d.blog)) {
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
						gfx.message('highlight', 'info', T.UI.INFO_UPDATED, 'INFO_UPDATED');
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
			gfx.page.blinkBar();
		};
		this.dialog['delete'].buttons[T.BUTTONS.DELETE_OK] = function () {
			window.onbeforeunload = function (e) {
				return null;
			}
			$('#delete_post').submit();
		};

		if (window.postMessage) {
			var onMsg = function (e) {
				$('#info_forum').val(e.data);
				$('#forum_auth_iframe').css('display', null).empty();
			};
			if (window.addEventListener) {
				window.addEventListener('message', onMsg, false);
			}/* else if (window.attachEvent) { // IE8
				window.attachEvent('onmessage', onMsg);
			}*/
		}

		$('#forum_auth').get(0).href += '?token=' + $('#token').val().substr(0, 16);

		if ($.browser.msie) {
			gfx.message('error', 'alert', T.UI.USING_IE_TO_EDIT, 'USING_IE_TO_EDIT');
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

		$('#title-name').css('display', 'none');
		if ($('#title-name').text() === '') {
			$('#title-name-edit input').css('display', 'block').addClass('empty').val(T.UI.EMPTY_TITLE);
			$('span.title-placeholder').addClass('title-empty').text(T.UI.TITLE_PLACEHOLDER);
		} else {
			$('#title-name-edit input').css('display', 'block').removeClass('empty').val($('#title-name').text());
			$('span.title-placeholder').text($('#title-name').text());
		}
		$('#groups input').each(
			function (i) {
				$(this).parent().toggleClass('not-selected', !this.checked);
			}
		);
	
		if (
		!$('#avatar_fileupload input').get(0).files // No file list
		|| !window.XMLHttpRequest // No native XMLHttpRequest
		|| !((new XMLHttpRequest()).sendAsBinary) // No Gecko sendAsBinary function
		) {
			$('#avatar_fileupload').parent().addClass('disabled');
		} else {
			// Overwrite xhr.send; make $.ajax run xhr.sendAsBinary instead
			// Seems no harm to send text content as binary.
			XMLHttpRequest.prototype.send = function (data) {
				return this.sendAsBinary(data);
			};
		}
	
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
					gfx.page.blinkBar();
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
					gfx.page.blinkBar();
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
					gfx.page.blinkBar();
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
					gfx.page.blinkBar();
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
		},
		'url' : function (url) {
			url = $.trim(url);
			if (
				($.inArray(url.substr(0,url.indexOf(':')).toLowerCase(), ['http', 'https', 'telnet', 'irc', 'ftp', 'nntp']) === -1) &&
				url !== ''
			) {
				gfx.alert('EDITOR_URL_BAD');
				return false;
			}
			return true;
		}
	},
	//Upload file using file API, "files" = File List obj.
	'uploadFile' : function (files) {
		if (files.length !== 1) {
			//User did not drop exactly one file but multipole files, or text, links.
			//(No alert)
			return false;
		}
		var info = { //HTML 5 File API || Gecko 1.9 File Obj
			'type' : files[0].type || '',
			'size' : files[0].size || files[0].fileSize,
			'name' : files[0].name || files[0].fileName
		};
		if (info.type && !info.type.match(/^image\/(gif|png|jpeg)$/)) {
		//User did not drop supported file
			gfx.alert(T.FILEUPLOAD.INVALID_FILETYPE, 'FILEUPLOAD_INVALID_FILETYPE');
			return false;
		}
		//If MIME is empty stream (unknown type)
		if (!info.type) info.type = 'application/octet-stream';

		if (info.size > (1 << 20)) { //1MB
			gfx.alert(T.FILEUPLOAD.FILE_EXCEEDS_SIZE_LIMIT, 'FILEUPLOAD_FILE_EXCEEDS_SIZE_LIMIT');
			return false;
		}

		//Workaround for fixme below; replace non-ASCII chars in filenames
		info.name = info.name.replace(/[^\x20-\x7E]/g, '_');

		var bd = 'gfx-xhrupload-' + parseInt(Math.random()*(2 << 16));
		var xhrupload = function (bin) {
			//This is an overwritten $.ajax that supports 'binary' option.
			gfx.xhr = $.ajax(
				{
					url: './editor/upload',
					contentType: 'multipart/form-data, boundary=' + bd,
					processData: false,
					timeout: 1200000, //2 min
					data: '--' + bd + '\n' // RFC 1867 Format, simulate form file upload
					+ 'content-disposition: form-data; name="Filedata";'
					+ ' filename="' + info.name + '"\n' // fixme: RFC 1522 encoding for non-US ASCII filenames
					+ 'Content-Type: ' + info.type + '\n\n'
					+ bin + '\n\n'
					+ '--' + bd + '--',
					binary: true,
					success: function (result, status) {
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
		};
		if (window.FileReader) {
			// HTML5 File API (Firefox 3.6)
			var reader = new FileReader();
			reader.onloadend = function (ev) {
				xhrupload(ev.target.result);
			};
			reader.onerror = function (ev) {
				if (ev.target.error) {
					switch (ev.target.error) {
						case 8:
						gfx.alert(T.FILEUPLOAD.NOT_FOUND, 'FILEUPLOAD_NOT_FOUND');
						break;
						case 24:
						gfx.alert(T.FILEUPLOAD.NOT_READABLE, 'FILEUPLOAD_NOT_READABLE');
						break;
						case 18:
						gfx.alert(T.FILEUPLOAD.SECURITY, 'FILEUPLOAD_SECURITY');
						break;
						case 20: //User Abort
						break;
					}
				}
			}
			reader.readAsBinaryString(files[0]);
		} else {
			// Non-standard File Object (Firefox 3.0/3.5)
			try {
				xhrupload(files[0].getAsBinary());
			} catch (e) {
				//Have no idea what's wrong, but give user a feedback anyway.
				gfx.alert(T.FILEUPLOAD.NOT_READABLE, 'FILEUPLOAD_NOT_READABLE');
			}	
		}
	},
	'changeAvatar' : function (avatar, url) {
		gfx.page.avatar = avatar;
		gfx.page.blinkBar();
		$('#title-avatar img:only-child')
		.hide()
		.one(
			'load',
			function () {
				$(this).fadeIn(
					100,
					function () {
						$(this).css('display', null);
					}
				);
			}
		).attr('src', url);
		//$('#title-avatar').removeClass('dropindation');
		$('#avatar_fileupload input').val('');
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
		//check for errors round 1
		if (!gfx.page.validate.title(d.title)) {
			return;
		}
		if ($('.feature.box').length) {
			gfx.alert('EDITOR_FEATURE_COUNT');
			return;
		}
		var g = $('.group-title input:checked');
		if (!g.length) {
			gfx.alert('EDITOR_GROUP_EMPTY');
			return;
		}
		var flag = false;
		g.parent().next('.group-addons').each(
			function () {
				if (!$(this).children().length) {
					gfx.alert('EDITOR_ADDON_EMPTY');
					flag = true;
					return false;
				}
			}
		);
		if (flag) {
			return;
		}
		/* almost done ... */
		if (d.name === '') {
			gfx.openDialog('almostdone');
			return;
		}
		//check for errors round 2
		if (!gfx.page.validate.name(d.name)) {
			return;
		}
		//continue gathering data
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
					gfx.page.blinkBar();

					$('.userpage-url').attr(
						'href',
						document.location.protocol
						+ '//'
						+ document.location.hostname
						+ '/'
						+ result.name
					);
					$('.shareblock a').each(
						function () {
							$this = $(this);
							if (!$this.data('href')) {
								$this.data('href', $this.attr('href'));
							}
							$this.attr('href', $this.data('href').replace(/PLACEHOLDER/g, result.name));
						}
					);
					$('.name-placeholder').text(result.name);
					gfx.closeDialog('almostdone');
					gfx.openDialog('editcomplete');
				}
			}
		);
	},
	'blinkBar' : function () {
		gfx.page.blinkTimer && clearTimeout(gfx.page.blinkTimer);
		gfx.page.blinkTimer = setTimeout(
			function () {
				$('#editor-save')
				.animate(
					{
						opacity: 0.3
					},
					200
				).animate(
					{
						opacity: 1
					},
					200,
					function () {
						if (this.style.removeAttribute) {
							/* IE text filter fix */
							this.style.removeAttribute('filter');
						}
					}
				);
			},
			2000
		);
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

