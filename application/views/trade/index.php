<div class="namespace_trade well">
	<h2>交易專區</h2>
	<!-- <a href="/trade/assist-sell">立刻出售商品</a> -->
	<a href="/bbs/forum.php?mod=forumdisplay&fid=40">立刻出售商品</a>

	<div class="well">
		<?php foreach ($news_flow as $key => $news): ?>
			<div>
				<?php echo $this->discuzx->alink_to_bbs(array(
					'tid' => $news['tid'],
					'text' => $news['subject'],
				)) ?>
			</div>
		<?php endforeach ?>
	</div>
</div>