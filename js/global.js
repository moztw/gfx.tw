var gfx = {
	'bind' : {
		'click' : {
			'div.message div p a.ui-icon-close' : function () {
				$(this).parents('.message').remove();
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
					$('#groups-install').addClass('show');
				} else {
					$('#groups').removeClass('detailed');
					$('#groups-install').removeClass('show');
				}
			},
			'a.newwindow' : function () {
				window.open(this.href);
				return false;
			},
			'.download a' : function () {
				gfx.openWindow('download');
				var os = (/(win|mac|linux)/.exec(navigator.platform.toLowerCase()) || [null])[0];
				var name = (/^\/([^\/\.\?]*)\??.*$/.exec(window.location.pathname) || [null, null])[1];
				if (name === '') name = '(default)';
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
					alert(T.UI.EXTINSTALL_NOT_FX);
					return;
				}
				var o = $('#groups .install input:checked').not('[disabled]');
				if (!o.length) {
					alert(T.UI.EXTINSTALL_CHECKED_NOTHING);
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
				InstallTrigger.install(l);
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
		if (gfx.editor) gfx.editor.onload();
		if ($('#groups-show-detail-box:checked').length) {
			$('#groups').addClass('detailed');
			$('#groups-install').addClass('show');
		}

		gfx.windowOption.download.buttons[T.BUTTONS.DOWNLOAD_OK] = function () {
			gfx.closeWindow('download');
		}
		gfx.windowOption.extinstall.buttons[T.BUTTONS.EXTINSTALL_OK] = function () {
			gfx.closeWindow('extinstall');
		}
		
		$('.window').each(
			function () {
				var option = {
					autoOpen: false,
					modal: true,
					overlay: {
						backgroundColor: '#000000',
						opacity: 0.5
					},
					position: ['center', 100]
				};

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
	}
}
$(document).ready(gfx.onload);
