<?php $videos = ( ! empty( $videos ) ) ? $videos : array();?>

<?php foreach ($videos as $index => $vod): ?>

<?php 
$subject_thumb = "<td class='subject_thumb'>".$this->discuzx->alink_to_bbs( array(
						'tid' => $vod['tid'],
						'text' => $vod['subject_thumb'],
					) )."</td>";?>

	<table class="single">
		<tr>
			<td class="thumb_wrapper">
				<a href="/vod/<?php echo $vod['first_video_type'] ?>/<?php echo $vod['first_video_code'] ?>" class="thumb" data-media-type="<?php echo $vod['first_video_type'] ?>" data-subject="<?php echo $vod['subject'] ?>" style="background-image: url(<?php echo $vod['first_video_thumb'] ?>);">
					<i class="g_vod_play_icon_hover"></i>
				</a>
			</td>
			<?php if ($layout==='LR'): ?>
				<!-- 圖左字右排版 -->
				<?php echo $subject_thumb ?>
			<?php endif ?>
		</tr>
		<?php if ($layout==='TB'): ?>
			<!-- 圖上字下排版 -->
			<tr>
				<?php echo $subject_thumb ?>
			</tr>
		<?php endif ?>
	</table>
<?php endforeach ?>
