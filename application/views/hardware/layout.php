<div class="namespace_hardware">
	
	<div class="header">
		<div class="banner"></div>

		<div class="news_flow"></div>
	</div>

	<div class="body">
		<div class="class_news_flow">
			<div class="header">最新滑鼠</div>
			<?php echo $this->template->fetch( 'hardware/partial/news_flow', $mouse_flow ) ?>
		</div>
		
		<div class="class_news_flow">
			<div class="header">最新鍵盤</div>
			<?php echo $this->template->fetch( 'hardware/partial/news_flow', $keyboard_flow ) ?>
		</div>

		<div class="class_news_flow">
			<div class="header">最新耳機</div>
			<?php echo $this->template->fetch( 'hardware/partial/news_flow', $headphone_flow ) ?>
		</div>

		<div class="class_news_flow">
			<div class="header">硬體相關</div>
			<?php echo $this->template->fetch( 'hardware/partial/news_flow', $else_flow ) ?>
		</div>
	</div>

</div>