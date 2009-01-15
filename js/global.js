var gfx = {
	'bind' : {
		'click' : {
			'#link_login' : function () {
				gfx.openWindow('login');
				return false;
			},
			'#link_logout' : function () {
				$('#logout_form').submit();
				return false;
			}
		},
		'blur' : {}
	},
	'windowSize' : {
		'login' : [40, 30]
	},
	'onload' : function () {
		if (gfx.editor) gfx.editor.onload();
		$('.window').dialog(
			{
				autoOpen: false,
				dialogClass: 'flora',
				modal: true,
				overlay: {
					backgroundColor: '#000',
					opacity: 0.5
				}
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