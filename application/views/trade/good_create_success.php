<?php 
	$posted_thread = "<a target='_blank' href='/bbs/forum.php?mod=viewthread&tid={$tid}'>[交易訊息]</a>";
?>

<div class="well">
	<!-- identifed_item -->
	<!-- tid -->
	<!-- file_info -->
	<div class="alert alert-success">
		<h4>您的圖片已上傳成功！</h4>
		請立即前往由系統自動為您發佈的 <?php echo $posted_thread ?> 進行詳細編輯。
	</div>
	
	<div class="well">
		<p class="label label-success">您的圖片</p>
		<p><img src="<?php echo preg_replace('@.*(\/bbs\/data\/attachment\/forum\/\d{6}\/\d{1,2}\/\w+\.\w+)@', '$1', $file_info['full_path'] ) ?>" alt="已上傳的商品圖片"></p>
	</div>

	<div class="well">
		<p class="label label-success">您的辨識結果</p>
		<div class="alert alert-warning">
			辨識結果僅供參考，仍需玩家進入 <?php echo $posted_thread ?> 進行最後的校對與編輯，才能使其商品在搜索系統中，正確地被其他玩家搜尋。
		</div>
		<div class="well">
		<?php foreach ($identifed_item as $key => $line): ?>
			<p>
				<?php echo $line ?>
			</p>
		<?php endforeach ?>
		</div>
	</div>

	<div class="alert alert-success">
		<h4>您的圖片已上傳成功！</h4>
		請立即前往由系統自動為您發佈的 <?php echo $posted_thread ?> 進行詳細編輯。
	</div>
</div>