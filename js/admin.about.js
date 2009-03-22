gfx.admin = {
	'dialog' : {
		'admin' : {
			'width' : 800,
			'height' : 450,
			'buttons' : {},
			'position' : ['center', 50],
			'open' : function () {
				$('.content object').css('visibility', 'hidden');
			},
			'close' : function () {
				$('.content object').css('visibility', null);
			}
		}
	},
	'onload' : function () {
		this.dialog.admin.buttons[T.BUTTONS.ADMIN_OK] = function () {
			gfx.xhr = $.ajax(
				{
					url: '/about/update',
					data: {
						'token' : $('#token').val(),
						'id' : $('#admin_id').val(),
						'title' : $('#admin_title').val(),
						'name' : $('#admin_name').val(),
						'content' : $('#admin_content').val()
					},
					success: function (result, status) {
						if (gfx.ajaxError(result)) {
							return;
						}
						gfx.message(
							result.message.type,
							result.message.icon,
							result.message.msg
						);
						gfx.closeDialog('admin');
					}
				}
			);
		};
		this.dialog.admin.buttons[T.BUTTONS.ADMIN_DELETEABOUT] = function () {
			if (!window.confirm(T.UI.ADMIN_DELETEABOUT_CONFIRM)) {
				return;
			}
			$('#admin_post').submit();
		}
	}
};