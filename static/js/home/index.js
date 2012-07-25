jQuery(function() {
	var $middle_block = jQuery('#middle_block');
	var $news_flows   = $middle_block.find('#news_flows').find('[data-kind]');
	var $news_btns    = $middle_block.find('#news_block').find('#sub_menu').find('[data-kind]');
	
	// 載入"綜合主題"新聞資訊流
	$news_flows.eq(0).show(200, function() {
		jQuery('#loading').remove();
	});

	// 新聞資訊流-切換主題機制
	$news_btns.eq(0).addClass('current');
	$news_btns.on('mouseover', function(event) {
		event.preventDefault();
		var $element = jQuery(this);
		var kind     = $element.data('kind');
		$element.addClass('current').siblings().removeClass('current');
		$news_flows.end().find('[data-kind='+kind+']').show().siblings().hide();
	});
});