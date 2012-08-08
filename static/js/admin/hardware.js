jQuery(function(){

	// 新增
	jQuery('#flows').find('#add').on('click', function() {

		var $self = jQuery(this);

		var data = $self.parents('tr').find(':input').serializeArray();

		jQuery.ajax({
			context    : this,
			url        : '/admin/ajax/hardware',
			type       : 'post',
			dataType   : 'json',
			data       : data,
			beforeSend : function(jqXHR, settings) {  },
			success    : function(data, textStatus, jqXHR) {
				if (data.success) {
					window.location.reload();
				}
				else if(data.success!==true){
					alert( data.error_msg );
				}
			},
			complete   : function(jqXHR, textStatus) {  }
		});
	});

	// 刪除
	jQuery('#flows').find('[data-delete-btn]').on('click', function() {

		var $self = jQuery(this);

		var id = $self.data('id');

		jQuery.ajax({
			context    : this,
			url        : '/admin/ajax/hardware/id/'+id,
			type       : 'delete',
			dataType   : 'json',
			data       : {},
			beforeSend : function(jqXHR, settings) {  },
			success    : function(data, textStatus, jqXHR) {
				if (data.success) {
					window.location.reload();
				}
				else if(data.success!==true){
					alert( data.error_msg );
				}
			},
			complete   : function(jqXHR, textStatus) {  }
		});
	});
});