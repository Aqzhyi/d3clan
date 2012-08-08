jQuery(function(){

	jQuery('#ad_banners').find('#add').on('click', function() {
		$self = jQuery(this);

		var data = $self.parents('tr').find(':input').serializeArray();

		jQuery.ajax({
			context    : this,
			url        : '/admin/ajax/ad-banners',
			type       : 'post',
			dataType   : 'json',
			data       : data,
			beforeSend : function(jqXHR, settings) {  },
			success    : function(data, textStatus, jqXHR) {
				if (data.success) {
					window.location.reload();
				}
				else if (data.success==false) {
					alert( data.error_msg );
				}
			},
			complete   : function(jqXHR, textStatus) {  }
		});
	});


	jQuery('#ad_banners').find('tbody').find('#delete').on('click', function() {

		if ( ! confirm( "確認要刪除嗎？" ) ) return false;

		$self = jQuery(this);

		var id = $self.data('id');

		jQuery.ajax({
			context    : this,
			url        : '/admin/ajax/ad-banners/id/'+id,
			type       : 'delete',
			dataType   : 'json',
			beforeSend : function(jqXHR, settings) {  },
			success    : function(data, textStatus, jqXHR) {
				if (data.success) {
					$self.parents('tr').hide();
				}
				else if (data.success==false) {
					alert( data.error_msg );
				}
			},
			complete   : function(jqXHR, textStatus) {  }
		});
	});
});