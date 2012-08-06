jQuery(function() {

	// 新增直播頻道
	var $form_add_channel = jQuery('#add_channel');

	$form_add_channel.find('#add').on('click', function(event) {
		event.preventDefault();

		jQuery.ajax({
			url: '/admin/ajax/live-channel',
			type: 'POST',
			data: jQuery('#add_channel :input').serializeArray(),
			complete: function(xhr, textStatus) {
			},
			success: function(data, textStatus, xhr) {
				window.location.reload();
			},
			error: function(xhr, textStatus, errorThrown) {
			}
		});
		
	});

	// 刪除直播頻道
	jQuery('[id^=delete_]').on('click', function(event) {
		event.preventDefault();

		var $element = jQuery(this);
		var id = this.id.match(/_(\d*)$/)[1];

		jQuery.ajax({
			url: '/admin/ajax/live-channel/id/'+id,
			type: 'DELETE',
			complete: function(xhr, textStatus) {
			},
			success: function(data, textStatus, xhr) {
				$element.parents('#row_'+id).hide(function() {
					$element.remove();
				});
			},
			error: function(xhr, textStatus, errorThrown) {
			}
		});

	});
});