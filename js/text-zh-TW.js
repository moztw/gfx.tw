var T = {
	/* xhr & swfupload result parsing error */
	AJAX_ERROR: {
		TIMEOUT : '連線逾時，請檢查網路連線並再試一次。',
		PARSE_RESPONSE: '伺服器傳回不明訊息，請再試一次。',
		UNABLE_TO_CONNECT: '無法連線伺服器，請檢查網路連線並再試一次。',
		SERVER_RESPONSE: '伺服器錯誤，請通知管理員。'
	},
	/* swfupload pre-upload errors */
	SWFUPLOAD: {
		ZERO_BYTE_FILE: '檔案的大小為 0 。',
		FILE_EXCEEDS_SIZE_LIMIT: '檔案超過限制大小。',
		INVALID_FILETYPE: '無法接受此檔案格式。'
	},
	/* ui texts and alert, confirm messages */
	UI: {
		USING_IE_TO_EDIT: '不覺得用 IE 編輯抓火狐推薦頁有點諷刺嗎？好啦，認真說，編輯頁用 IE 應該會壞掉，所以改用 Firefox 吧。',
		EMPTY_TITLE: '在這裡輸入暱稱',
		TITLE_PLACEHOLDER: '{您的名字}',
		CONFIRM_QUIT: '離開此頁會失去所有未儲存的資料！',
		FEATURES_COUNT: '請選三個你推薦別人使用 Firefox 的理由，三個就對了！',
		EDITOR_NO_TITLE: '請輸入暱稱。',
		TITLE_LENGTH: '名稱太長了喔！',
		NAME_LENGTH: '網址太長了喔！',
		NO_GROUPS: '必須要選擇一個以上的火狐屬性。',
		ADDON_ADD: '新增',
		ADDON_ADD_CANT_DUP: '無法重複新增',
		ADDON_DEL: '刪除',
		ADDON_SUGGEST_LIST: '或是從下面選擇最多人在此屬性推薦的附加元件 ...',
		ADDON_SEARCH_RESULT: '搜尋結果：',
		EXTINSTALL_NOT_FX: '必須要使用 Firefox 系列瀏覽器才能安裝附加元件喔。',
		EXTINSTALL_CHECKED_NOTHING: '您沒有選擇任何附加元件。'
	},
	/* $.dialog buttons */
	BUTTONS: {
		PROGRESS_FORCESTOP: '停止',
		ALMOSTDONE_OK: '確定',
		INFO_OK: '確定',
		ADDON_ADD_OK: '確定',
		DOWNLOAD_OK: '確定',
		EXTINSTALL_OK: '確定'
	},
	/* substitution of server error messages */
	EDITOR_NOT_LOGGED_IN: '沒有登入，無法存檔。或是請開新分頁重新登入再按一次存檔。',
	EDITOR_SAVE_NO_NAME: '沒有提供網址，你搞笑喔？',
	EDITOR_SAVE_NO_TITLE: '沒有提供名稱，你搞笑喔？',
	EDITOR_FORUM_CODE: '討論區認證碼認證失敗。',
	EDITOR_SAVE_ERROR_TOKEN: '認證失敗。',
	EDITOR_BAD_NAME: '無法使用此網址作為您的推薦頁，請換一個。',
	EDITOR_SAVE_FEATURE_ERROR: '推薦功能不是數字，你又搞笑了。',
	EDITOR_AVATAR_WRONG_FILE_TYPE: '上傳的不是圖檔，無法使用。',
	EDITOR_AVATAR_SIZE_TOO_LARGE: '上傳的檔案長寬太大了，請換小一點的圖片。'
};