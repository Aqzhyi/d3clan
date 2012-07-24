<div class="namespace_together">
	<div class="well">
		<h2>約團專區</h2>
		<a href="/bbs/forum.php?mod=forumdisplay&amp;fid=39">立刻去約團</a>

		<div class="well">
			<?php foreach ($news_flow as $key => $news): ?>
				<div>
					<span class="label label-info">[<?php echo date('Y-m-d', $news['dateline']) ?>]</span>
					<?php echo $this->discuzx->alink_to_bbs(array(
						'tid' => $news['tid'],
						'text' => $news['subject'],
					)) ?>
				</div>
			<?php endforeach ?>
		</div>
	</div>
</div>