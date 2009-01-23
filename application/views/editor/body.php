<?php

if (!$avatar) {
	$avatar = './images/keyhole_edit.gif';
} elseif ($avatar === '(gravatar)') {
	$avatar = 'http://www.gravatar.com/avatar/' . md5($email) . '?s=60&amp;r=g&amp;d=' . urlencode(site_url('images/keyhole_edit.gif'));
} else {
	$avatar = './useravatars/' . $avatar;
}

?>
	<div id="editor_save">
		<p><button id="editor_save_button">儲存您的頁面</button>編輯完畢，儲存變更、領取宣傳貼紙！</p>
	</div>
	<div id="window_almostdone" class="window" title="快完成了...">
		<p>請設定您的推薦網頁的專用網址，需使用英數字：</p>
		<p><?php print base_url() . form_input(array('id' =>'name', 'value' => (substr($name, 0, 8) === '__temp__')?'':$name)); ?></p>
	</div>
	<div id="window_editcomplete" class="window" title="完成！">
		<p>感謝您向大家推薦 Mozilla Firefox，接下來...</p>
		<ul>
			<li>看看<a id="window_userpage_url" href="#">您的個人推薦網頁</a></li>
			<li>領取<a href="./stickers">宣傳貼紙</a>，在部落格、論壇宣傳！</li>
		</ul>
	</div>
	<div id="titleblock">
		<h1>
			<span id="title-avatar" class="editable"><img src="<?php print $avatar ?>" alt="[個人小圖示]" /></span>
			<span id="title-name" class="editable"><?php print htmlspecialchars($title) ?></span>
			<span id="title-name-edit"><?php print form_input('title', $title); ?></span>
			<span id="title-1">推薦您改用</span>
			<span id="title-2">Firefox</span>
			<span id="title-3">看網頁！</span>
		</h1>
		<div class="download">
			<p class="link"><a href="#">免費下載</a></p>
			<p class="version">3.0 系列</p>
		</div>
		<p class="count">{您的推薦指數會在這裡出現}</p>
		<p class="desc">您的網際生活將因 Firefox 更加豐富有趣！Firefox 有許多特色，協助您完成工作、找到資訊。正因為它如此實用，<span class="title-placeholder">{您的名字}</span>願意推薦您改用 Firefox！以下是<span class="title-placeholder">{您的名字}</span>最喜歡 Firefox 的三大特點：</p>
	</div>
	<div id="window_avatar" class="window" title="選擇個人圖示">
		<div class="avatar_selection">
			<div id="avatar_spfupload" class="avatar_icon">
				<div id="avatar_spfupload_replace">&nbsp;</div>
			</div>
			<p>上傳照片</p>
		</div>
		<div class="avatar_selection">
			<div id="avatar_glavatar" class="avatar_icon">
				<img src="<? print 'http://www.gravatar.com/avatar/' . md5($email) . '?s=60&amp;r=g&amp;d=' . urlencode(site_url('images/keyhole.gif')); ?>" alt="Gravatar" />
			</div>
			<p><a href="http://www.gravatar.com/" class="newwindow">Gravatar</a>上的圖示</p>
		</div>
		<div class="avatar_selection">
			<div id="avatar_default" class="avatar_icon">
				<img src="./images/keyhole.gif" alt="鑰匙孔小人" />
			</div>
			<p>預設圖示</p>
		</div>
	</div>
	<div id="featureselection">
		<p class="features-desc">請選三個你推薦別人使用 Firefox 的理由（確定後可以拖曳改變順序）：</p>
		<ul>
<?php
/* put it into a function scope */
function featureselection($feature) {
	extract($feature);
?>
			<li><?php
	print form_checkbox(array('id' => 'fs_' . $name, 'name' => 'fs_' . $id, 'checked' => isset($user_id)));
	print form_label($title, 'fs_' . $name, array('title' => $description));
	unset($user_id);
?></li>

<?php
}
$features = array();
foreach ($allfeatures as $feature) {
	featureselection($feature);
	if (isset($feature['order'])) {
		$features[intval($feature['order'])] = $feature;
	}
}
?>
		</ul>
		<p id="featureselection_save"><button>確定</button></p>
	</div>
	<div id="features">
<?php
/* put it into a function scope */
function feature($feature) {
	extract($feature);
?>

		<div class="feature sortable" id="<?php print $name ?>">
			<h2 id="featureid-<?php print $id ?>"><?php print htmlspecialchars($title) ?></h2>
			<p><?php print htmlspecialchars($description) ?></p>
			<p class="link"><a href="<?php print site_url('feature/' . $name); ?>">More ...</a></p>
		</div>
<?php
}
//cannot foreach
$i = 1;
while(isset($features[$i])) {
	feature($features[$i]);
	$i++;
}
?>
	</div>
	<div id="userinfo">
	<h2>關於<span class="title-placeholder">{您的名字}</span></h2>
	<p><button>編輯</button></p>
	<ul>
		<li><span class="item">抓火狐網址</span> <a class="gfxurl value" href="<?php print site_url($name); ?>"><?php print site_url($name); ?></a></li>
<?php if ($web)  { ?>
		<li><span class="item">網站</span> <a class="web value" href="<?php print htmlspecialchars($web); ?>"><?php print htmlspecialchars($web); ?></a></li>
<?php } ?>
<?php if ($blog)  { ?>
		<li><span class="item">部落格</span> <a class="blog value" href="<?php print htmlspecialchars($blog); ?>"><?php print htmlspecialchars($blog); ?></a></li>
<?php } ?>
<?php if ($forum_username)  { ?>
		<li><span class="item"><a href="http://forum.moztw.org/">MozTW 討論區</a>ID</span> <span class="forum-username value"><?php print htmlspecialchars($forum_username); ?></span></li>
<?php } ?>
<?php if ($bio)  { ?>
		<li><span class="item">一行自介</span> <span class="bio value"><?php print htmlspecialchars($bio); ?></span></li>
<?php } ?>
	</ul>
	</div>
	<h2 id="groups-title"><span class="title-placeholder">{您的名字}</span>的火狐屬性</h2>
	<p>請在下方選擇符合您網際活動的屬性，並加入您推薦的附加元件：</p>
	<div id="groups">
<?php
/* put it into a function scope */
function addon($addon) {
	extract($addon);
	if (!isset($icon_url)) $icon_url = '';
	if ($url === '' && $amo_id !== '') $url = 'https://addons.mozilla.org/zh-TW/firefox/addon/' . $amo_id;
	elseif ($url === '' && $amo_id === '') return;
?>
		<div class="addon" id="a_<?php print $id ?>">
			<p><a href="<?php print htmlspecialchars($url); ?>"><img src="<? print htmlspecialchars($icon_url) ?>" alt="" /><span><?php print htmlspecialchars($title); ?></span></a></p>
			<p class="del-addon">Del</p>
		</div>
<?php
}
function group($group, $addons) {
	extract($group);
?>

		<div class="group" id="<?php print $name ?>">
			<div class="group-title sortable<?php print (isset($user_id))?'':' not-selected'; ?>" id="g_<?php print $id ?>">
				<input type="checkbox" <?php print (isset($user_id))?'checked="checked"':''; ?>/>
				<h3><?php print htmlspecialchars($title) ?></h3>
				<p class="group-add-addon"><a href="#">新增元件</a></p>
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
	<div id="window_addons" class="window" title="新增附加元件">
		<p>搜尋名稱或是輸入<a href="https://addons.mozilla.org">Mozilla Add-ons</a>元件編號: <?php print form_input(array('id' =>'addon_query', 'value' => '')); ?></p>
		<p><button id="addon_query_ok">確定</button></p>
		<p id="addon_query_result" class="detailed"></p>
		<p>名稱搜尋僅適用曾被新增過的附加元件；未曾新增的元件一定要輸入 AMO 編號。</p>
	</div>
	<div id="window_progress" class="window" title="與伺服器通訊中...">
		<img src="images/ajax-progress.gif" alt="處理中..." />
	</div>