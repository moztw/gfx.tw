var gfx = {
	'bind' : {
		'click' : {
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
			'buttons' : {
				'OK' : function () {
					gfx.closeWindow('download');
				}
			}
		}
	},
	'onload' : function () {
		if (gfx.editor) gfx.editor.onload();
		if ($('#groups-show-detail-box:checked').length) {
			$('#groups').addClass('detailed');
			$('#groups-install').addClass('show');
		}

		$('.window').each(
			function () {
				var option = {
					autoOpen: false,
					dialogClass: 'flora',
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
