/*global window, document, $, T */

var gfx = {
	'bind' : {
		'click' : {
			'div.message div p a.ui-icon-close' : function () {
				$(this).parents('.message').slideUp(500);
				return false;
			},
			'#link_login' : function () {
				gfx.openWindow('login');
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
				gfx.openWindow('download');
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
				var resizeIfr = function (ifr, el) {
					ifr.css(
						{
							width: el.width() + 'px',
							height: el.height() + 'px'
						}
					);
				};
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
				).dialog(
					{
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
							resizeIfr(
								$(ui.element).find('.ui-dialog-content iframe'),
								$(ui.element).find('.ui-dialog-content')
							);
						},
						'open' : function (e) {
							resizeIfr(
								$(e.target).find('iframe'),
								$(e.target)
							);
						},
						'close' : function (e, ui) {
							$(e.target).dialog('destroy').remove();
						}
					}
				);
				
				return false;
			},
			'#groups-install button' : function () {
				if (window.InstallTrigger === undefined) {
					window.alert(T.UI.EXTINSTALL_NOT_FX);
					return;
				}
				var o = $('#groups .install input:checked').not('[disabled]');
				if (!o.length) {
					window.alert(T.UI.EXTINSTALL_CHECKED_NOTHING);
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
				gfx.openWindow('extinstall');
				window.InstallTrigger.install(l);
			}
		},
		'change' : {
			'#openid_sp' : function () {
				$('#openid-identifier').val(this.value);
			}
		}
	},
	'windowOption' : {
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
		}
	},
	'onload' : function () {
		if (gfx.editor) {
			gfx.editor.onload();
		}
		if (gfx.admin) {
			gfx.admin.onload();
		}
		if ($('#groups-show-detail-box:checked').length) {
			$('#groups').addClass('detailed');
		} else {
			$('#groups').removeClass('detailed');
		}

		gfx.windowOption.download.buttons[T.BUTTONS.DOWNLOAD_OK] = function () {
			gfx.closeWindow('download');
		};
		gfx.windowOption.extinstall.buttons[T.BUTTONS.EXTINSTALL_OK] = function () {
			gfx.closeWindow('extinstall');
		};
		
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

				if (gfx.windowOption[this.id.substr(7)]) {
					$.extend(
						option,
						gfx.windowOption[this.id.substr(7)]
					);
				}
				$(this).dialog(option);
			}
		);

		$.each(
			gfx.bind,
			function (e, o) {
				$.each(
					o,
					function (s, f) {
						$(s).bind(e, f);
					}
				);
			}
		);
	},
	'openWindow' : function (id) {
		$('#window_' + id).dialog("open");
	},
	'closeWindow' : function (id) {
		$('#window_' + id).dialog("close");
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
					.addClass('ui-icon ui-icon-close')
					.attr('href', '#')
					.bind(
						'click',
						function () {
							$(this).parents('.message').slideUp(500);
							return false;
						}
					)
				).append(
					$(document.createElement('span'))
					.addClass('ui-icon ui-icon-' + icon)
				).append(msg)
			)
		);
		$('#header').after(o);
		o.slideDown(500);
	}
};
$(document).ready(gfx.onload);
