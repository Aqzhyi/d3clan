<div class="namespace_admin_news_bot_latest_vod">
	<!-- 導航列 -->
	<?php echo $this->load->view( 'admin/partial/navbar' ) ?>

	<!-- 新聞爬蟲導航列 -->
	<?php echo $this->load->view( 'admin/partial/news-bot-navbar' ) ?>
	
	<table class="table">
		<thead>
			<tr>
				<th>日期</th>
				<th>原文地址</th>
				<th>預覽/擷取/狀態</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($fetch_rows as $key => $row): ?>
				<tr>
					<td><?php echo $row['date'] ?></td>
					<td>
						<?php echo "<a target='_blank' href='{$row['href']}'>{$row['text']}</a>" ?>
					</td>
					<td>
						<span class="btn btn-mini disabled"><s>預覽</s></span>
						<span class="btn btn-mini disabled"><s>擷取</s></span>
						<?php if ($row['fetched']): ?>
							<?php echo $row['bbs_link'] ?>
						<?php endif ?>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>