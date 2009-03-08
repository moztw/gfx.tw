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
	/* ui texts and alert, confirm messages */
	UI: {
		USING_IE_TO_EDIT: 'Don\'t you feel it\'s a bit ironic to edit your page with IE? Seriously, this page will break in IE so please try to use Firefox instead.',
		EMPTY_TITLE: 'Enter your name here',
		TITLE_PLACEHOLDER: '{your name}',
		CONFIRM_QUIT: 'Leave this page will lost all your unsaved modifications!',
		FEATURES_COUNT: 'Please select three features, three!',
		EDITOR_NO_TITLE: 'Please enter your name.',
		TITLE_LENGTH: 'It looks like your name is too long.',
		NAME_LENGTH: 'The URL is too long.',
		NO_GROUPS: 'You have to choose one of the "gangs."',
		ADDON_ADD: 'Add',
		ADDON_ADD_CANT_DUP: 'Cannot duplicate',
		ADDON_DEL: 'Delete',
		ADDON_SUGGEST_LIST: 'Or select add-ons from there most suggested under this gang ...',
		ADDON_SEARCH_RESULT: 'Search result:',
		EXTINSTALL_NOT_FX: 'Add-on can only be installed on Firefox. Go download it!',
		EXTINSTALL_CHECKED_NOTHING: 'You have not yet select any add-on.'
	},
	/* $.dialog buttons */
	BUTTONS: {
		PROGRESS_FORCESTOP: 'Stop',
		ALMOSTDONE_OK: 'OK',
		INFO_OK: 'Close',
		ADDON_ADD_OK: 'Ok',
		DOWNLOAD_OK: 'Close',
		EXTINSTALL_OK: 'Close'
	},
	/* substitution of server error messages */
	EDITOR_NOT_LOGGED_IN: 'You have been logged out. Please open another tab to log in than click save again.',
	EDITOR_SAVE_NO_NAME: 'You have to provide an URL.',
	EDITOR_SAVE_NO_TITLE: 'You have to provide a name.',
	EDITOR_FORUM_CODE: 'Forum ID authorization failed.',
	EDITOR_SAVE_ERROR_TOKEN: 'Server authrization failed.',
	EDITOR_BAD_NAME: 'We cannot accept your gfx URL. Please choose another one.',
	EDITOR_SAVE_FEATURE_ERROR: 'Features are not numbers.',
	EDITOR_AVATAR_WRONG_FILE_TYPE: 'The file you uploaded is not a picture.',
	EDITOR_AVATAR_SIZE_TOO_LARGE: 'The file you uploaded is too large.'
};