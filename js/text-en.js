var T = {
	/* xhr & swfupload result parsing error */
	AJAX_ERROR: {
		TIMEOUT : 'Connection Timeout, please check your Internet connection and try again.',
		PARSE_RESPONSE: 'Server returns unknown messages, please try again.',
		UNABLE_TO_CONNECT: 'Unable to connect to the server. Please check your Internet connection and try again.',
		SERVER_RESPONSE: 'Server returns an error. Please contact the staff.'
	},
	/* swfupload pre-upload errors */
	SWFUPLOAD: {
		ZERO_BYTE_FILE: 'File size is zero.',
		FILE_EXCEEDS_SIZE_LIMIT: 'File exceeds size limit.',
		INVALID_FILETYPE: 'Invalid file type.'
	},
	/* alert messages (errors only) */
	ALERT: {
		NOT_LOGGED_IN: 'You have been logged out. Please open another tab to log in than click save again.',
		AUTH_ERROR: 'Server authrization failed.',

		EXTINSTALL_NOT_FX: 'Add-on can only be installed on Firefox. Go download it!',
		EXTINSTALL_CHECKED_NOTHING: 'You have not yet select any add-on.',

		EDITOR_TITLE_EMPTY: 'You have to provide a name.',
		EDITOR_TITLE_LENGTH: 'Your name is too long.',
		EDITOR_NAME_EMPTY: 'You have to provide an URL.',
		EDITOR_NAME_BAD: 'We cannot accept your gfx URL. Please choose another one.',
		EDITOR_NAME_LENGTH: 'The URL is too long.',
		EDITOR_AVATAR_ERROR: 'This is not a vaild avatar URL.',
		EDITOR_FORUM_CODE: 'Forum ID authorization failed.',
		EDITOR_FEATURE_COUNT: 'Please select three features, three!',
		EDITOR_FEATURE_ERROR: 'Features are not numbers.',
		EDITOR_GROUP_ERROR: 'Gangs are not numbers.',
		EDITOR_GROUP_EMPTY: 'You have to choose one of the "gangs."',
		EDITOR_ADDON_ERROR: 'Addon are not numbers.',
		EDITOR_AVATAR_WRONG_FILE_TYPE: 'The file you uploaded is not a picture.',
		EDITOR_AVATAR_SIZE_TOO_LARGE: 'The file you uploaded is too large.'
	},
	/* ui texts and confirm messages */
	UI: {
		USING_IE_TO_EDIT: 'Don\'t you feel it\'s a bit ironic to edit your page with IE? Seriously, this page will break in IE so please try to use Firefox instead.',
		EMPTY_TITLE: 'Enter your name here',
		TITLE_PLACEHOLDER: '{your name}',
		CONFIRM_QUIT: 'Leave this page will lost all your unsaved modifications!',
		ADDON_ADD: 'Add',
		ADDON_ADD_CANT_DUP: 'Cannot duplicate',
		ADDON_DEL: 'Delete',
		ADDON_SUGGEST_LIST: 'Or select add-ons from there most suggested under this gang ...',
		ADDON_SEARCH_RESULT: 'Search result:',
		ADMIN_DELETEUSER_CONFIRM: 'Are you sure you want to delete this account?',
		ADMIN_FACEOFF_CONFIRM: 'You would have to log out and log back in again to switch back, are you sure?',
		ADMIN_DELETEFEATURE_CONFIRM: 'Are you sure you want to delete this featur introduction?'
	},
	/* $.dialog buttons */
	BUTTONS: {
		PROGRESS_FORCESTOP: 'Stop',
		ALMOSTDONE_OK: 'OK',
		INFO_OK: 'Close',
		ADDON_ADD_OK: 'Ok',
		DOWNLOAD_OK: 'Close',
		EXTINSTALL_OK: 'Close',
		DELETE_OK: 'Ok, DELETE MY ACCOUNT',
		ADMIN_OK: 'Save',
		ADMIN_FACEOFF: 'Switch to this Account',
		ADMIN_DELETEUSER: 'DELETE ACCOUNT',
		ADMIN_EDIT_FEATURE: 'Admin: Edit this feature',
		ADMIN_DELETEFEATURE: 'Delete this feature'
	}
};