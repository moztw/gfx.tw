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
			},
			'#groups-show-detail-box' : function () {
				$('#groups').toggleClass('detailed');
			}
		},
		/* Don't remove them coz editor.js will extend them */
		'blur' : {},
		'mousedown' : {},
		'mouseup' : {}
	},
	'windowSize' : {
		'login' : [40, 30]
	},
	'onload' : function () {

		if (gfx.editor) gfx.editor.onload();

		//cornerizing
		$('.downloadframe').corner("round 8px").parent().css('padding', '2px').corner("round 9px");
		$('#titleblock').corner("top");

		//gradienting
		$('#titleblock').addClass("gradient B4D5E6 FFFFFF vertical");

		//dialoging
		/*$('.download').dialog(
			{  
				bgiframe: true,
				dialogClass: 'download',
			}
		); */                                                                                          

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
	},
}
$(document).ready(gfx.onload);