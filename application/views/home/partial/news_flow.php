<div id="news_block" class="news_block" data-explain="流水新聞分類條目">
	<div class="caption">
		<img src="static/img/home/index/news.png" alt="">
	</div>
	<div class="inner">
		<div id="sub_menu" class="sub_menu g_clear">
			<?php foreach ( $news_cata as $cata_name => $cata_val ): ?>
				<span data-kind="<?php echo $cata_name ?>" class="sub_menu_btn"><?php echo $cata_val ?></span>
			<?php endforeach ?>
		</div>

		<div id="news_flows" class="news_flows">
			<div id="loading" class="g_loading"></div>

			<?php foreach ( $news_flows as $flow_kind => $flow ): ?>
				<div data-kind="<?php echo $flow_kind ?>" class="news_flow">
					<?php foreach ( $flow as $index => $news ): ?>
						<a target="_blank" href="/bbs/forum.php?mod=viewthread&tid=<?php echo $news['tid'] ?>" class="news_row">
							<span style="color: white;"><?php echo "[{$news['name']}]" ?></span>
							<?php echo $news['subject'] ?>
						</a>
					<?php endforeach ?>
				</div>
			<?php endforeach ?>
		</div>
	</div>
</div>