jQuery(function(){
	
	/**
	 * 新增
	 * @return {[type]} [description]
	 */
	jQuery('#home_4_circle').find('#add').on('click', function() {

		var $self = jQuery(this);

		var data = $self.parents('tr').find(':input').serializeArray();

		jQuery.ajax({
			context    : this,
			url        : '/admin/ajax/home-circle',
			type       : 'post',
			dataType   : 'json',
			data       : data,
			beforeSend : function(jqXHR, settings) {  },
			success    : function(data, textStatus, jqXHR) {
				if (data.success) {
					window.location.reload();
				}
				else if (data.success!==true){
					alert(data.error_msg);
				}
			},
			complete   : function(jqXHR, textStatus) {  }
		});
	});

	/**
	 * 刪除
	 * @return {[type]} [description]
	 */
	jQuery('#home_4_circle').find('[data-delete-btn]').on('click', function() {

		var $self = jQuery(this);

		var id = $self.data('id');

		jQuery.ajax({
			context    : this,
			url        : '/admin/ajax/home-circle/' + id,
			type       : 'delete',
			dataType   : 'json',
			beforeSend : function(jqXHR, settings) {  },
			success    : function(data, textStatus, jqXHR) {
				if (data.success) {
					$self.parents('tr').hide();
				}
				else if (data.success!==true){
					alert(data.error_msg);
				}
			},
			complete   : function(jqXHR, textStatus) {  }
		});
	});

});