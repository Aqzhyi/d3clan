jQuery ->
	app =
		origin: {}
		entity: {}

	app.origin.base = Backbone.View.extend
		el: 'body'
		initialize: () ->
			app.entity.news_class = new app.origin.news_class
			app.entity.circle_news = new app.origin.circle_news

	# 新聞分類
	app.origin.news_class = Backbone.View.extend
		el: jQuery '#bb-news_class'
		initialize: () ->
		events:
			'click #menu_btn' : 'switch_news_class'
		switch_news_class: (e) ->
			jQuery(e.currentTarget)
				.addClass('current')
				.siblings()
				.removeClass 'current'

			index = jQuery(e.currentTarget).index();

			@.$el.find('[id=list_block]')
				.eq(index)
				.addClass('current')
				.siblings()
				.removeClass 'current'

	# 輪播看版
	app.origin.circle_news = Backbone.View.extend
		el: jQuery '#bb-circle_news'
		initialize: () ->
		events:
			'click #menu_btn': 'switch_circle_news'
		switch_circle_news: (e) ->
			jQuery(e.currentTarget)
				.addClass('current')
				.siblings()
				.removeClass 'current'

			index = jQuery(e.currentTarget).index();

			@.$el.find('[id=list_block]')
				.eq(index)
				.addClass('current')
				.siblings()
				.removeClass 'current'


	# 初始化
	app.entity.base = new app.origin.base