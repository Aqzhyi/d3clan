<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://ogp.me/ns/fb#">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo $loaded_title ?></title>
	<meta http-equiv="X-UA-Compatible" content="chrome=1" />
	<meta name="description" content="<?php echo $this->config->item( 'description' ) ?>"/>
	<meta name="keywords" content="<?php echo $this->config->item( 'keywords' ) ?>"/>
	<link rel="stylesheet" href="/e/m/<?php foreach ( $loaded_css_files as $index => $css_file ) {if ( count( $loaded_css_files )-1 > $index ) {echo $this->config->item( 'css_static_path' ) . "$css_file.css,";} else {echo $this->config->item( 'css_static_path' ) . "$css_file.css";}} ?>">
	<link rel="shortcut icon" href="/static/img/common/icon/official_d3.ico" type="image/x-icon"/>
	<link rel="canonical" href="<?php echo $this->view->get_canonical_url() ?>" />
	<meta property="og:site_name" content="<?php echo $this->config->item( 'site_name' ) ?>" />
	<meta property="og:description" content="<?php echo $this->config->item( 'description' ) ?>" />
	<meta property="og:title" content="<?php echo $loaded_title ?>" />
	<meta property="og:image" content="<?php echo $loaded_og_image ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:locale" content="zh_TW" />
	<meta property="og:url" content="<?php echo $this->view->get_canonical_url() ?>" />
	<script>
		if (location.host === 'dev.d3clan.tw') document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>');
	</script>
	<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
	<div class="wrapper">

		<header>
		<div class="header" id="header">
			<!-- 網站LOGO -->
			<a href="/" class="site_logo"></a>
			<!-- 遊戲LOGO -->
			<a href="/" class="game_logo g_clear"></a>

			<!-- 商業廣告 -->
			<div class="commercial_ad g_clear" id="commercial_ad">
				<a href="http://www.facebook.com/d3clan.tw" class="g_fb" target="_blank"></a>
				<?php echo $this->media->embed_flash( array(
					'width'  => '270',
					'height' => '60',
					'src'    => '\static\file\common\ad\270x60-ASROCK.swf',
					'class'  => 'single',
				) ) ?>
				<?php echo $this->media->embed_flash( array(
					'width'  => '270',
					'height' => '60',
					'src'    => '\static\file\common\ad\270x60-kinston.swf',
					'class'  => 'single',
				) ) ?>
				<?php echo $this->media->embed_flash( array(
					'width'  => '270',
					'height' => '60',
					'src'    => '\static\file\common\ad\270x60-msi.swf',
					'class'  => 'single',
				) ) ?>
				<?php echo $this->media->embed_flash( array(
					'width'  => '270',
					'height' => '60',
					'src'    => '\static\file\common\ad\270x60-msi-cup-banner.swf',
					'class'  => 'single g_hide',
				) ) ?>
				<a href="http://sc2clan.tw/" class="sc2clan" target="_blank"></a>
			</div>

			<!-- 網站導航 -->
			<div class="navigation">
				<nav>
				<a class="single" href="/"><i class="icon home"></i><span class="nav_label">首頁</span></a>
				<a class="single" href="/game"><i class="icon game"></i><span class="nav_label">遊戲資料</span></a>
				<a class="single" href="/hardware"><i class="icon hardware"></i><span class="nav_label">週邊設備</span></a>
				<a class="single" href="/lives"><i class="icon lives"></i><span class="nav_label">Live頻道</span></a>
				<a class="single" href="/event/2012-girls-vote"><i class="icon event-2012-msi"></i><span class="nav_label">D-Girl選拔</span></a>
				<a class="single" href="/event/2012-msi"><i class="icon event-2012-girls-vote"></i><span class="nav_label">微星盃</span></a>
				<a class="single" href="/together"><i class="icon together"></i><span class="nav_label">約團活動</span></a>
				<a class="single" href="/trade"><i class="icon trade"></i><span class="nav_label">交易專區</span></a>
				<a class="single" href="/about"><i class="icon about"></i><span class="nav_label">關於暗盟</span></a>
				<a class="single" href="/bbs" title="前往論壇" target="_blank"><i class="icon bbs"></i><span class="nav_label">論壇</span></a>
				<div class="g_clear"></div>
				</nav>
			</div>
		</div>
		</header>

		<!-- 動態網頁 -->
		<div role="main" class="diy">
			<?php echo $loaded_view ?>
		</div>

		<!-- 頁腳 -->
		<footer>
		<div class="footer">
			執行長: 虎虎(HUhu)、營運經理: 萱萱太太(Cynthia)、助理工程師: 然苦氏太太、美術設計師: 小伊(Ithil)<br />
			我們極度建議您使用<a href="http://www.google.com/chrome/index.html?hl=zh-TW&amp;brand=CHMA&amp;utm_campaign=zh_hk&amp;utm_source=zh-TW-ha-apac-tw-bk&amp;utm_medium=ha" target="_blank">Google Chrome 20+ 瀏覽器</a>來瀏覽本網站。
		</div>
		</footer>

		<div id="metadata-root" data-metadata='<?php echo $loaded_json_metadata ?>'></div>
	</div>
	<script src="/e/m/<?php foreach ( $loaded_js_files as $index => $js_file ) {if ( count( $loaded_js_files )-1 > $index ) {echo $this->config->item( 'js_static_path' ) . "$js_file.js,";}else {echo $this->config->item( 'js_static_path' ) . "$js_file.js";}} ?>"></script>

	<?php foreach ($linked_js_files as $url): ?>
		<script src="<?php echo $url ?>"></script>
	<?php endforeach ?>

	<?php echo file_get_contents(template('d3clan_ga:code')) ?>
</body>
</head>