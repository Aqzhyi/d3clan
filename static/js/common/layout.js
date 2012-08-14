jQuery(function(){

	var $ads = jQuery('#commercial_ad').find('.single');

	var ad_amount = $ads.length;

	var random;

	// 只顯示三個270x60廣告.
	random = Math.floor( Math.random() * (ad_amount - 0) );
	$ads.eq(random).show();
	$ads.splice( random, 1 );

	random = Math.floor( Math.random() * (ad_amount - 1) );
	$ads.eq(random).show();
	$ads.splice( random, 1 );

	random = Math.floor( Math.random() * (ad_amount - 2) );
	$ads.eq(random).show();
	$ads.splice( random, 1 );

	// // 驗證
	// if ($ads.siblings('[href*="http://expendables2.catchplay.com/"]').is(':visible')) {
	// 	console.log(/270x60 banner/, "浴血任務2");
	// }
	// if ($ads.siblings('[href*="http://www.cmstorm.com/tw/start/"]').is(':visible')) {
	// 	console.log(/270x60 banner/, "CMSTORM");
	// }
	// if ($ads.siblings('[href*="http://www.roccat.org"]').is(':visible')) {
	// 	console.log(/270x60 banner/, "ROCCAT");
	// }

	$ads.remove();
});