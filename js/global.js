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
				$('#groups').toggleClass('detailed');
			},
			'a.newwindow' : function () {
				window.open(this.href);
				return false;
			}
		},
		'change' : {
			'#openid_sp' : function () {
				$('#openid-identifier').val(this.value);
			}
		},
		/* Don't remove them coz editor.js will extend them */
		'focus' : {},
		'blur' : {},
		'keyup' : {},
		'mousedown' : {},
		'mouseup' : {}
	},
	'windowOption' : {
		'login' : {
			'width' : 500,
			'height' : 400,
			'position' : ['center', 120]
		}
	},
	'onload' : function () {

		if (gfx.editor) gfx.editor.onload();                                      

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
	},
}
$(document).ready(gfx.onload);