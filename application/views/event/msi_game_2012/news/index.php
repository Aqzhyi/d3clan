<?php foreach ($news_flow as $key => $news): ?>
	<div>
		<?php echo $this->discuzx->alink_to_bbs( array(
			'tid'  => $news['tid'],
			'text' => $news['subject'],
		) ) ?>
	</div>
<?php endforeach ?>