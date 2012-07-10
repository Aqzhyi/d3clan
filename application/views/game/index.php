<?php
// 目錄結構
// http://d3clan.tw/game/{$path}/{$page}/{$title}
// \d3clan.tw\application\views\game\{$path}\{$page}.php

// 選單結構
$data_list[] = array(
	'folder_name' => '職業介紹',
	'links' => array(
		'class/barbarian'    => array( 'text' => '野蠻人', ),
		'class/wizard'       => array( 'text' => '秘術師', ),
		'class/demon-hunter' => array( 'text' => '狩魔獵人', ),
		'class/witch-doctor' => array( 'text' => '巫醫', ),
		'class/monk'         => array( 'text' => '武僧', ),
	),
);
$data_list[] = array(
	'folder_name' => '基本說明',
	'links' => array(
		'遊戲簡介',
		'intro/what-is-d3'              => array( 'text' => '什麼是《暗黑破壞神III》？', ),
		'intro/history'                 => array( 'text' => '故事提要', ),
		'遊戲操作',
		'gameplay/fundamentals'         => array( 'text' => '基礎操作' ),
		'gameplay/combat-skills'        => array( 'text' => '戰鬥技能' ),
		'gameplay/world'                => array( 'text' => '遊戲場景' ),
		'gameplay/objects'              => array( 'text' => '物件' ),
		'gameplay/followers'            => array( 'text' => '追隨者' ),
		'gameplay/playing-with-friends' => array( 'text' => '與好友一同遊戲' ),
		'物品',
		'intro/equipment'                => array( 'text' => '物品與裝備' ),
		'intro/inventory'                => array( 'text' => '物品欄' ),
		'intro/crafting'                 => array( 'text' => '製作與工匠' ),
		'intro/auction-house'            => array( 'text' => '拍賣場' ),
	),
);
$data_list[] = array(
	'folder_name' => '追隨者',
	'links' => array(
		'follower/index'       => array( 'text' => '追隨者系統', ),
		'follower/enchantress' => array( 'text' => '巫女', ),
		'follower/scoundrel'   => array( 'text' => '盜賊', ),
		'follower/templar'     => array( 'text' => '聖堂騎士', ),
	),
);
$data_list[] = array(
	'folder_name' => '物品',
	'links' => array(
		'item/gem'               => array( 'text' => '寶石', ),
		'item/dye'               => array( 'text' => '染料', ),
		'item/potion'            => array( 'text' => '藥水', ),
		'item/crafting-material' => array( 'text' => '製作材料', ),
	),
);
$data_list[] = array(
	'folder_name' => '工匠',
	'links' => array(
		'artisan/index'      => array( 'text' => '工匠系統', ),
		'artisan/blacksmith' => array( 'text' => '鐵匠', ),
		'artisan/jeweler'    => array( 'text' => '珠寶匠', ),
	),
);
?>
<div class="namespace_game g_clear">
	<!-- 選單結構 -->
	<div id="data_list" class="data_list">
		<!-- 搜尋 -->
		<input id="data_list_search" class="data_list_search g_search" type="text" placeholder="搜尋...">
		<!-- 選單 -->
		<?php foreach ( $data_list as $index => $list_item ): ?>
			<div class="data_title">
				<!-- 大分類顯示名稱 -->
				<span class="title"><?php echo $list_item['folder_name'] ?></span>
				<div class="data_hide">
					<!-- 細項(們) -->
					<?php echo $this->load->view( 'game/partial/menu-items.php', $list_item ) ?>
				</div>
			</div>
		<?php endforeach ?>
	</div>

	<!-- 主內容 -->
	<div class="content">
		<!-- 主題內容 -->
		<div class="each_page_content">
			<?php echo $page ?>
		</div>
	</div>
</div>
<!--  -->
