jQuery ->
	ad_amount = 5 - 1
	
	$ads = jQuery('#commercial_ad')
		.find('.single')
		.show()

	for i in [0..ad_amount-3]
		random = Math.round( Math.random() * ad_amount - i )
		$ads
			.eq( random )
			.hide()

		$ads.splice( random, 1 )