<div class="namespace_event_2012_girls_vote">

	<div class="girls_topin"></div>

	<div class="girls_banner" id="girls_banner">
		<div id="item" class="item current"><img src="/static/img/event/girls_vote_2012/dgirl-b.jpg" alt=""></div>
		<div id="item" class="item"><?php echo $this->media->embed_flash( array(
			'height' => '400',
			'src' => '\static\file\common\ad\960x400-DGirl-intel.swf',
		) ) ?></div>
		<div id="item" class="item"><?php echo $this->media->embed_flash( array(
			'height' => '400',
			'src' => '\static\file\common\ad\960x400-DGirl.swf',
		) ) ?></div>
	</div>

	<div class="girls_sponsor">
		<img src="/static/img/event/girls_vote_2012/girls_sponsor.jpg" alt="">
	</div>

	<div class="girls_vote_progress">
		<div id="the_rule" class="the_rule">
			<div class="hide" id="template"><?php echo $this->template->fetch( 'event/girls_vote_2012/partial/rule' ) ?></div>
		</div>
	</div>

	<div class="girls_intro">
		<?php echo $this->template->fetch( "event/girls_vote_2012/partial/girls", $girls ); ?>
	</div>
</div>