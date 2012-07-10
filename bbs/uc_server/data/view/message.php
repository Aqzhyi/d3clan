<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<?php if(empty($_REQUEST['inajax'])) { ?>
	<div class="container">
		<div class="ajax rtninfo">
			<div class="ajaxbg">
				<h4>提示信息:</h4>
				<p><?php echo $message;?></p>
				<?php if($redirect == 'BACK') { ?>
					<p><a href="###" onclick="history.back();">點擊這裡返回</a></p>
				<?php } elseif($redirect) { ?>
					<p><a href="<?php echo $redirect;?>">頁面將在 3 秒後自動跳轉到下一頁，點擊這裡直接跳轉...</a></p>
					<script type="text/javascript">
					function redirect(url, time) {
						setTimeout("window.location='" + url + "'", time * 1000);
					}
					redirect('<?php echo $redirect;?>', 3);
					</script>
				<?php } ?>
			</div>
		</div>
	</div>
<?php } else { ?>
	<?php echo $message;?>
<?php } ?>
<?php include $this->gettpl('footer');?>