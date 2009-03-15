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
		'dialog' : {}
	};
	/* Run each "parts": copy their event binding and dialog options and loads them */
	var m = ['global', 'page', 'admin'];
	$.each(
		m,
		function () {
			if (gfx[this]) {
				if (gfx[this].bind) {
					$.each(
						gfx[this].bind,
						function(e, o) {
							$.extend(
								setting.bind[e] || (setting.bind[e] = {}),
								o
							);
						}
					);
					delete gfx[this].bind;
				}
				if (gfx[this].dialog) {
					$.extend(
						setting.dialog,
						gfx[this].dialog
					);
					delete gfx[this].dialog;
				}
				if (gfx[this].onload) {
					gfx[this].onload.apply(setting);
					delete gfx[this].onload;
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
		setting.bind,
		function (e, o) {
			$.each(
				o,
				function (s, f) {
					$(s).bind(e, f);
				}
			);
		}
	);
	
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
				'div.message div p a.ui-icon' : function () {
					$(this).parents('.message').slideUp(500);
					return false;
				},
				'div.announcement div p a.ui-icon' : function () {
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
				'#link_login' : function () {
					gfx.openDialog('login');
					$('#openid-identifier').focus();
					return false;
				},
				'#link_logout' : function () {
					$('#logout_form').submit();
					return false;
				},
				'#groups-show-detail-box' : function () {
					if (this.checked) {
						$('#groups').addClass('detailed');
					} else {
						$('#groups').removeClass('detailed');
					}
				},
				'a.newwindow' : function () {
					window.open(this.href);
					return false;
				},
				'.download a' : function () {
					gfx.openDialog('download');
					var os = (/(win|mac|linux)/.exec(window.navigator.platform.toLowerCase()) || [null])[0];
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
							window.location.href = $('#iframe-feature iframe').attr('src');
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
				},
				'#groups-install button' : function () {
					if (window.InstallTrigger === undefined) {
						gfx.alert('EXTINSTALL_NOT_FX');
						return;
					}
					var o = $('#groups .install input:checked').not('[disabled]');
					if (!o.length) {
						window.alert('EXTINSTALL_CHECKED_NOTHING');
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
				}
			},
			'mouseout' : {
				'#link_manage a' : function () {
					$(this).parent().removeClass('ui-state-hover');
				}
			}			
		},
		'dialog' : {
			'login' : {
				'width' : 500,
				'height' : 400,
				'position' : ['center', 120]
			},
			'download' : {
				'width' : 500,
				'height' : 320,
				'position' : ['center', 130],
				'buttons' : { }
			},
			'extinstall' : {
				'width' : 300,
				'height' : 250,
				'position' : ['right', 'top'],
				'buttons' : { }
			},
			'progress' : {
				'width' : 244,
				'height' : 40,
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

			if ($('#groups-show-detail-box:checked').length) {
				$('#groups').addClass('detailed');
			} else {
				$('#groups').removeClass('detailed');
			}

			var showMessage = function (o) {
				o.slideDown(
					500, 
					function () {
						showMessage($(this).next('.message'));
					}
				);
			}
			
			showMessage($('.message:first'));

			if (gfx.admin) {
				$('#link_manage').show();
			}
		}
	},
	'openDialog' : function (id) {
		$('#window_' + id).dialog("open");
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
			.addClass('ui-state-' + type + ' ui-corner-all')
			.append(
				$(document.createElement('p'))
				.append(
					$(document.createElement('a'))
					.addClass('ui-icon ui-icon-circle-close')
					.attr('href', '#')
					.bind(
						'click',
						function () {
							$(this).parents('.message').slideUp(500);
							return false;
						}
					)
				)/*.append(
					$(document.createElement('span'))
					.addClass('ui-icon ui-icon-' + icon)
				)*/.append(msg)
			)
		);
		$('#header').after(o);
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
	}
};

