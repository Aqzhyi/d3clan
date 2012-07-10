jQuery(function() {
	$circle_elements = jQuery('[id=circle_loop]');
	
	$circle_elements.each(function(index, element) {
		$element = jQuery(element);

		$element.find('#switcher[data-index]').on('mouseover', function(event) {
			$self = jQuery(this);
			$self.addClass('current').siblings().removeClass('current');
			var index = $self.data('index');
			$element.find('#windows[data-index="'+index+'"]').removeClass('g_hide').siblings().addClass('g_hide');
		});
	});

});