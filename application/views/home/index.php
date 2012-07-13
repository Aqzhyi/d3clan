<div class="namespace_home">
	<!-- main上半 -->
	<div class="top_block">
		<!-- plugin/circle_loop/base上半輪播 -->
		<?php echo $this->load->view( 'plugin/circle_loop/base', $circle_loop ) ?>

		<!-- div.recommend_videos[data-explain=上半精選VOD] -->
		<div class="recommend_videos" data-explain="上半精選VOD">
			<div class="inner">
				<?php echo $this->load->view( 'vod/partial/vod_list', array( 'layout' => 'TB', 'videos' => $videos ) ) ?>
				<div class="g_clear"></div>
				<a class="more_link" href="/vod">點這裡看更多VOD...</a>
			</div>
		</div>
	</div>

	<!-- main中部 -->
	<div id="middle_block" class="middle_block">
		<!-- div#news_block.news_block[data-explain=流水新聞分類條目] -->
		<?php echo $this->template->fetch( 'home/partial/news_flow', array( 'news_flows' => $news_flows, 'news_cata' => $news_cata ) ) ?>

		<!-- div.live_list_block[data-explain=直播頻道條目] -->
		<?php echo $this->template->fetch( 'home/partial/live_channels_flow', $live_channels ) ?>
	</div>
	<div class="g_clear"></div>
	
	<!-- main下半 -->
</div>
