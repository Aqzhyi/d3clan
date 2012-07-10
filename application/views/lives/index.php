<div class="namespace_lives">
	<div class="lives_banner">
		<!-- 橫廣告區 -->
		<div class="lives_ad">
			<!-- 廣告擺這裡面 -->
		</div>
	</div>

	<div class="lives_menu">
		
	</div>

	<div class="lives_channels">
		<div class="inner_top"></div>
		<div class="inner">
			<div class="lives_kind">
				<div class="lives_title">台灣地區</div>
				<div class="padding">
					<?php echo $this->template->fetch( 'lives/partial/channels', $live_channels['taiwan'] ) ?>
				</div>
			</div>
			<div class="lives_kind">
				<div class="lives_title">其它地區</div>
				<div class="padding">
					<?php echo $this->template->fetch( 'lives/partial/channels', $live_channels['else'] ) ?>
				</div>
			</div>
		</div>
		<div class="inner_bottom"></div>
	</div>
</div>