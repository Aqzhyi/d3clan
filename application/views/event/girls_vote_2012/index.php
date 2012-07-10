<div class="namespace_event_2012_girls_vote">
	<div class="girls_banner">女孩廣告橫幅</div>

	<div class="girls_sponsor">贊助商單位</div>

	<div class="girls_vote_progress">
		<div class="girl_vote_kind_date <?php echo $girls_vote_progress1_active ?>">
			<span class="the_title">氣質系票選</span>
			<span class="the_date">7/21 ~ 7/25</span>
		</div>
		<div class="girl_vote_kind_date <?php echo $girls_vote_progress2_active ?>">
			<span class="the_title">萌系票選</span>
			<span class="the_date">7/26 ~ 7/31</span>
		</div>
		<div class="girl_vote_kind_date <?php echo $girls_vote_progress3_active ?>">
			<span class="the_title">性感系票選</span>
			<span class="the_date">8/1 ~ 8/5</span>
		</div>
		<div class="girl_vote_kind_date <?php echo $girls_vote_progress4_active ?>">
			<span class="the_title">活潑系票選</span>
			<span class="the_date">8/6 ~ 8/10</span>
		</div>
		<div class="the_rule"></div>
	</div>

	<div class="girls_intro">
		<?php echo $this->template->fetch( "event/girls_vote_2012/partial/girls", $girls ); ?>
	</div>
</div>