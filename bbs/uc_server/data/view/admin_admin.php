<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>

<?php if($a == 'ls') { ?>

	<script src="js/common.js" type="text/javascript"></script>
	<script src="js/calendar.js" type="text/javascript"></script>
	<script type="text/javascript">
		function switchbtn(btn) {
			$('addadmindiv').className = btn == 'addadmin' ? 'tabcontentcur' : '' ;
			$('editpwdiv').className = btn == 'addadmin' ? '' : 'tabcontentcur';

			$('addadmin').className = btn == 'addadmin' ? 'tabcurrent' : '';
			$('editpw').className = btn == 'addadmin' ? '' : 'tabcurrent';

			$('addadmindiv').style.display = btn == 'addadmin' ? '' : 'none';
			$('editpwdiv').style.display = btn == 'addadmin' ? 'none' : '';
		}
		function chkeditpw(theform) {
			if(theform.oldpw.value == '') {
				alert('請輸入原創始人密碼');
				theform.oldpw.focus();
				return false;
			}
			if(theform.newpw.value == '') {
				alert('請輸入新密碼');
				theform.newpw.focus();
				return false;
			}
			if(theform.newpw2.value == '') {
				alert('請重複輸入新密碼');
				theform.newpw2.focus();
				return false;
			}
			if(theform.newpw.value != theform.newpw2.value) {
				alert('兩次輸入的密碼不一致');
				theform.newpw2.focus();
				return false;
			}
			if(theform.newpw.value.length < 6 && !confirm('您的密碼太短，可能會不安全，您確定設定此密碼嗎？')) {
				theform.newpw.focus();
				return false;
			}
			return true;
		}
	</script>

	<div class="container">
		<?php if($status) { ?>
			<div class="<?php if($status > 0) { ?>correctmsg<?php } else { ?>errormsg<?php } ?>">
				<p>
				<?php if($status == 1) { ?> 添加 <?php echo $addname;?> 為管理員成功
				<?php } elseif($status == -1) { ?> 添加 <?php echo $addname;?> 為管理員成功
				<?php } elseif($status == -2) { ?> 添加 <?php echo $addname;?> 為管理員失敗
				<?php } elseif($status == -3) { ?>無此用戶: <?php echo $addname;?>
				<?php } elseif($status == -4) { ?> /data/config.inc.php 文件不可寫
				<?php } elseif($status == -5) { ?> 創始人賬號密碼輸入錯誤
				<?php } elseif($status == -6) { ?> 兩次輸入的密碼不一致
				<?php } elseif($status == 2) { ?> 創始人賬號密碼修改成功
				<?php } ?>
				</p>
			</div>
		<?php } ?>
		<div class="hastabmenu" style="height:175px;">
			<ul class="tabmenu">
				<li id="addadmin" class="tabcurrent"><a href="#" onclick="switchbtn('addadmin');">添加管理員</a></li>
				<?php if($user['isfounder']) { ?><li id="editpw"><a href="#" onclick="switchbtn('editpw');">修改創始人密碼</a></li><?php } ?>
			</ul>
			<div id="addadmindiv" class="tabcontentcur">
				<form action="admin.php?m=admin&a=ls" method="post">
				<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
				<table class="dbtb">
					<tr>
						<td class="tbtitle">用戶名:</td>
						<td><input type="text" name="addname" class="txt" /></td>
					</tr>
					<tr>
						<td valign="top" class="tbtitle">權　限:</td>
						<td>
							<ul class="dblist">
								<li><input type="checkbox" name="allowadminsetting" value="1" class="checkbox" checked="checked" />允許改變設置</li>
								<li><input type="checkbox" name="allowadminapp" value="1" class="checkbox" />允許管理應用</li>
								<li><input type="checkbox" name="allowadminuser" value="1" class="checkbox" />允許管理用戶</li>
								<li><input type="checkbox" name="allowadminbadword" value="1" class="checkbox" checked="checked" />允許管理詞語過濾</li>
								<li><input type="checkbox" name="allowadmintag" value="1" class="checkbox" checked="checked" />允許管理TAG</li>
								<li><input type="checkbox" name="allowadminpm" value="1" class="checkbox" checked="checked" />允許管理短消息</li>
								<li><input type="checkbox" name="allowadmincredits" value="1" class="checkbox" checked="checked" />允許管理積分</li>
								<li><input type="checkbox" name="allowadmindomain" value="1" class="checkbox" checked="checked" />允許管理域名解析</li>
								<li><input type="checkbox" name="allowadmindb" value="1" class="checkbox" />允許管理數據</li>
								<li><input type="checkbox" name="allowadminnote" value="1" class="checkbox" checked="checked" />允許管理數據列表</li>
								<li><input type="checkbox" name="allowadmincache" value="1" class="checkbox" checked="checked" />允許管理緩存</li>
								<li><input type="checkbox" name="allowadminlog" value="1" class="checkbox" checked="checked" />允許查看日誌</li>
							</ul>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<input type="submit" name="addadmin" value="提 交" class="btn" />
						</td>
					</tr>
				</table>
				</form>
			</div>
			<?php if($user['isfounder']) { ?>
			<div id="editpwdiv" class="tabcontent" style="display:none;">
				<form action="admin.php?m=admin&a=ls" onsubmit="return chkeditpw(this)" method="post">
				<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
				<table class="dbtb" style="height:123px;">
					<tr>
						<td class="tbtitle">舊密碼:</td>
						<td><input type="password" name="oldpw" class="txt" /></td>
					</tr>
					<tr>
						<td class="tbtitle">新密碼:</td>
						<td><input type="password" name="newpw" class="txt" /></td>
					</tr>
					<tr>
						<td class="tbtitle">重複新密碼:</td>
						<td><input type="password" name="newpw2" class="txt" /></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<input type="submit" name="editpwsubmit" value="提 交" class="btn" />
						</td>
					</tr>
				</table>
				</form>
			</div>
			<?php } ?>
		</div>
		<h3>管理員列表</h3>
		<div class="mainbox">
			<?php if($userlist) { ?>
				<form action="admin.php?m=admin&a=ls" onsubmit="return confirm('您確定刪除嗎？');" method="post">
				<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
				<table class="datalist fixwidth" onmouseover="addMouseEvent(this);">
					<tr>
						<th><input type="checkbox" name="chkall" id="chkall" onclick="checkall('delete[]')" value="1" class="checkbox" /><label for="chkall">刪除</label></th>
						<th>用戶名</th>
						<th>Email</th>
						<th>註冊日期</th>
						<th>註冊IP</th>
						<th>資料</th>
						<th>權限</th>
					</tr>
					<?php foreach((array)$userlist as $user) {?>
						<tr>
							<td class="option"><input type="checkbox" name="delete[]" value="<?php echo $user['uid'];?>" value="1" class="checkbox" /></td>
							<td class="username"><?php echo $user['username'];?></td>
							<td><?php echo $user['email'];?></td>
							<td class="date"><?php echo $user['regdate'];?></td>
							<td class="ip"><?php echo $user['regip'];?></td>
							<td class="ip"><a href="admin.php?m=user&a=edit&uid=<?php echo $user['uid'];?>&fromadmin=yes">資料</a></td>
							<td class="ip"><a href="admin.php?m=admin&a=edit&uid=<?php echo $user['uid'];?>">權限</a></td>
						</tr>
					<?php } ?>
					<tr class="nobg">
						<td><input type="submit" value="提 交" class="btn" /></td>
						<td class="tdpage" colspan="4"><?php echo $multipage;?></td>
					</tr>
				</table>
				</form>
			<?php } else { ?>
				<div class="note">
					<p class="i">目前沒有相關記錄!</p>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php if($_POST['editpwsubmit']) { ?>
		<script type="text/javascript">
		switchbtn('editpw');
		</script>
	<?php } else { ?>
		<script type="text/javascript">
		switchbtn('addadmin');
		</script>
	<?php } ?>

<?php } else { ?>
	<div class="container">
		<h3 class="marginbot">編輯管理員權限<a href="admin.php?m=admin&a=ls" class="sgbtn">返回管理員列表</a></h3>
		<?php if($status == 1) { ?>
			<div class="correctmsg"><p>編輯管理員權限成功</p></div>
		<?php } elseif($status == -1) { ?>
			<div class="correctmsg"><p>編輯管理員權限失敗</p></div>
		<?php } else { ?>
			<div class="note">請謹慎開放「管理應用」，「管理用戶」、「管理數據」權限</div>
		<?php } ?>
		<div class="mainbox">
			<form action="admin.php?m=admin&a=edit&uid=<?php echo $uid;?>" method="post">
			<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
				<table class="opt">
					<tr>
						<th>管理員 <?php echo $admin['username'];?>:</th>
					</tr>
					<tr>
						<td>
							<ul>
								<li><input type="checkbox" name="allowadminsetting" value="1" class="checkbox" <?php if($admin['allowadminsetting']) { ?> checked="checked" <?php } ?>/>允許改變設置</li>
								<li><input type="checkbox" name="allowadminapp" value="1" class="checkbox" <?php if($admin['allowadminapp']) { ?> checked="checked" <?php } ?>/>允許管理應用</li>
								<li><input type="checkbox" name="allowadminuser" value="1" class="checkbox" <?php if($admin['allowadminuser']) { ?> checked="checked" <?php } ?>/>允許管理用戶</li>
								<li><input type="checkbox" name="allowadminbadword" value="1" class="checkbox" <?php if($admin['allowadminbadword']) { ?> checked="checked" <?php } ?>/>允許管理詞語過濾</li>
								<li><input type="checkbox" name="allowadmintag" value="1" class="checkbox" <?php if($admin['allowadmintag']) { ?> checked="checked" <?php } ?>/>允許管理TAG</li>
								<li><input type="checkbox" name="allowadminpm" value="1" class="checkbox" <?php if($admin['allowadminpm']) { ?> checked="checked" <?php } ?>/>允許管理短消息</li>
								<li><input type="checkbox" name="allowadmincredits" value="1" class="checkbox" <?php if($admin['allowadmincredits']) { ?> checked="checked" <?php } ?>/>允許管理積分</li>
								<li><input type="checkbox" name="allowadmindomain" value="1" class="checkbox" <?php if($admin['allowadmindomain']) { ?> checked="checked" <?php } ?>/>允許管理域名解析</li>
								<li><input type="checkbox" name="allowadmindb" value="1" class="checkbox" <?php if($admin['allowadmindb']) { ?> checked="checked" <?php } ?>/>允許管理數據</li>
								<li><input type="checkbox" name="allowadminnote" value="1" class="checkbox" <?php if($admin['allowadminnote']) { ?> checked="checked" <?php } ?>/>允許管理數據列表</li>
								<li><input type="checkbox" name="allowadmincache" value="1" class="checkbox" <?php if($admin['allowadmincache']) { ?> checked="checked" <?php } ?>/>允許管理緩存</li>
								<li><input type="checkbox" name="allowadminlog" value="1" class="checkbox" <?php if($admin['allowadminlog']) { ?> checked="checked" <?php } ?>/>允許查看日誌</li>
							</ul>
						</td>
					</tr>
				</table>
				<div class="opt"><input type="submit" name="submit" value=" 提 交 " class="btn" tabindex="3" /></div>
			</form>
		</div>
	</div>

<?php } ?>

<?php include $this->gettpl('footer');?>