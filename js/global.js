var gfx = {
	'windowSize' : {
		'login' : [40, 30]
	},
	'onload' : function () {
		if ($('link_login')) {
			$('link_login').addEvent(
				'click',
				function () {
					gfx.openWindow('login');
					return false;
				}
			);
		}
		if ($('link_logout')) {
			$('link_logout').addEvent(
				'click',
				function () {
					$('window_logout').getFirst().submit();
					return false;
				}
			);
		}
		if (gfx.editor) gfx.editor.onload();
		$$('.window .close a').addEvent(
			'click',
			function () {
				gfx.closeWindow(this.parentNode.parentNode.id.substr(7));
				return false;
			}
		);
	},
	'openWindow' : function (id) {
		$('window_' + id).setStyle('display', 'block');
		if (gfx.windowSize[id]) {
			var d = gfx.windowSize[id];
			$('window_' + id).setStyles({
				'width' : d[0] + '%',
				'height' : d[1] + '%',
				'top' : (50 - d[1]/2) + '%',
				'left' : (50 - d[0]/2) + '%'
			});
		}
	},
	'closeWindow' : function (id) {
		$('window_' + id).setStyle('display');
	}
}
window.onload = gfx.onload;