<div class="namespace_vod">

	<div class="player" data-explain="影片播放視窗">
		<?php echo $this->media->embed_vod( array(
			'type'  => $playing[0]['first_video_type'],
			'code'  => $playing[0]['first_video_code'],
			'width' => '100%',
			'height' => '100%',
		) ) ?>
	</div>

	<div class="lasest_vod_at_right" data-explain="最近影片">
		<div class="inner">
			<?php echo $this->load->view( 'vod/partial/vod_list', array( 'layout' => 'LR', 'videos' => $videos_right_list ) ) ?>
		</div>
	</div>

	<div class="search">
		<form action="/bbs/search.php?searchsubmit=yes" method="POST" target="_blank">
			<input type="hidden" name="mod" id="scbar_mod" value="curforum">
			<input type="hidden" name="srhfid" value="56">
			<input type="hidden" name="srchtype" value="title">
			<input type="hidden" name="srhlocality" value="forum::forumdisplay">
			<input class="keyword" type="text" name="srchtxt" value="" />
		</form>
	</div>

	<div class="g_clear"></div>

	<div class="line01"></div>
	<div class="lasest_vod_at_bottom g_clear" data-explain="最近影片">
		<div class="inner">
			<?php echo $this->load->view( 'vod/partial/vod_list', array( 'layout' => 'TB', 'videos' => $videos_bottom_list ) ) ?>
		</div>
	</div>
	<div class="line02"></div>
</div>