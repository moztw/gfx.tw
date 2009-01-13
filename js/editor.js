gfx.editor = {
	'movingFeature' : null,
	'onload' : function () {
		$('title-name').addEvent(
			'click',
			function () {
				this.setStyle('display', 'none');
				$('title-name-edit')
					.getFirst()
					.setStyle('display', 'block')
					.focus();
				$('title-name-edit').getFirst().value = this.firstChild.nodeValue;
			}
		);
		$('title-name-edit').getFirst().addEvent(
			'blur',
			gfx.editor.changeTitle
		);
		$('featureselection_save').getFirst().addEvent(
			'click',
			gfx.editor.changeFeatureSelection
		);
		$('title-avatar').addEvent(
			'click',
			function () {
				gfx.openWindow('avatar');
			}
		);
		gfx.editor.swfupload = new SWFUpload(
			{
				'upload_url': location.href + '/upload',
				//'post_params' : { 'type' : 'upload' },

				// File Upload Settings
				'file_size_limit' : 1024,	// 1MB
				'file_types' : '*.jpg;*.jpeg;*.gif;*.png',
				'file_types_description' : 'Images',
				'file_upload_limit' : '0',
				// Button Settings
				//button_image_url : '',	// Relative to the this file
				button_placeholder_id : 'avatar_spfupload_replace',
				button_width: 60,
				button_height: 60,
				//button_text : '<span class="button">上傳影像…</span>',
				//button_text_style : '.button { font-family: 微軟正黑體, Verdana, sans-serif; font-size: 13; text-align: center}',
					/* Adobe CSS subset  */
				//button_text_top_padding: 2,
				//button_text_left_padding: 20,
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,
				// Flash Settings
				'flash_url' : './swfupload/swfupload.swf',	// Relative to this file
				// Debug Settings
				'debug' : false,
				// Event Handler Settings
				'file_dialog_complete_handler' : function (n, q) {
					if (n === 1 && q === 1) {
						this.setButtonDisabled(true);
						this.startUpload();
					}
				},
				'file_queue_error_handler' : function (file, error, msg) {
					switch (error) {
						case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
						window.alert(msg);
						break;
						case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
						window.alert(msg);
						break;
						case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
						window.alert(msg);
						break;
						default:
						window.alert(msg);
						break;
					}
				},
				'upload_error_handler' : function (file, error, msg) {
					if (error === SWFUpload.UPLOAD_ERROR.FILE_CANCELLED) {
					} else {
						window.alert(msg);
					}
					this.setButtonDisabled(false);
				},
				'upload_success_handler' : function (file, result) {
					this.setButtonDisabled(false);
					result = JSON.decode(result);
					if (!result) {
						window.alert('E');
					} else if (result.error) {
						window.alert(result.error);
					} else {
						gfx.editor.changeAvatar(result.img, './useravatars/' + result.img);
					}
				}
			}
		);
		$('avatar_glavatar').addEvent(
			'click',
			function () {
				gfx.editor.changeAvatar('(gravatar)', this.getFirst().src);
			}
		);
		$('avatar_default').addEvent(
			'click',
			function () {
				gfx.editor.changeAvatar('(default)', './images/keyhole.gif');
			}
		);
		gfx.editor.sortable = new Sortables(
			'features',
			{
				onComplete : function () {
					gfx.editor.featureChanged = true;
				}
			}
		);
		$('editor_save_button').addEvent(
			'click',
			function () {
				gfx.openWindow('savepage');
			}
		);
		$('save_page').addEvent(
			'click',
			gfx.editor.savePage
		);
	},
	'changeFeatureSelection' : function () {
		var s = [];
		gfx.editor.featureChanged = true;
		$$('#featureselection input').each(
			function (o) {
				if (o.checked) {
					s[s.length] = o;
				}
			}
		);
		if (s.length > 3) {
			alert('E');
			return;
		}
		var Feature = function (name, title, description) {
			return new Element(
				'div',
				{
					'id' : name,
					'class' : 'feature sortable'
				}
			).adopt(
				new Element(
					'h2',
					{
						'text' : title
					}
				),
				new Element(
					'p',
					{
						'text' : description
					}
				),
				new Element(
					'p'
				).adopt(
					new Element(
						'a',
						{
							'href' : './features/' + name,
							'text' : 'More...'
						}
					)
				)
			);
		}
		$$('.feature').each(
			function (o) {
				if (!$('fs_' + o.id).checked) gfx.editor.sortable.removeItems(o).destroy();
			}
		);
		var f = $('features');
		$each(
			s,
			function (o) {
				if (!$(o.id.substr(3))) {
					f.adopt(new Feature(o.id.substr(3), o.getNext().firstChild.nodeValue, o.getNext().getProperty('title')));
					gfx.editor.sortable.addItems(f.lastChild);
				}
			}
		);
		return false;
	},
	'changeTitle' : function () {
		title = $('title-name-edit').getFirst().value;
		$('title-name').firstChild.nodeValue = title;
		//$('header_username').firstChild.nodeValue = title;
		this.setStyle('display');
		$('title-name').setStyle('display');
	},
	'changeAvatar' : function (avatar, url) {
		gfx.editor.avatar = avatar;
		$('title-avatar').getFirst().src = url;
		gfx.closeWindow('avatar');
	},
	'savePage' : function () {
		//Gather data
		var d = {
			'title' : $('title-name').firstChild.nodeValue,
			'name' : $('name').value
		}
		if (gfx.editor.avatar) {
			d.avatar = gfx.editor.avatar;
		}
		if (gfx.editor.featureChanged) {
			d.features = {};
			$each(
				gfx.editor.sortable.serialize(),
				function (f, i) {
					d.features[i+1] = $('fs_' + f).name.substr(3);
				}
			);
		}
		//check for errors
		if (d.title.length > 128) {
			window.alert('TITLE LENGTH');
			return;
		}
		if (d.name.length > 24) {
			window.alert('NAME LENGTH');
			return;
		}
		if (!d.name.test('[a-zA-Z0-9_\-]+')) {
			window.alert('NAME CONTENT');
			return;
		}
		//ajax send
		gfx.editor.jsonRequest = new Request.JSON(
			{
				url: './editor/save',
				method: 'post',
				data: d,
				timeout: 20000,
				onCancel : function (result) {
					//
				},
				onFailure : function (xhr) {
					if (xhr.readyState === 4) {
						window.alert('failed');
					}
				},
				onTimeout : function () {
					window.alert('timeout');
				},
				onSuccess: function (result) {
					if (!result) {
						window.alert('EMPTY ERROR');
						return;
					}
					if (result.error) {
						window.alert(result.error);
						return;
					}
					$('window_userpage_url').href = './' + result.name;
					gfx.closeWindow('savepage');
					gfx.openWindow('editcomplete');
				}
			}
		).send();
	}
};
$extend(
	gfx.windowSize,
	{
		'avatar' : [60, 30],
		'savepage' : [60, 60],
		'editcomplete': [40, 50]
	}
);