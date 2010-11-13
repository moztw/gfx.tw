var T = {
	/* xhr & swfupload result parsing error */
	AJAX_ERROR: {
		TIMEOUT : '連線逾時，請檢查網路連線並再試一次。',
		PARSE_RESPONSE: '伺服器傳回不明訊息，請再試一次。',
		UNABLE_TO_CONNECT: '無法連線伺服器，請檢查網路連線並再試一次。',
		SERVER_RESPONSE: '伺服器錯誤，請通知管理員。'
	},
	/* file upload pre-upload errors */
	FILEUPLOAD: {
		FILE_EXCEEDS_SIZE_LIMIT: '檔案超過限制大小。',
		INVALID_FILETYPE: '無法接受此檔案格式。',
		NOT_FOUND: '找不到檔案。',
		NOT_READABLE: '無法讀取此檔案。',
		SECURITY: '您沒有讀取此檔案的權限。'
	},
	/* alert messages (errors only) */
	ALERT: {
		NOT_LOGGED_IN: '沒有登入，無法存檔。或是請開新分頁重新登入再按一次存檔。',
		AUTH_ERROR: '認證失敗。',

		EXTINSTALL_NOT_FX: '必須要使用 Firefox 系列瀏覽器才能安裝附加元件喔。',
		EXTINSTALL_CHECKED_NOTHING: '您沒有選擇任何附加元件。',

		EDITOR_TITLE_EMPTY: '請先輸入暱稱。',
		EDITOR_TITLE_LENGTH: '名稱太長了喔！',
		EDITOR_NAME_EMPTY: '請輸入網址。',
		EDITOR_NAME_BAD: '無法使用此網址作為您的推薦頁，請換一個。',
		EDITOR_NAME_LENGTH: '網址太長了喔！',
		EDITOR_URL_BAD: '不能接受此網址作為部落格/網頁網址。',
		EDITOR_AVATAR_ERROR: '無法接受這個個人圖示網址。',
		EDITOR_UPLOAD_FLASH_FAILED: 'Flash 上傳啟動失敗，請改用其他個人圖示上傳方式。',
		EDITOR_FORUM_CODE: '討論區認證碼認證失敗。',
		EDITOR_FEATURE_COUNT: '請選三個你推薦別人使用 Firefox 的理由，三個就對了！',
		EDITOR_FEATURE_ERROR: '推薦功能不是數字。',
		EDITOR_GROUP_ERROR: '附加元件分類不是數字。',
		EDITOR_GROUP_EMPTY: '必須要選擇一個以上的附加元件分類。',
		EDITOR_ADDON_EMPTY: '您並沒有在選擇的某個分類內新增附加元件。',
		EDITOR_ADDON_ERROR: '附加元件不是數字。',
		EDITOR_AVATAR_WRONG_FILE_TYPE: '上傳的不是圖檔，無法使用。',
		EDITOR_AVATAR_SIZE_TOO_LARGE: '上傳的檔案長寬太大了，請換小一點的圖片。'
	},
	/* ui texts and confirm messages */
	UI: {
		INTRO_TEXT_FX_USER: '不如現在就加入大家的行列，向您的親朋好友推薦 Firefox！',
		INTRO_TEXT_NON_FX_USER: '來看看大家推薦您改用 Firefox 的理由！',
		USING_IE_TO_EDIT: '不覺得用 IE 編輯抓火狐推薦頁有點諷刺嗎？好啦，認真說，編輯頁用 IE 應該會壞掉，所以改用 Firefox 吧。',
		EMPTY_TITLE: '在這裡輸入暱稱',
		EMPTY_RECOMMENDATION: '加一些您自己對 Firefox 的評語 ...',
		TITLE_PLACEHOLDER: '{您的名字}',
		CONFIRM_QUIT: '離開此頁會失去所有未儲存的資料！',
		INFO_UPDATED: '個人介紹成功儲存。',
		ADDON_OS_NO_MATCH: '不支援您的作業系統',
		ADDON_ADD: '新增',
		ADDON_ADD_CANT_DUP: '無法重複新增',
		ADDON_DEL: '刪除',
		ADDON_SUGGEST_LIST: '或是從下面選擇最多人在此屬性推薦的附加元件 ...',
		ADDON_SEARCH_RESULT: '搜尋結果：',
		PUSH_MINE: '我推薦大家使用 Firefox ',
		ADMIN_DELETEUSER_CONFIRM: '確定要刪除這個帳號？',
		ADMIN_FACEOFF_CONFIRM: '切換到帳號之後，您必須登出才能變回自己。您確定嗎？',
		ADMIN_DELETEFEATURE_CONFIRM: '確定要刪除這個功能推薦？',
		ADMIN_DELETEABOUT_CONFIRM: '確定要刪除這個網站簡介頁面？',
		ADMIN_SITE_MANAGE: '網站管理'
	},
	/* $.dialog buttons */
	BUTTONS: {
		PROGRESS_FORCESTOP: '停止',
		ALMOSTDONE_OK: '確定',
		INFO_SAVE: '儲存變更',
		INFO_CANCEL: '取消',
		ADDON_ADD_OK: '確定',
		DOWNLOAD_OK: '確定',
		EXTINSTALL_OK: '確定',
		DELETE_OK: '了解，刪除我的帳號',
		ADMIN_OK: '儲存修改',
		ADMIN_FACEOFF: '切換到此帳號',
		ADMIN_DELETEUSER: '刪除帳號',
		ADMIN_EDIT_FEATURE: '管理：編輯功能介紹',
		ADMIN_DELETEFEATURE: '刪除功能推薦',
		ADMIN_DELETEABOUT: '刪除頁面'
	}
};
