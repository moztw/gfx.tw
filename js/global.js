/*

JavaScript code orgenisation:

* everything falls into the object "gfx".
* there are three different "parts" to included, depend on what page the user is on and what right does s/he has,
	and of course, the given file exists:
	gfx.global, gfx.page, and gfx.admin
* jQuery doc ready function loads will do following stuff with each part:
	* copy the settings (bind and dialog) and delete them.
	* run onload script (with "this" refer to "settings") and delete it.
	* bind all events and reate all dialogs

*/
/*global window, document, $, T */

$(function () {
	/* Event binding list and dialog options list */
	var setting = {
		'bind' : {},
		'live' : {},
		'dialog' : {}
	};
	/* Run each "parts": copy their event binding and dialog options and loads them */
	$.each(
		['global', 'page', 'admin'],
		function (i, p) {
			if (gfx[p]) {
				$.each(
					['bind', 'live', 'one'],
					function (i, t) {
						if (gfx[p][t]) {
							$.each(
								gfx[p][t],
								function(e, o) {
									$.extend(
										setting[t][e] || (setting[t][e] = {}),
										o
									);
								}
							);
							delete gfx[p][t];
						}
					}
				);
				if (gfx[p].dialog) {
					$.extend(
						setting.dialog,
						gfx[p].dialog
					);
					delete gfx[p].dialog;
				}
				if (gfx[p].onload) {
					gfx[p].onload.apply(setting);
					delete gfx[p].onload;
				}
			}
		}
	);

	/* $.dialog all dialogs */
	$('.window').each(
		function () {
			var option = {
				autoOpen: false,
				position: ['center', 100]
			};
			if ($.browser.msie && parseInt($.browser.version) <= 6) {
				/* Is IE6, some fix for jQuery UI dialog
				Not prefect but works (sigh...) */
				option.open = function (e) {
					$(e.target).dialog('option', 'height', option.height + 20);
					if ($(e.target).parent().find('.ui-dialog-buttonpane').length) {
						$(e.target).height(
							$(e.target).parent().innerHeight()
							- $(e.target).parent().find('.ui-dialog-titlebar').outerHeight({'margin':true})
							- $(e.target).parent().find('.ui-dialog-buttonpane').outerHeight({'margin':true})
						);
					} else {
						$(e.target).height(
							$(e.target).parent().innerHeight()
							- $(e.target).parent().find('.ui-dialog-titlebar').outerHeight({'margin':true})
						);
					}
				};
			} else {
				/* Not IE6 */
				option.modal = true;
				option.overlay = {
					backgroundColor: '#000000',
					opacity: 0.5
				};
			}

			if (setting.dialog[this.id.substr(7)]) {
				$.extend(
					option,
					setting.dialog[this.id.substr(7)]
				);
			}
			$(this).dialog(option);
		}
	);

	/* bind all events */
	$.each(
		['bind', 'live', 'one'],
		function (i, t) {
			if (setting[t]) {
				$.each(
					setting[t],
					function (e, o) {
						$.each(
							o,
							function (s, f) {
								$(s)[t](e, f);
							}
						);
					}
				);
			}
		}
	);
	
	var h = window.location.hash.substr(1);
	if (h && $('#window_' + h).length) {
		gfx.openDialog(h);
	}
	
	/* unset setting */
	setting = null;

	/* show the first tab or every tab set */
	$('.tabs li:first').click();
	
	/* ajax setup */
	$.ajaxSetup(
		{
			type: 'POST',
			timeout: 20000,
			dataType: 'json',
			beforeSend : function (xhr) {
				if (gfx.xhr) {
					gfx.xhr.abort();
				}
				xhr.running = true;
				window.setTimeout(
					function () {
						/* for some reason check gfx.xhr.readyState won't work */
						if (gfx.xhr.running) {
							gfx.openDialog('progress');
						}
					},
					400
				);
			},
			complete : function (xhr, status) {
				xhr.running = false;
				gfx.closeDialog('progress');
			},
			error: function (xhr, status, error) {
				xhr.running = false;
				gfx.closeDialog('progress');
				switch (status) {
					case 'timeout':
					gfx.alert(T.AJAX_ERROR.TIMEOUT, 'AJAX_ERROR_TIMEOUT');
					break;
					case 'parsererror':
					gfx.alert(T.AJAX_ERROR.PARSE_RESPONSE, 'AJAX_ERROR_PARSE_RESPONSE');
					break;
					case 'error':
					default:
					if (xhr.status === 0) {
						gfx.alert(T.AJAX_ERROR.UNABLE_TO_CONNECT, 'AJAX_ERROR_UNABLE_TO_CONNECT');
					} else {
						gfx.alert(T.AJAX_ERROR.SERVER_RESPONSE, 'AJAX_ERROR_SERVER_RESPONSE');
					}
				}
			}
		}
	);
});

var gfx = {
	'global' : {
		'bind' : {
			'click' : {
				'#link_login, #newcomer-intro-login' : function () {
					if ($(this).not('.ui-state-disabled').length !== 0) {
						gfx.openDialog('login');
						$('#openid-identifier').focus();
					}
					return false;
				},
				'#link_logout' : function () {
					$('#logout_form').submit();
					return false;
				},
				'#link-intro' : function () {
					/* show/hide instead of slide because strange margin issue */
					var show = ($('#intro-block:visible').length === 0);
					$('#intro-block').toggleClass('show', show);
					$('#header').toggleClass('no-margin', show);
					$(this).parent().toggleClass('active', show);
					if (show) gfx.randomAvatar(true);
					return false;
				},
				'#link-newcomer-intro' : function () {
					$(this).parent().toggleClass('active', ($('#newcomer-intro:visible').length === 0));
					if ($('#newcomer-intro:visible').length === 0) {
						$('#newcomer-intro').slideDown(500);
						gfx.randomAvatar(true);
					} else {
						$('#newcomer-intro').slideUp(500);
					}
					return false;
				},
				'#groups-show-detail-box' : function () {
					if (this.checked) {
						$('#groups').addClass('detailed');
					} else {
						$('#groups').removeClass('detailed');
					}
				},
				'.download a' : function () {
					gfx.openDialog('download');
					var os = gfx.getOS();
					var name = (/^\/([^\/\.\?]*)\??.*$/.exec(window.location.pathname) || [null, null])[1];
					if (name === '') {
						name = '(default)';
					}
					var dl = '/download';
					if (os && name) {
						dl += '?name=' + name + '&os=' + os;
					}
					//Call directly as IE will show information bar if we don't.
					/*
						setTimeout(function () {window.location.href = dl; }, 100);
					*/
					window.location.href = dl;

					return false;
				},
				'#groups-install button' : function () {
					if (window.InstallTrigger === undefined) {
						gfx.alert('EXTINSTALL_NOT_FX');
						return;
					}
					var o = $('#groups .install input:checked:not([disabled])');
					if (!o.length) {
						gfx.alert('EXTINSTALL_CHECKED_NOTHING');
						return;
					}
					var l = {};
					o.each(
						function (i) {
							var a = $(this).parents('.addon');
							l[a.find('a span').attr('title')] = {
								URL : this.value,
								IconURL: a.find('a img').attr('src')
							};
						}
					);
					gfx.openDialog('extinstall');
					try {
						pageTracker._trackEvent("Addons", "install");
					} catch (e) {}
					window.InstallTrigger.install(l);
				},
				'#link_manage a' : function () {
					gfx.openDialog('admin');
					return false;
				},
				'.tabs li' : function () {
					$(this).addClass('ui-state-hover')
					.siblings('li').removeClass('ui-state-hover')
					.parent()
					.siblings('.tab-content').eq($(this).prevAll().length).show()
					.siblings('.tab-content').hide();
				},
				'#push-plurk' : function () {
					window.open(
						'http://plurk.com/?status='
						+ encodeURIComponent(T.UI.PUSH.replace('NAME', $('#title-name').text()) + this.href)
					);
					return false;
				},
				'#push-twitter' : function () {
					window.open(
						'http://twitter.com/home/?status='
						+ encodeURIComponent(T.UI.PUSH.replace('NAME', $('#title-name').text()) + this.href)
					);
					return false;
				},
				'#push-plurk-mine' : function () {
					window.open(
						'http://plurk.com/?status='
						+ encodeURIComponent(T.UI.PUSH_MINE.replace('NAME', $('#title-name').text()) + this.href)
					);
					return false;
				},
				'#push-twitter-mine' : function () {
					window.open(
						'http://twitter.com/home/?status='
						+ encodeURIComponent(T.UI.PUSH_MINE.replace('NAME', $('#title-name').text()) + this.href)
					);
					return false;
                                }
			},
			'change' : {
				'#openid_sp' : function () {
					$('#openid-identifier').val(this.value);
				}
			},
			'mouseover' : {
				'#link_manage a' : function () {
					$(this).parent().addClass('ui-state-hover');
				},
				'#header li, #newcomer-intro-login' : function () {
					$(this).not('.ui-state-disabled').addClass('ui-state-hover');
				}
			},
			'mouseout' : {
				'#link_manage a' : function () {
					$(this).parent().removeClass('ui-state-hover');
				},
				'#header li, #newcomer-intro-login' : function () {
					$(this).not('.active').removeClass('ui-state-hover');
				}
			},
			'focus' : {
				'.challenge-answer' : function () {
					if ($('.challenge-question').text()) return;
					$('.challenge-question').text('...')
					$.ajax(
						{
							url: '/auth/challenge',
							data: {},
							beforeSend : function (xhr) { },
							complete : function (xhr, status) { },
							error: function (xhr, status, error) { },
							success: function (result, status) {
								if (result.challenge && result.question)
								$('.challenge-token').val(result.challenge);
								$('.challenge-question').text(result.question);
							}
						}
					);
				}
			}
		},
		'live' : {
			'click' : {
				'div.message div p a.ui-icon' : function () {
					$(this).parents('.message').slideUp(500);
					$('#link-' + $(this).parents('.message').attr('id')).parent().removeClass('active ui-state-hover');
					return false;
				},
				'div.announcement div p a.ui-icon:first' : function () {
					$.ajax(
						{
							url: '/auth/skip_announcement',
							data: {},
							beforeSend : function (xhr) { },
							complete : function (xhr, status) { },
							error: function (xhr, status, error) { },
							success: function (result, status) {
								gfx.ajaxError(result);
							}
						}
					);
				},
				'#link-mozilla' : function () {
					try {
						pageTracker._trackEvent("External", "mozilla");
					} catch (e) {
					}
				},
				'#link-moztw' : function () {
					try {
						pageTracker._trackEvent("External", "moztw");
					} catch (e) {
					}
				},
				'#myid' : function () {
					try {
						pageTracker._trackEvent("External", "MyID.tw");
					} catch (e) {
					}
				},
				'a.newwindow' : function () {
					window.open(this.href);
					return false;
				},
				'#features p.link a' : function () {
					var option = {
						title: $(this).parents('.feature').find('h2').text(),
						modal: true,
						overlay: {
							backgroundColor: '#000000',
							opacity: 0.5
						},
						'width' : 800,
						'height' : 500,
						'show' : null, /* demenstion detection will fail */
						'position' : ['center', 50],
						'resizeStart' : function (e, ui) {
							$(ui.element).find('.ui-dialog-content div').show();
						},
						'resizeStop' : function (e, ui) {
							$(ui.element).find('.ui-dialog-content div').hide();
						},
						'resize' : function (e, ui) {
							gfx.fillContainer(
								$(ui.element).find('.ui-dialog-content iframe'),
								$(ui.element).find('.ui-dialog-content')
							);
						},
						'open' : function (e) {
							gfx.fillContainer(
								$(e.target).find('iframe'),
								$(e.target)
							);
						},
						'close' : function (e, ui) {
							$(e.target).dialog('destroy').remove();
						}
					};
					if ($('#link_manage').length) {
						option.buttons = {};
						option.buttons[T.BUTTONS.ADMIN_EDIT_FEATURE] = function () {
							window.location.href = $('#iframe-feature iframe').attr('src') + '#admin';
						};
					}
					$(document.createElement('div'))
					.addClass('window').attr(
						{
							'id' : 'iframe-feature'
						}
					).append(
						$(document.createElement('iframe')).attr(
							{
								src : this.href + '/inframe',
								frameBorder: '0' /* IE7 */
							}
						).css(
							{
								border: 'none'
							}
						)
					).append(
						/* A div covers iframe so that mouseover can be detected when resizing
						Won't work in IE7 coz its transparent */
						$(document.createElement('div')).css(
							{
								width: '100%',
								height: '100%',
								position: 'absolute',
								top: 0,
								left: 0,
								display: 'none'
							}
						)
					).dialog(option);
					
					return false;
				}
			}
		},
		'dialog' : {
			'login' : {
				'width' : 500,
				'height' : 460,
				'position' : ['center', 100]
			},
			'download' : {
				'width' : 500,
				'height' : 360,
				'position' : ['center', 130],
				'buttons' : { }
			},
			'extinstall' : {
				'width' : 300,
				'height' : 290,
				'position' : ['right', 'top'],
				'buttons' : { }
			},
			'progress' : {
				'width' : 244,
				'height' : 75,
				'buttons' : {},
				'position' : [100, 100]
			}
		},
		'onload' : function () {
			/* Buttons in dialog */
			this.dialog.download.buttons[T.BUTTONS.DOWNLOAD_OK] = function () {
				gfx.closeDialog('download');
			};
			this.dialog.extinstall.buttons[T.BUTTONS.EXTINSTALL_OK] = function () {
				gfx.closeDialog('extinstall');
			};
			this.dialog.progress.buttons[T.BUTTONS.PROGRESS_FORCESTOP] = function () {
				if (gfx.xhr) {
					gfx.xhr.abort();
				}
				gfx.closeDialog('progress');
			};

			/* Fill random avatars */
			gfx.randomAvatar();
			
			/* User page show addon details */
			if ($('#groups-show-detail-box:checked').length) {
				$('#groups').addClass('detailed');
			} else {
				$('#groups').removeClass('detailed');
			}
			
			/* User page detect user OS and disable unsupported addons */
			var os = 'Z';
			switch (gfx.getOS()) {
				case 'win':
					os = '5';
					break;
				case 'mac':
					os = '3';
					break;
				case 'linux':
					os = '2';
					break;
			}
			$('.addon .install').each(
				function () {
					var o = $(this);
					if (os !== 'Z') {
						if (!o.hasClass('os_0') && !o.hasClass('os_' + os)) {
							o.addClass('disabled');
							o.find('label').text(T.UI.ADDON_OS_NO_MATCH);
							o.find('input').attr('disabled', 'disabled');
						} else if (o.hasClass('amo-addon') && !o.hasClass('os_0')) {
							o.find('input').get(0).value += '/platform:' + os;
						}
					} else {
						//could be os_1 or os_4 (BSD or Solaris)
						if (!o.hasClass('os_0') && !o.hasClass('os_1') && !o.hasClass('os_4')) {
							o.addClass('disabled');
							o.find('label').text(T.UI.ADDON_OS_NO_MATCH);
							o.find('input').attr('disabled', 'disabled');
						} else if (o.hasClass('amo-addon') && !o.hasClass('os_0')) {
							if (o.hasClass('os_1') && !o.hasClass('os_4')) {
								o.find('input').get(0).value += '/platform:1';
							} else if (o.hasClass('os_4') && !o.hasClass('os_1')) {
								o.find('input').get(0).value += '/platform:4';
							} else {
								//choose either of them would break another.
								//just disable installation and ask them go to amo...
								
								//chances are its extremely unlikely to come to here
								//people use non-win|osx|linux Firefox
								//(which by the way is not even Mozilla-supported)
								//looking for an addon that supports both BSD and Solaris
								//but not all platforms...
								
								o.addClass('disabled');
								o.find('input').attr('disabled', 'disabled');
							}
						}
					}
				}
			);
			

			/* Show intro block if this is top block */
			/*if (window.location.pathname === '/') {
				this.bind.click['#link-intro'].apply($('#link-intro'));
			}*/

			/* visually enable all javascript buttons */
			$('.ui-state-disabled').removeClass('ui-state-disabled');
			
			/* Show intro text */
			if (!$.browser.mozilla) {
				$('#visitor-intro').text(T.UI.INTRO_TEXT_NON_FX_USER);
			} else {
				$('#visitor-intro').text(T.UI.INTRO_TEXT_FX_USER);
			}
			
			/* Turn off some autocomplete */
			$('.challenge-answer').attr('autocomplete','off');
			
			/* Show messages */
			var showMessage = function (o) {
				o.slideDown(
					500, 
					function () {
						showMessage($(this).next('.message:not(.show):not(.no-auto)'));
					}
				);
			};
			
			showMessage($('.message:not(.show):not(.no-auto)'));

			if (gfx.admin) {
				$('#link_manage').show();
			}
		}
	},
	'randomAvatar' : function (reload) {
		var Avatar = function (d) {
			return $(document.createElement('p')).append(
				$(document.createElement('a')).attr(
					'href', '/' + d.name
				).append(
					$(document.createElement('img')).one(
						'load',
						function () { /* won't work on live event so leave it here */
							$(this).fadeIn(200).next().fadeIn(200, function () {
								if (this.style.removeAttribute) {
									/* IE text filter fix */
									this.style.removeAttribute('filter');
								}
							});
						}
					)
				).append(
					$(document.createElement('span')).text(d.title)
				)
			);
		};
		$('.random-avatars:visible').each(
			function (i, o) {
				if ($(o).children().length !== 0 && !reload) {
					return;
				}
				/* .children().hide() is a bit faster than .empty(); we need the buttons to response fast */
				$(o).addClass('random-avatars-loading').children().hide();
				/* Remember we might not yet $.ajaxSetup so do fill some common variables */
				$.ajax(
					{
						url: (reload)?'/user/list/random-avatars-reload':'/user/list/random-avatars',
						data: {},
						type: 'GET',
						timeout: 20000,
						dataType: 'json',
						beforeSend : function (xhr) { },
						complete : function (xhr, status) { },
						error: function (xhr, status, error) { },
						success: function (result, status) {
							if (!result.users) {
								return;
							}
							$(o).empty().removeClass('random-avatars-loading');
							$.each(
								result.users,
								function () {
									$(o).append(
										new Avatar(this)
									);
									/* set src after append, make sure onload fires on IE */
									$(o).find('p:last img').attr('src', this.avatar);
								}
							)
						}
					}
				);
			}
		);
	},
	'openDialog' : function (id) {
		//beforeopen is an option we made up so we check it ourselves.
		if ($('#window_' + id).dialog('option', 'beforeopen').prototype) {
			if ($('#window_' + id).dialog('option', 'beforeopen')()) {
				$('#window_' + id).dialog('open');
			}
		} else {
			$('#window_' + id).dialog('open');
		}
	},
	'closeDialog' : function (id) {
		$('#window_' + id).dialog("close");
	},
	'fillContainer' : function (element, container, option) {
		if (option !== 'noheight') {
			var h = container.height();
			element.filter(':visible').siblings().each(
				function () {
					h -= this.offsetHeight;
				}
			);
			element.height(h);
		}
		if (option !== 'nowidth') {
			var w = container.width();
			element.filter(':visible').siblings().each(
				function () {
					w -= this.offsetWidth;
				}
			);
			element.width(w);
		}
	},
	'message' : function (type, icon, msg) {
		var o = $(document.createElement('div'))
		.addClass('ui-widget message')
		.css('display', 'none')
		.append(
			$(document.createElement('div'))
			.addClass('ui-state-' + (type || 'highlight') + ' ui-corner-all')
			.append(
				$(document.createElement('p'))
				.append(
					$(document.createElement('a'))
					.addClass('ui-icon ui-icon-circle-close ui-corner-all')
					.attr('href', '#')
				)/*.append(
					$(document.createElement('span'))
					.addClass('ui-icon ui-icon-' + (icon || 'info'))
				)*/.append(msg)
			)
		);
		$('#header, #newcomer-intro, #intro-block').filter(':last').after(o);
		o.slideDown(500);
	},
	'alert' : function (msg, tag) {
		if (T.ALERT[msg]) {
			tag = msg;
			msg = T.ALERT[msg];
		}
		window.alert(msg);
		/* tag is reserved for record user actions */
	},
	'ajaxError' : function (result) {
		if (result.message && result.message.type === 'error') {
			gfx.alert(
				T.ALERT[result.message.tag] || result.message.msg,
				result.message.tag || false
			);
			return true;
		} else {
			return false;
		}
	},
	'getOS' : function () {
		return (/(win|mac|linux)/.exec(window.navigator.platform.toLowerCase()) || [null])[0];
	}
};

