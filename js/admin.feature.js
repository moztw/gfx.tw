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
					url: './update',
					data: {
						'token' : $('#token').val(),
						'id' : $('#admin_id').val(),
						'title' : $('#admin_title').val(),
						'name' : $('#admin_name').val(),
						'order' : $('#admin_order').val(),
						'description' : $('#admin_description').val(),
						'content' : $('#admin_content').val()
					},
					success: function (result, status) {
						if (gfx.ajaxError(result)) {
							return;
						}
						gfx.message(
							result.message.type,
							result.message.icon,
							result.message.msg,
							result.message.tag
						);
						gfx.closeDialog('admin');
					}
				}
			);
		};
		this.dialog.admin.buttons[T.BUTTONS.ADMIN_DELETEFEATURE] = function () {
			window.alert('TBD coz we don\'t know how to handle users who select this feature.');
			return;
			if (!window.confirm(T.UI.ADMIN_DELETEFEATURE_CONFIRM)) {
				return;
			}
			$('#admin_post').submit();
		}
	}
};
