jQuery ->
	ad_amount = 4 - 1
	random_hide_id = Math.round( Math.random() * ad_amount )
	jQuery('#commercial_ad')
		.find('.single')
		.removeClass('g_hide')
		.eq(random_hide_id)
		.addClass('g_hide')