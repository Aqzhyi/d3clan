jQuery(function(){
	var $root = jQuery('[data-root="/event/2012-amd/lucky_user"]');

	$root.find('[data-cmd=get_lucky_user]').on('click', function(e) {
		// if (confirm('確定要抽取嗎？')) {
			var $sf = jQuery(this);
			var $obj = $sf.obj();
			var $users = $obj.find('[data-user-id]');

			var random = random = Math.floor( Math.random() * ($users.length) );
			if ($users.eq(random).attr('data-congratulations')==='t') {
				if ($users.siblings('[data-congratulations=f]').length===0) {
					$sf.addClass('btn-danger').html('已抽完....');
					return;
				}
				$sf.trigger('click');
				return;
			}

			$sf.addClass('disabled').attr('disabled', true).css('cursor', 'wait').html('抽取中....');

			setTimeout(function() {
				var $photo = jQuery('<img>', {
					src: 'http://d3clan.tw/bbs/uc_server/avatar.php?uid='+$users.eq(random).data('user-id')+'&size=small'
				});
				$users.eq(random).prepend($photo).prepend('[中] ').css({backgroundColor: '#596314'});
				$users.eq(random).attr('data-congratulations', 't');
				$sf.removeClass('disabled').attr('disabled', false).css('cursor', '').html('再抽!!');
			}, 2000);
		// }
	});
});