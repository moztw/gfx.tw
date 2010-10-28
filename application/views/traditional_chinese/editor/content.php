<?php

$this->load->config('gfx');
$this->load->helper('gfx');
$avatar = avatarURL($avatar, $email, $login);

?>
	<div id="editor-save">
		<p><button id="editor-save-button">儲存您的頁面</button>編輯完畢，儲存變更、領取宣傳貼紙！</p>
	</div>
	<div id="window_almostdone" class="window" title="快完成了...">
		<p>請設定您的推薦網頁的專用網址，需使用英數字：</p>
		<p><?php print base_url() . form_input(array('id' =>'name', 'value' => '')); ?></p>
	</div>
	<div id="window_editcomplete" class="window" title="完成！">
		<p>感謝您向大家推薦 Mozilla Firefox，接下來...</p>
		<ul>
			<li>看看<a class="userpage-url" href="#">您的個人推薦網頁</a></li>
			<li>領取<a href="./sticker">宣傳貼紙</a>，在部落格、論壇宣傳！</li>
		</ul>
		<div class="shareblock">
			<p>和大家分享您的抓火狐推薦頁！</p>
			<ul>
				<li><a class="newwindow" title="分享到 Facebook" href="http://www.facebook.com/sharer.php?u=<?php
/* Facebook fetches sticker image and description from <head> */
print urlencode(site_url('PLACEHOLDER'));
?>"><span class="sprite facebook"></span>Facebook</a></li>
				<li><a class="newwindow" title="噗到 Plurk" href="http://plurk.com/?status=<?php
print urlencode('來跟我一起抓火狐，使用 Firefox 逛網頁！ '
        . site_url(
                '/userstickers/' . dechex(intval($id) >> 12) . '/' . dechex(intval($id & (pow(2,12)-1)))
        )
        . '/featurecard.png '
        . site_url($name)
);
?>"><span class="sprite plurk"></span>噗浪</a></li>
				<li><a class="newwindow" title="推到 Twitter" href="http://twitter.com/home/?status=<?php
print urlencode('來跟我一起抓火狐，使用 Firefox 逛網頁！ ' . site_url('PLACEHOLDER'));
?>"><span class="sprite twitter"></span>Twitter</a></li>
				<li><a class="newwindow" title="推薦到 Funp" href="http://funp.com/push/submit/?via=tools&amp;url=<?
/* TBD: push sticker image and description to funp */
print urlencode(site_url('PLACEHOLDER'));
?>"><span class="sprite funp"></span>推推王</a></li>
			</ul>
		</div>
	</div>
	<div id="titleblock">
		<form id="title-name-form" action="#">
			<h1>
				<input type="text" id="title-avatar-textarea" />
				<span id="title-avatar" class="editable" title="選擇個人圖示"><img src="<?php print $avatar ?>" alt="[個人小圖示]" /></span>
				<span id="title-name" class="editable"><?php print htmlspecialchars($title) ?></span>
				<span id="title-name-edit"><?php print form_input('title', $title); ?></span>
				<span id="title-1">推薦您改用</span>
				<span id="title-2">Firefox</span>
				<span id="title-3">看網頁！</span>
			</h1>
		</form>
		<div class="download">
			<p class="link"><a href="/download">免費下載</a></p>
			<p class="version">3.6 系列最新版</p>
		</div>
		<p class="count">{您的推薦指數會在這裡出現}</p>
		<p class="desc">您的網際生活將因 Firefox 更加豐富有趣！Firefox 有許多特色，協助您完成工作、找到資訊。正因為它如此實用，<span class="title-placeholder">{您的名字}</span>願意推薦您改用 Firefox！以下是<span class="title-placeholder">{您的名字}</span>最喜歡 Firefox 的三大特點：</p>
	</div>
	<div id="window_avatar" class="window" title="選擇個人圖示">
		<div id="dropzone">&nbsp;</div>
		<div class="avatar_selection">
			<div id="avatar_default" class="avatar_icon">
				<img src="./images/avatar-default.gif" alt="預設圖示" />
			</div>
			<p class="avatar_desc">預設圖示</p>
			<p>使用預設圖示的話會錯過很多好玩的事情喔！</p>
		</div>
		<div class="avatar_selection">
			<div id="avatar_fileupload" class="avatar_icon">
				<input type="file" />
			</div>
			<p class="avatar_desc">上傳圖片檔案</p>
			<p>從您的電腦上傳圖片檔案作為個人圖示。</p>
			<p id="avatar_disabled_desc_fileupload" class="avatar_disabled_desc">僅供 HTML5 瀏覽器使用</p>
		</div>
<?php
if ($email) {
?>
		<div class="avatar_selection">
<?php
} else {
?>
		<div class="avatar_selection disabled">
<?php
}
?>
			<div id="avatar_gravatar" class="avatar_icon">
				<img src="<?php print 'http://www.gravatar.com/avatar/' . md5($email) . '?s=60&amp;r=g&amp;d=wavatar'; ?>" alt="Gravatar" />
			</div>
			<p class="avatar_desc"> Gravatar 頭像</p>
			<p>使用 <a href="http://www.gravatar.com/" id="link-gravatar" class="newwindow">Gravatar</a> 上的個人圖示或是 E-mail 雜湊圖片。</p>
			<p id="avatar_disabled_desc_gravatar" class="avatar_disabled_desc">請先<a href="#" id="change-email">輸入 E-mail</a></p>
		</div>
<?php
if (preg_match('/myid\.tw\/$/', $login)) { ?>
		<div class="avatar_selection">
			<div id="avatar_myidtw" class="avatar_icon">
				<img src="<?php print 'http://myid.tw/plugin/gethead?name=' . urlencode($login) . '&amp;type=s&amp;mode=302'; ?>" alt="MyID.tw 個人圖像" />

<?php
} else { ?>
		<div class="avatar_selection disabled">
			<div id="avatar_myidtw" class="avatar_icon">
				<img src="http://myid.tw/images/userimage.jpg" alt="MyID.tw 個人圖像" />


<?php
} ?>
			</div>
			<p class="avatar_desc">MyID.tw 個人圖像</p>
			<p>使用您在 <a href="https://myid.tw/" id="link-myidtw" class="newwindow">MyID.tw</a> 上傳的個人圖像當作圖示。</p>
			<p id="avatar_disabled_desc_myidtw" class="avatar_disabled_desc">僅供 MyID.tw 使用者使用</p>
		</div>
	</div>
	<div id="window_download" class="window" title="正在啟動下載...">
		<h2>感謝您下載 Firefox！</h2>
		<p>下載將在幾秒內開始，如果沒有啟動請按<a href="/download">這裡</a>。</p>
		<p>安裝完畢後，別忘了使用 Firefox 連到：</p>
		<p class="gfx-url"><?php print site_url('{您的推薦網址}') ?></p>
		<p>依照<span class="title-placeholder">{您的名字}</span>的建議，加入附加元件改造屬於您的火狐！</p>
	</div>
	<div id="featureselection">
		<p class="features-desc">請將想要推薦的功能拖放到您想要顯示的位置。</p>
		<ul>
<?php
/* put it into a function scope */
function featureselection($feature) {
	extract($feature);
?>
			<li id="fs-<?php print $name; ?>"<?php if (isset($user_order)) print ' class="selected"';?> rel="fid-<?php print $id; ?>" title="<?php print htmlspecialchars($description); ?>"><?php print htmlspecialchars($title); ?></li>
<?php
}
$features = array();
foreach ($allfeatures as $feature) {
	featureselection($feature);
	if (isset($feature['user_order'])) {
		$features[$feature['user_order']] = $feature;
	}
}
?>
		</ul>
		<p id="featureselection-clear"><button>全部重來</button></p>
	</div>
	<div id="features" class="sortable">
<?php
/* put it into a function scope */
function feature($feature) {
	extract($feature);
?>

		<div class="feature" id="<?php print $name ?>">
			<h2 id="featureid-<?php print $id ?>"><?php print htmlspecialchars($title) ?></h2>
			<p><?php print htmlspecialchars($description) ?></p>
			<p class="link"><a href="<?php print site_url('feature/' . $name); ?>">More ...</a></p>
		</div>
<?php
}
for ($i = 0; $i < 3; $i++) {
	if (isset($features[$i])) feature($features[$i]);
	else {
?>
		<div class="feature box">
			<h2>&nbsp;</h2>
			<p>&nbsp;</p>
			<p class="link"><a href="#">More ...</a></p>
		</div>
<?php
	}
}
?>
	</div>
	<div id="middleblock">
	<div id="userinfo">
		<h2>關於<span class="title-placeholder">{您的名字}</span></h2>
		<p>您的個人介紹會出現在此處。<button>編輯</button></p>
	</div>
	<div id="window_info" class="window" title="編輯個人介紹">
		<form id="info_form" action="#">
			<p><label for="info_name">推薦頁網址：</label> <span class="form-prepend"><?php print base_url() ?></span><?php print form_input(array('id' =>'info_name', 'value' => $name)); ?>
			<span class="form-desc">您的推薦網頁的專用網址，需使用英數字。</span></p>
			<p><label for="info_email">E-mail：</label> <?php print form_input(array('id' =>'info_email', 'value' => $email)); ?>
			<span class="form-desc">不會公開；Gravatar 的圖示要在重新載入後才會變更。</span></p>
			<p><label for="info_web">個人首頁：</label> <?php print form_input(array('id' =>'info_web', 'value' => $web)); ?></p>
			<p><label for="info_blog">部落格：</label> <?php print form_input(array('id' =>'info_blog', 'value' => $blog)); ?></p>
			<p><label for="info_password">討論區 ID 認證：</label> <?php print form_password(array('id' =>'info_forum', 'value' => ($forum_id && $forum_username)?'(keep-the-forum-username)':'')); ?>
			<span class="form-desc"><a href="http://forum.moztw.org/gfxcode.php" id="forum_auth">按此處</a>取得認證碼；不想顯示請清除認證碼。</span>
			<span class="form-desc" id="forum_auth_iframe">&nbsp;</span></p>
			<p><label for="info_bio">一行自介：</label>
				<textarea id="info_bio"><?php print htmlspecialchars($bio) ?></textarea>
			</p>
			<p><a href="#" id="info_delete_account">刪除我的帳號</a></p>
		</form>
	</div>
	<div id="window_delete" class="window" title="刪除帳號">
		<form id="delete_post" action="/user/delete" method="post">
			<input type="hidden" name="token" value="<?php print md5($this->session->userdata('id') . $this->config->item('gfx_token')); ?>" />
		</form>
		<p>這會從系統中刪除您所有個人資訊，包括自訂的套件列表、圖片、下載次數紀錄等等。</p>
		<p id="delete-url-notice">另外，您的推薦頁網址 (<?php print base_url(); ?><span class="name-placeholder"><?php print $name ?></span>/) 也將取消，其他人從此可以選用 <span class="name-placeholder"><?php print $name ?></span> 作為他的網址。</p>
		<p>這個動作無法復原。若確定要刪除您的帳號，請按下面的按鈕。</p>
	</div>
	<div id="groups-title">
		<h2><span class="title-placeholder">{您的名字}</span>推薦的附加元件</h2>
		<p>請在下方選擇符合您想要推薦的附加元件類別，並為其加入您推薦的附加元件：</p>
	</div>
	<div id="groups" class="sortable">
<?php
/* put it into a function scope */
function addon($addon) {
	extract($addon);
	if (!$icon_url) $icon_url = site_url('images/addon_default_icon.png');
	if (!isset($xpi_url)) $xpi_url = '';
	if (!$url && $amo_id) $url = 'https://addons.mozilla.org/zh-TW/firefox/addon/' . $amo_id;
	elseif (!$url && !$amo_id) return;
?>
		<div class="addon" id="a_<?php print $id ?>">
			<p class="del-addon ui-icon ui-icon-close" title="刪除">刪除</p>
			<p><a href="<?php print htmlspecialchars($url); ?>"><img src="<? print htmlspecialchars($icon_url) ?>" alt="" /><span><?php print htmlspecialchars($title); ?></span></a></p>
		</div>
<?php
}
function group($group, $addons) {
	extract($group);
?>

		<div class="group" id="<?php print $name ?>">
			<div class="group-title<?php print (isset($user_id))?'':' not-selected'; ?>" id="g_<?php print $id ?>">
				<input type="checkbox" <?php print (isset($user_id))?'checked="checked"':''; ?>/>
				<h3><?php print htmlspecialchars($title) ?></h3>
				<p class="group-add-addon"><a href="#" title="在此屬性下新增附加元件"><span class="ui-icon ui-icon-circle-plus">&nbsp;</span>新增元件</a></p>
				<p><?php print htmlspecialchars($description) ?></p>
			</div>
			<div class="group-addons">
<?php
		foreach ($addons as $addon) {
			if ($addon) addon($addon);
		}
?>
			</div>
		</div>
<?php
}
foreach ($allgroups as $group) {
	group($group, $addons[$group['id']]);
}
?>
	</div>
	<div id="groups-tail">
			<p>直接從頁面安裝附加元件的說明會出現在這裡。</p>
	</div>
	<div id="window_addons" class="window" title="新增附加元件">
		<form action="#" id="addon_query_form">
			<p>搜尋: <?php print form_input(array('id' =>'addon_query', 'value' => '')); ?> <button type="submit">尋找附加元件</button></p>
		</form>
		<p id="addon_query_desc">&nbsp;</p>
		<p id="addon_query_notfound">沒有找到任何附加元件，可能是因為您要找的附加元件從未被推薦過。您可以在搜尋攔貼上該附加元件在 <a href="https://addons.mozilla.org/" class="newwindow">Mozilla 附加元件</a>網站的網址直接推薦。</p>
		<div id="addon_query_result" class="detailed">&nbsp;</div>
	</div>
	<div id="window_progress" class="window" title="與伺服器通訊中...">
		<img src="images/ajax-progress.gif" alt="處理中..." />
	</div>
	</div>
