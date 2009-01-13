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
		<p><button id="editor_save_button">編輯完畢，選擇網址、儲存、領取宣傳貼紙！</button></p>
	</div>
	<div id="window_savepage" class="window">
		<p class="close"><a href="#">關閉</a></p>
		<div class="window_content">
			<h2>快完成了...</h2>
			<p>URL: <?php print base_url() . form_input(array('id' =>'name', 'value' => (substr($name, 0, 8) === '__temp__')?'':$name)); ?></p>
			<p><button id="save_page">確定</button></p>
		</div>
	</div>
	<div id="window_editcomplete" class="window">
		<p class="close"><a href="#">關閉</a></p>
		<div class="window_content">
			<h2>完成！</h2>
			<p><a id="window_userpage_url" href="#">我的個人宣傳頁面</a></p>
			<p><a href="./stickers">領取宣傳貼紙</a></p>
		</div>
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
		<p class="close"><a href="#">關閉</a></p>
		<div class="window_content">
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
			<h2><?php print htmlspecialchars($title) ?></h2>
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
