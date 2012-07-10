<div class="well">
	<div class="alert alert-info">
		<h4>在 <?php echo $this->config->item('site_name') ?> 刊登虛擬遊戲寶物出售</h4>
		<p>本站提供一個給所有註冊玩家刊登遊戲虛擬寶物的開放空間，玩家有兩種方式能夠將自己的寶物，刊登自本站。</p>
		<p>1. 直接刊登: 直接至 [論壇]-[交易專區] 發表一則 <a href="/bbs/forum.php?mod=post&action=newthread&fid=40&special=2" target="_blank">[出售商品]</a> 的帖子。全程自行編輯與上傳圖片。</p>
		<hr />
		<p>2. 由本站提供的 [快捷協助刊登] 協助玩家刊登寶物出售訊息。本服務會自動根據玩家所上傳的圖片，自動辨識寶物的屬性，節省玩家在填寫商品屬性的時間。</p>
		<div class="label label-warning">[快捷協助刊登] 不保證能夠完整且正確地辨識屬性，玩家仍需自行添修辨識結果，才能有效地使其他買家能夠搜尋其商品。</div>
		<p></p>
		<div class="label label-warning">[快捷協助刊登] 最後的結果與 [直接刊登] 方法相同。</div>
	</div>
		<?php echo form_open_multipart('trade/good/create', array('class'=>'form well')) ?>
			<legend>快捷協助刊登</legend>
			<input type="file" name="userfile" size="30" />
			<input type="submit" value="上傳商品圖片" />
			<div class="alert alert-info">由於[快捷協助刊登]會自動辨識商品屬性，因此請耐心等候上傳結果。</div>
		<?php echo form_close() ?>
	</div>
</div>