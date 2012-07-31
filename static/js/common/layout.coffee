jQuery ->
	$ads = jQuery('#commercial_ad')
		.find('.single')
		.show()

	ad_amount = $ads.length - 1

	for i in [0..ad_amount-3]
		random = Math.round( Math.random() * ad_amount - i )
		$ads
			.eq( random )
			.hide()

		$ads.splice( random, 1 )