jQuery(function() {
	// 選單篩選
	var $data_list_search = jQuery('#data_list_search');

	$data_list_search.on('keyup', function() {
		var search_text = jQuery(this).val();
		var $filter_elements = jQuery('#data_list').find('[data-filter="t"]');

		if (search_text) {
			$filter_elements.hide();
			$filter_elements.parent().addClass('g_dpb').find(':contains('+search_text+')').show();
		}
		else {
			$filter_elements.show();
			$filter_elements.parent().removeClass('g_dpb');
		}
	});
	// ---
});