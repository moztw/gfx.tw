var T = {
	/* xhr & swfupload result parsing error */
	AJAX_ERROR: {
		TIMEOUT : '连线逾时，请检查网路连线并再试一次。',
		PARSE_RESPONSE: '伺服器传回不明讯息，请再试一次。',
		UNABLE_TO_CONNECT: '无法连线伺服器，请检查网路连线并再试一次。',
		SERVER_RESPONSE: '伺服器错误，请通知管理员。'
	},
	/* swfupload pre-upload errors */
	SWFUPLOAD: {
		ZERO_BYTE_FILE: '档案的大小为 0 。',
		FILE_EXCEEDS_SIZE_LIMIT: '档案超过限制大小。',
		INVALID_FILETYPE: '无法接受此档案格式。'
	},
	/* alert messages (errors only) */
	ALERT: {
		NOT_LOGGED_IN: '没有登入，无法存档。或是请开新分页重新登入再按一次存档。',
		AUTH_ERROR: '认证失败。',

		EXTINSTALL_NOT_FX: '必须要使用 Firefox 系列浏览器才能安装附加元件喔。',
		EXTINSTALL_CHECKED_NOTHING: '您没有选择任何附加元件。',

		EDITOR_TITLE_EMPTY: '请先输入昵称。',
		EDITOR_TITLE_LENGTH: '名称太长了喔！',
		EDITOR_NAME_EMPTY: '请输入网址。',
		EDITOR_NAME_BAD: '无法使用此网址作为您的推荐页，请换一个。',
		EDITOR_NAME_LENGTH: '网址太长了喔！',
		EDITOR_URL_BAD: '不能接受此网址作为部落格/网页网址。',
		EDITOR_AVATAR_ERROR: '无法接受这个个人图示网址。',
		EDITOR_FORUM_CODE: '讨论区认证码认证失败。',
		EDITOR_FEATURE_COUNT: '请选三个你推荐别人使用 Firefox 的理由，三个就对了！',
		EDITOR_FEATURE_ERROR: '推荐功能不是数字。',
		EDITOR_GROUP_ERROR: '附加元件分类不是数字。',
		EDITOR_GROUP_EMPTY: '必须要选择一个以上的附加元件分类。',
		EDITOR_ADDON_EMPTY: '您并没有在选择的某个分类内新增附加元件。',
		EDITOR_ADDON_ERROR: '附加元件不是数字。',
		EDITOR_AVATAR_WRONG_FILE_TYPE: '上传的不是图档，无法使用。',
		EDITOR_AVATAR_SIZE_TOO_LARGE: '上传的档案长宽太大了，请换小一点的图片。'
	},
	/* ui texts and confirm messages */
	UI: {
		INTRO_TEXT_FX_USER: '不如现在就加入大家的行列，向您的亲朋好友推荐 Firefox！',
		INTRO_TEXT_NON_FX_USER: '来看看大家推荐您改用 Firefox 的理由！',
		USING_IE_TO_EDIT: '不觉得用 IE 编辑抓火狐推荐页有点讽刺吗？好啦，认真说，编辑页用 IE 应该会坏掉，所以改用 Firefox 吧。',
		EMPTY_TITLE: '在这里输入昵称',
		TITLE_PLACEHOLDER: '{您的名字}',
		CONFIRM_QUIT: '离开此页会失去所有未储存的资料！',
		INFO_UPDATED: '个人介绍成功储存。',
		ADDON_OS_NO_MATCH: '不支援您的作业系统',
		ADDON_ADD: '新增',
		ADDON_ADD_CANT_DUP: '无法重复新增',
		ADDON_DEL: '删除',
		ADDON_SUGGEST_LIST: '或是从下面选择最多人在此属性推荐的附加元件 ...',
		ADDON_SEARCH_RESULT: '搜寻结果：',
		PUSH: '这是 NAME 推荐 Firefox 的理由 ',
		PUSH_MINE: '我推荐大家使用 Firefox ',
		ADMIN_DELETEUSER_CONFIRM: '确定要删除这个帐号？',
		ADMIN_FACEOFF_CONFIRM: '切换到帐号之后，您必须登出才能变回自己。您确定吗？',
		ADMIN_DELETEFEATURE_CONFIRM: '确定要删除这个功能推荐？',
		ADMIN_DELETEABOUT_CONFIRM: '确定要删除这个网站简介页面？',
		ADMIN_SITE_MANAGE: '网站管理'
	},
	/* $.dialog buttons */
	BUTTONS: {
		PROGRESS_FORCESTOP: '停止',
		ALMOSTDONE_OK: '确定',
		INFO_SAVE: '储存变更',
		INFO_CANCEL: '取消',
		ADDON_ADD_OK: '确定',
		DOWNLOAD_OK: '确定',
		EXTINSTALL_OK: '确定',
		DELETE_OK: '了解，删除我的帐号',
		ADMIN_OK: '储存修改',
		ADMIN_FACEOFF: '切换到此帐号',
		ADMIN_DELETEUSER: '删除帐号',
		ADMIN_EDIT_FEATURE: '管理：编辑功能介绍',
		ADMIN_DELETEFEATURE: '删除功能推荐',
		ADMIN_DELETEABOUT: '删除页面'
	}
};