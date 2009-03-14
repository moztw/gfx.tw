gfx.admin = {
	'dialog' : {
		'admin' : {
			'width' : 800,
			'height' : 450,
			'buttons' : {},
			'position' : ['center', 50],
			'resize' : function (e, ui) {
				gfx.fillContainer(
					$(ui.element).find('.ui-dialog-content .tab-content'),
					$(ui.element).find('.ui-dialog-content'),
					'nowidth'
				);
			},
			'open' : function (e) {
				gfx.fillContainer(
					$(e.target).find('.tab-content'),
					$(e.target),
					'nowidth'
				);
			}
		}
	},
	'onload' : function () {
		$('#link_manage a')[0].lastChild.nodeValue = T.UI.ADMIN_SITE_MANAGE;
	}
};