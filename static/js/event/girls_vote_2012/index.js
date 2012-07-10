jQuery(function() {

	var $girls_area = jQuery('[id^=girl_id_]');

	// 展開詳細資料/投票
	$girls_area.find('#girl_vote').on('click', function() {
		$element = jQuery(this);

		$element.siblings('#girl_detail_info').css('top', $(window).scrollTop()+100).show();
	});

	// 關閉詳細資料/投票
	$girls_area.find('#detail_close').on('click', function() {
		$element = jQuery(this);

		$element.parents('#girl_detail_info').hide();
	});

	// 詳細資料內的照片隨選-往右
	$girls_area.find('#btn_right_click').on('click', function() {
		var $element = jQuery(this);
		var $root    = $element.parent();

		if ( $root.find('#twinned.first').next('#twinned').length ) {
			$root.find('#twinned.first').removeClass('first').next('#twinned').addClass('first');
		}
		else {
			$root.find('#twinned.first').removeClass('first');
			$root.find('#twinned').first().addClass('first');
		}
	});
	// 詳細資料內的照片隨選-往左
	$girls_area.find('#btn_left_click').on('click', function() {
		var $element = jQuery(this);
		var $root    = $element.parent();

		if ( $root.find('#twinned.first').prev('#twinned').length ) {
			$root.find('#twinned.first').removeClass('first').prev('#twinned').addClass('first');
		}
		else {
			$root.find('#twinned.first').removeClass('first');
			$root.find('#twinned').last().addClass('first');
		}
	});
});