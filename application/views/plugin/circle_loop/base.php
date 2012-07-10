<div id="circle_loop" class="circle_loop">
	<!-- 輪播按鈕 -->
	<div class="circle_switch_set">
		<?php foreach ($circle_loop_data as $index => $item): ?>
			<?php
			// 第一個顯示"框", 其餘先隱藏"框",等 js 調用.
			if ($index===0) {
				$addClass = "current";
			}
			else {
				$addClass = "";	
			}
			?>
			<div id="switcher" data-index="<?php echo $index ?>" class="switcher <?php echo $addClass ?>">
				<img src="<?php echo $item['img'] ?>" alt="輪播新聞" />
			</div>
		<?php endforeach ?>
	</div>

	<!-- 輪播顯示器視窗 -->
	<div class="circle_windows">
		<?php foreach ($circle_loop_data as $index => $item): ?>
			<?php
			// 第一個顯示, 其餘先隱藏, 等 js 調用.
			if ($index===0) {
				$addClass = "";
			}
			else {
				$addClass = "g_hide";	
			}
			?>
			<div id="windows" data-index="<?php echo $index ?>" class="window <?php echo $addClass ?>">
				<a><img src="<?php echo $item['img'] ?>" alt="輪播新聞" /></a>
				<div class="textarea">
					<div class="title">
						<a href="<?php echo $item['link'] ?>"><?php echo $item['title'] ?></a>
					</div>
					<!-- 空兩格全形空白 -->
					<div class="descr">　　<?php echo $item['descr'] ?></div>
				</div>
			</div>
		<?php endforeach ?>
	</div>
	<div class="g_clear" style="clear: both;"></div>
</div>

