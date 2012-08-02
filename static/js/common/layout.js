jQuery(function(){

	var $ads = jQuery('#commercial_ad').find('.single');

	var ad_amount = $ads.length - 1;

	var random;

	// 只顯示三個270x60廣告.
	random = Math.round( Math.random() * ad_amount - 0 );
	$ads.eq(random).show();
	$ads.splice( random, 1 );

	random = Math.round( Math.random() * ad_amount - 1 );
	$ads.eq(random).show();
	$ads.splice( random, 1 );

	random = Math.round( Math.random() * ad_amount - 2 );
	$ads.eq(random).show();
	$ads.splice( random, 1 );

	$ads.remove();
});