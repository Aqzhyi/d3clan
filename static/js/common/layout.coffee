jQuery ->
	ad_amount = 7 - 1
	
	$ads = jQuery('#commercial_ad')
		.find('.single')
		.show()

	console.log /data/, $ads

	for i in [0..ad_amount-3]
		random = Math.round( Math.random() * ad_amount - i )
		$ads
			.eq( random )
			.hide()

		$ads.splice( random, 1 )