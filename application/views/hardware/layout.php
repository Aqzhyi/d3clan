<div class="namespace_hardware">
	
	<div class="header">
		<?php echo $this->template->fetch( 'hardware/partial/circle_news', $circle_news ) ?>
		<?php echo $this->template->fetch( 'hardware/partial/news_class', $news_class ) ?>
		
		<div class="search">
			<?php echo $this->discuzx->search_in_bbs(array(
				'srchfid' => array( '44','45','46','47' ),
			)) ?>
		</div>
	</div>

	<div class="body">
		<div class="class_news_flow">
			<div class="header">最新滑鼠<div class="hardware_AD"></div></div>
			<?php echo $this->template->fetch( 'hardware/partial/news_flow', $mouse_flow ) ?>
		</div>
		
		<div class="class_news_flow">
			<div class="header">最新鍵盤<div class="hardware_AD"></div></div>
			<?php echo $this->template->fetch( 'hardware/partial/news_flow', $keyboard_flow ) ?>
		</div>

		<div class="class_news_flow">
			<div class="header">最新耳機<div class="hardware_AD"></div></div>
			<?php echo $this->template->fetch( 'hardware/partial/news_flow', $headphone_flow ) ?>
		</div>

		<div class="class_news_flow">
			<div class="header">硬體相關<div class="hardware_AD"></div></div>
			<?php echo $this->template->fetch( 'hardware/partial/news_flow', $else_flow ) ?>
		</div>
	</div>

</div>