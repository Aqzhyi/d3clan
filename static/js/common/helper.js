jQuery(function(){
	//# 串連物件所有屬性
	/**
	 * 為了提供與配合ajax的輸出, 本函式serialize某個具備[data-id]
	 * 以及其底下那些具備[data-name]的元素.
	 * 
	 * @return {object} [description]
	 */
	jQuery.fn.serialize_content = function() {
		var serialize_content = {};
		var $self = this;

		if ( $self.find('[data-name]').length > 0 ) {
			//#
			var id = $self.data('id');
			//#
			var data = $self.find('[data-name]').serialize_content();
			data.id = id;
			//#
			return data;
		}
		else {
			$self.each(function(index, element) {
				$self = jQuery(element);

				if ( $self.get(0).type === 'checkbox' ) {
					if ( typeof $self.attr('checked') !== 'undefined' ) {
						serialize_content[ $self.data('name') ] = 1;
					}
					else {
						serialize_content[ $self.data('name') ] = 0;
					}
				}
				else if ( $self.val() !== '' ) {
					serialize_content[ $self.data('name') ] = $self.val();
				}
				else {
					serialize_content[ $self.data('name') ] = $self.html();
				}
				
			});
		}

		return serialize_content;
	};

	//# 尋找物件
	/**
	 * obj物件是html中的一個元素, 須具備[data-id]屬性,
	 * 以及在這個元素裡面, 通常還會包裹許多具備[data-name]的元素.
	 * 
	 * @return {jQuery} [description]
	 */
	jQuery.fn.obj = function() {
		var $self = this;
		var $obj = $self.parents('[data-id]');
		return $obj;
	};

	//# 封裝tooltip
	jQuery.fn.pop = function(setting, extend_setting) {

		setting = jQuery.extend({
			trigger: 'manual'
		}, setting);

		extend_setting = jQuery.extend({
			close: 2000,
			callback: function() {},
			scope: this,
			params: {}
		}, extend_setting);

		var $self   = this;

		$self.popover(setting).popover('show');

		if ( extend_setting.close === 0 || extend_setting.close === false ) {
			return true;
		}
		else {
			setTimeout(function() {
				$self.popover('destroy');
				extend_setting.callback.call(extend_setting.scope, extend_setting.params);
			}, extend_setting.close);

			return true;
		}
	};

	//# 封裝 "新增" 功能. 主要為服務後台開發.
	/**
	 * 封裝 "新增" 功能. 主要為服務後台開發.
	 * @param  {} setting   [description]
	 * @return null         [description]
	 * @example
	 	var $root = jQuery('#namespace-A');
	 	$root.find('[data-act=post]').data_row({
			on       : 'click',
			url      : '/admin/ajax/coming_games'
		});
	 */
	jQuery.fn.data_row = function(setting) {

		var $arr = jQuery(this);

		var level1 = jQuery.extend({
			on     : 'click',
			reload : false
		}, setting);

		$arr.on(level1.on, function(e) {
			var $self = jQuery(this);
			var $obj  = $self.obj();
			
			var level2 = jQuery.extend({
				act      : $self.data('act'),
				url      : '/',
				dataType : 'json',
				before   : function(jqXHR, settings) {

				},
				success  : function(response) {
					if (response.success===true) {
						$self.pop({
							content: response.msg,
							placement: 'left'
						}, {
							callback: function() {
								if (level1.reload===true) {
									window.location.reload();
								}
							}
						});
					}
					else if (response.success!==true) {
						$self.pop({
							content: response.msg,
							placement: 'left'
						});
					}
				},
				confirm: false,
				confirm_text: "確定繼續執行?"
			}, setting);

			if (level2.confirm === false || (level2.confirm === true && confirm(level2.confirm_text))) {
				jQuery.ajax({
					url        : level2.url,
					type       : level2.act,
					dataType   : level2.dataType,
					data       : $obj.serialize_content(),
					beforeSend : function(jqXHR, settings) {
						level2.before.apply({
							$obj     : $obj,
							$sf      : $self,
							_setting : setting
						}, arguments);
					},
					success: function(data, textStatus, jqXHR) {
						level2.success.apply({
							$obj     : $obj,
							$sf      : $self,
							_setting : setting
						}, arguments);
					},
					error: function(jqXHR, textStatus, errorThrown) {
						alert( "很抱歉，系統發生無法預期的錯誤!!\r\n\r\n你可以將本畫面截圖傳送給網站管理者以協助除錯.\r\n\r\n" + jqXHR.responseText );
					}
				});
			}
		});
	};

	//# 封裝 "banner輪播" 功能.
	/**
	 * 由一個父元素包合多個子元素, 本機制將會自動依秒數輪播子元素.
	 * 建議一開始first子元素預設 visible, 其它子元素預設 hidden. 
	 * 本機制將會自動輪流進行 show 的動作.
	 * 
	 * @param  {[type]} setting [description]
	 * @return {[type]}         [description]
	 */
	jQuery.fn.banner_circle = function(setting) {
		var $root = this;
		var $els = $root.children();
		var $current = $els.first();

		var setting = jQuery.extend({
			ms: 10000 // 預設10秒
		}, setting);

		$current.show();

		setInterval(function(){
				if ( $current.next(':hidden').length ) {
					$current = $current.hide().next().show();
				}
				else {
					$current.hide();
					$current = $els.first().show();
				}
			}, setting.ms);		
	};
});