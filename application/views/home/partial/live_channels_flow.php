<div class="live_list_block" data-explain="直播頻道條目">
	<div class="inner">
		<?php foreach ( $live_channels as $key => $channel ): ?>
			<a class="live_row" href="/lives/channel/<?php echo $channel['sn'] ?>"><?php echo $channel['live_name'] ?> (<?php echo $channel['viewer_count'] ?>)</a>
		<?php endforeach ?>
	</div>
</div>