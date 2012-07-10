<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>

<script src="js/common.js" type="text/javascript"></script>

<div class="container">
	<?php if($operate == 'list') { ?>
		<h3 class="marginbot">
			<a href="admin.php?m=db&a=ls&o=export" class="sgbtn">數據備份</a>
			數據恢復
		</h3>
		<div class="note fixwidthdec">
			<p class="i">根據備份日期選擇要恢復的備份，點擊「詳情」進入之後選擇要恢復的應用備份</p>
		</div>
		<div class="mainbox">
			<form id="theform">
				<table class="datalist" onmouseover="addMouseEvent(this);">
					<tr>
						<th nowrap="nowrap"><input type="checkbox" name="chkall" id="chkall" onclick="checkall('operate[]')" class="checkbox" /><label for="chkall">刪除</label></th>
						<th nowrap="nowrap">備份所在目錄</th>
						<th nowrap="nowrap">備份日期</th>
						<th nowrap="nowrap">操作</th>
						<th nowrap="nowrap">&nbsp;</th>
						<th nowrap="nowrap">&nbsp;</th>
					</tr>
					<?php foreach((array)$baklist as $bak) {?>
						<tr>
							<td width="50"><input type="checkbox" name="operate[]" value="<?php echo $bak['name'];?>" class="checkbox" /></td>
							<td width="200"><a href="admin.php?m=db&a=ls&o=view&dir=<?php echo $bak['name'];?>"><?php echo $bak['name'];?></a></td>
							<td width="120"><?php echo $bak['date'];?></td>
							<td><a href="admin.php?m=db&a=ls&o=view&dir=<?php echo $bak['name'];?>">詳情</a></td>
							<td id="db_operate_<?php echo $bak['name'];?>"></td>
							<td><iframe id="operate_iframe_<?php echo $bak['name'];?>" style="display:none" width="0" height="0"></iframe></td>
						</tr>
					<?php } ?>
					<tr class="nobg">
						<td colspan="6"><input type="button" value="提 交" onclick="db_delete($('theform'))" class="btn" /></td>
					</tr>
				</table>
			</form>
		</div>
	<?php } elseif($operate == 'view') { ?>
		<h3 class="marginbot">
			<a href="admin.php?m=db&a=ls&o=export" class="sgbtn">數據備份</a>
			數據恢復
		</h3>
		<div class="note fixwidthdec">
			<p class="i">在需要恢復的應用前面勾選，之後點擊「提交」按鈕即可恢復備份數據</p>
		</div>
		<div class="mainbox">
			<form id="theform">
			<table class="datalist" onmouseover="addMouseEvent(this);">
				<tr>
					<th nowrap="nowrap"><input type="checkbox" name="chkall" id="chkall" onclick="checkall('operate[]')" class="checkbox" /><label for="chkall">導入</label></th>
					<th nowrap="nowrap">ID</th>
					<th nowrap="nowrap">應用名稱</th>
					<th nowrap="nowrap">應用的主 URL</th>
					<th nowrap="nowrap">&nbsp;</th>
					<th nowrap="nowrap">&nbsp;</th>
				</tr>
				<tr>
					<td width="50"><input type="checkbox" name="operate_uc" class="checkbox" /></td>
					<td width="35"></td>
					<td><strong>UCenter</strong></td>
					<td></td>
					<td id="db_operate_0"><img src="images/correct.gif" border="0" class="statimg" /><span class="green">備份存在</span></td>
					<td><iframe id="operate_iframe_0" style="display:none" width="0" height="0"></iframe></td>
				</tr>
				<?php foreach((array)$applist as $app) {?>
					<tr>
						<td width="50"><input type="checkbox" name="operate[]" value="<?php echo $app['appid'];?>" class="checkbox" /></td>
						<td width="35"><?php echo $app['appid'];?></td>
						<td width="160"><a href="admin.php?m=app&a=detail&appid=<?php echo $app['appid'];?>"><strong><?php echo $app['name'];?></strong></a></td>
						<td><a href="<?php echo $app['url'];?>" target="_blank"><?php echo $app['url'];?></a></td>
						<td id="db_operate_<?php echo $app['appid'];?>"></td>
						<td><iframe id="operate_iframe_<?php echo $app['appid'];?>" src="admin.php?m=db&a=ls&o=ping&appid=<?php echo $app['appid'];?>&dir=<?php echo $dir;?>" style="display:none" width="0" height="0"></iframe></td>
					</tr>
				<?php } ?>
				<tr class="nobg">
					<td colspan="6"><input type="button" value="提 交" onclick="db_operate($('theform'), 'import')" class="btn" /></td>
				</tr>
			</table>
			</form>
		</div>
	<?php } else { ?>
		<h3 class="marginbot">
			數據備份
			<a href="admin.php?m=db&a=ls&o=list" class="sgbtn">數據恢復</a>
		</h3>
		<div class="mainbox">
			<form id="theform">
			<table class="datalist" onmouseover="addMouseEvent(this);">
				<tr>
					<th nowrap="nowrap"><input type="checkbox" name="chkall" id="chkall" checked="checked" onclick="checkall('operate[]')" class="checkbox" /><label for="chkall">數據備份</label></th>
					<th nowrap="nowrap">ID</th>
					<th nowrap="nowrap">應用名稱</th>
					<th nowrap="nowrap">應用的主 URL</th>
					<th nowrap="nowrap">&nbsp;</th>
					<th nowrap="nowrap">&nbsp;</th>
				</tr>
				<tr>
					<td width="50"><input type="checkbox" name="operate_uc" disabled="disabled" checked="checked" class="checkbox" /></td>
					<td width="35"></td>
					<td><strong>UCenter</strong></td>
					<td></td>
					<td id="db_operate_0"></td>
					<td><iframe id="operate_iframe_0" style="display:none" width="0" height="0"></iframe></td>
				</tr>
				<?php foreach((array)$applist as $app) {?>
					<tr>
						<td width="50"><input type="checkbox" name="operate[]" value="<?php echo $app['appid'];?>" checked="checked" class="checkbox" /></td>
						<td width="35"><?php echo $app['appid'];?></td>
						<td width="160"><a href="admin.php?m=app&a=detail&appid=<?php echo $app['appid'];?>"><strong><?php echo $app['name'];?></strong></a></td>
						<td><a href="<?php echo $app['url'];?>" target="_blank"><?php echo $app['url'];?></a></td>
						<td id="db_operate_<?php echo $app['appid'];?>"></td>
						<td><iframe id="operate_iframe_<?php echo $app['appid'];?>" style="display:none" width="0" height="0"></iframe></td>
					</tr>
				<?php } ?>
				<tr class="nobg">
					<td colspan="6"><input type="button" value="提 交" onclick="db_operate($('theform'), 'export')" class="btn" /></td>
				</tr>
			</table>
			</form>
		</div>
	<?php } ?>
</div>

<script type="text/javascript">
var import_status = new Array();
function db_delete(theform) {
	var lang_tips = '開始刪除備份數據，請等待，請勿關閉瀏覽器';
	if(!confirm('刪除數據庫備份會同時刪除UCenter 下所有應用的同目錄下的備份，您確定要刪除嗎？')) {
		return;
	}
	for(i = 0; theform[i] != null; i++) {
		ele = theform[i];
		if(/^operate\[/.test(ele.name) && ele.type == "checkbox" && ele.checked) {
			show_status(ele.value, lang_tips);
			$('operate_iframe_'+ele.value).src = 'admin.php?m=db&a=delete&backupdir='+ele.value;
		}
	}
}

function db_operate(theform, operate) {
	operate = operate == 'import' ? 'import' : 'export';
	if(operate == 'export') {
		var lang_tips = '開始備份數據，請等待，請勿關閉瀏覽器';
	} else {
		if(!confirm('導入備份數據會覆蓋現有的數據，您確定導入嗎？')) {
			return;
		}
		if(theform.operate_uc.checked && !confirm('導入備份數據將會覆蓋現有用戶中心的數據，您確定導入嗎？')) {
			return;
		}
		var lang_tips = '開始恢複數據，請等待，請勿關閉瀏覽器';
	}

	if(theform.operate_uc.checked) {
		show_status(0, lang_tips);
		$('operate_iframe_0').src = 'admin.php?m=db&a=operate&t='+operate+'&appid=0&backupdir=<?php echo $dir;?>';
	}
	for(i = 0; theform[i] != null; i++) {
		ele = theform[i];
		if(/^operate\[\]$/.test(ele.name) && ele.type == "checkbox" && ele.checked) {
			if(operate != 'import' || import_status[ele.value] != false) {
				show_status(ele.value, lang_tips);
				$('operate_iframe_'+ele.value).src = 'admin.php?m=db&a=operate&t='+operate+'&appid='+ele.value+'&backupdir=<?php echo $dir;?>';
			}
		}
	}
}

function show_status(extid, msg) {
	var o = $('db_operate_'+extid);
	o.innerHTML = msg;
}
</script>

<?php include $this->gettpl('footer');?>