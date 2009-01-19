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
		<p>編輯完畢，選擇網址、儲存、領取宣傳貼紙！<button id="editor_save_button">儲存您的頁面</button></p>
	</div>
	<div id="window_savepage" class="window" title="快完成了...">
		<p>URL: <?php print base_url() . form_input(array('id' =>'name', 'value' => (substr($name, 0, 8) === '__temp__')?'':$name)); ?></p>
		<p><button id="save_page">確定</button></p>
	</div>
	<div id="window_editcomplete" class="window" title="完成！">
		<p><a id="window_userpage_url" href="#">我的個人宣傳頁面</a></p>
		<p><a href="./stickers">領取宣傳貼紙</a></p>
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
			<p>免費下載</p>
			<p>(您的推薦指數會在這裡出現)</p>
		</div>
		<p class="desc">您的網際生活將因........///面放為！知利空國看動。者以目該當；聽工龍年影……清實工球能！清像童難喜回下，照獲風時接一！展下停然事漸其歡與態，王親然體分，問象讓它個球作陽的能加球起政活業。大德師但！達是性因，於影通身興師片保原二愛式政由來手紙庭世，獨北見維能本痛半有情當不給福公中！</p>
	</div>
	<div id="window_avatar" class="window">
		<div class="avatar_selection">
			<div id="avatar_spfupload" class="avatar_icon">
				<div id="avatar_spfupload_replace">&nbsp;</div>
			</div>
			<p>上傳圖片</p>
		</div>
		<div class="avatar_selection">
			<div id="avatar_glavatar" class="avatar_icon">
				<img src="<? print 'http://www.gravatar.com/avatar/' . md5($email) . '?s=60&amp;r=g&amp;d=' . urlencode(site_url('images/keyhole_edit.gif')); ?>" alt="Gravatar" />
			</div>
			<p><a href="http://www.gravatar.com/">Gravatar</a>上的圖示</p>
		</div>
		<div class="avatar_selection">
			<div id="avatar_default" class="avatar_icon">
				<img src="./images/keyhole_edit.gif" alt="鑰匙孔小人" />
			</div>
			<p>預設圖示</p>
		</div>
	</div>
	<div id="featureselection">
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
			<p><a href="<?php print site_url('feature/' . $name); ?>">More ...</a></p>
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
	<h2>關於<span class="title-placeholder">(您的名字)</span></h2>
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
	<h2 id="groups-title"><span class="title-placeholder">(您的名字)</span>的火狐屬性</h2>
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
	<div id="window_addons" class="window">
		<h2>新增附加元件</h2>
		<p>搜尋名稱或是輸入<a href="https://addons.mozilla.org">Mozilla Add-ons</a>元件編號: <?php print form_input(array('id' =>'addon_query', 'value' => '')); ?></p>
		<p><button id="addon_query_ok">確定</button></p>
		<p id="addon_query_result" class="detailed"></p>
		<p>名稱搜尋僅適用曾被新增過的附加元件；未曾新增的元件一定要輸入 AMO 編號。</p>
	</div>
