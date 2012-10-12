jQuery(function(){
	var $root = jQuery('#vote-root');
	
	$root.find('[data-cmd=vote_him]').on('click', function(e) {
		var $sf = jQuery(this);
		var $btn_parent = $sf.parent();

		jQuery.ajax({
			context    : this,
			url        : '/event/2012-amd/ajax/vote_player',
			type       : 'post',
			dataType   : 'json',
			data       : {
				vote_to: $sf.obj().data('id')
			},
			beforeSend : function(jqXHR, settings) {
				$btn_parent.html('<span style="color: orange; font-size: 24px;">投票中...</span>');
			},
			success    : function(data, textStatus, jqXHR) {
				if (data.success===true) {
					$btn_parent.html('<span style="color: #25DB25; font-size: 24px;">投票完成</span>');
				}
				else {
					var no_login = jQuery(data.error_msg).filter(function() {return this == '尚未登入'; }).length;

					if (no_login) {
						$btn_parent.html('<a target="_blank" href="/bbs/member.php?mod=logging&amp;action=login" class="btn btn-danger">點我註冊登入<br>或透過FB登入</a>');
					}
					else {
						$btn_parent.html('<span style="color: red; font-size: 24px;">'+data.msg+'</span>');
					}
				}
			},
			complete   : function(jqXHR, textStatus) {  }
		});
	});
});