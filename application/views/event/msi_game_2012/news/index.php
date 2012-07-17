<div class="well">
	<?php foreach ($news_flow as $key => $news): ?>
	<div>
		<span class="label label-info">[<?php echo date('Y-m-d', $news['dateline']) ?>]</span>
		<?php echo $this->discuzx->alink_to_bbs( array(
			'tid'  => $news['tid'],
			'text' => $news['subject'],
		) ) ?>
	</div>
	<?php endforeach ?>
</div>