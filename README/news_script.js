/* 轉新聞專用script code，限制使用 google chrome 瀏覽器。 */
/* 本code同時支持TESL與s.163.com與d.163.com */
/* ctrl+a全選本code，於指定新聞頁面按F12，並於console面版貼入本code按下enter即可 */
(function(window, undefined) {
	
	var app       = {};
	var code      = {};
	var libMedia  = {};
	var libTrans  = {};
	var libHelper = {};
	var termList  = {};
	
	//# 字詞轉換索引表
	termList.common = [
		// 一般
		{orig: /，?轉載請注明出處！?/mg, to: ''}
		,{orig: /-?-?S\.163\.COM(編制|制作|編譯)/mg, to: ''}
		,{orig: /--詳細報導/mg, to: ''}
		,{orig: /這篇博客/mg, to: '這篇文章'}
		,{orig: /網吧/mg, to: '網咖'}
		,{orig: /社區/mg, to: '社群'}
		,{orig: /錄像/mg, to: '錄影'}
		,{orig: /網絡/mg, to: '網路'}
		,{orig: /點擊/mg, to: '點選'}
		,{orig: /屌絲/mg, to: '<a target="_blank" href="http://zh.wikipedia.org/zh-tw/%E5%B1%8C%E4%B8%9D" title="名詞解釋">屌絲</a>'}
		,{orig: /視頻/mg, to: '影片'}
		,{orig: /菜單/mg, to: '選單'}
		,{orig: /攢錢/mg, to: '賺錢'}
		,{orig: /郵箱地址/mg, to: '電子郵件'}
		,{orig: /攢了/mg, to: '賺了'}
		,{orig: /質朴/mg, to: '簡單'}
		,{orig: /采集/mg, to: '採集'}
		,{orig: /補丁/mg, to: '更新'}
		,{orig: /采訪/mg, to: '採訪'}
		,{orig: /游戲/mg, to: '遊戲'}
		,{orig: /報道/mg, to: '報導'}
		,{orig: /([\d]+) ?個贊/mg, to: '$1個讚'}
		,{orig: /地域/mg, to: '地區'}
		,{orig: /戰斗/mg, to: '戰鬥'}
		,{orig: /其余/mg, to: '其餘'}
		,{orig: /注冊/mg, to: '註冊'}
		,{orig: /標志/mg, to: '標誌'}
		,{orig: /登陸/mg, to: '登入'}
		,{orig: /范圍/mg, to: '範圍'}
		,{orig: /沖擊/mg, to: '衝擊'}
		,{orig: /薪金/mg, to: '薪資'}
		,{orig: /我的文檔/mg, to: '我的文件夾'}
		,{orig: /谷歌/mg, to: 'Google'}
		,{orig: /干什麼/mg, to: '幹什麼'}
		,{orig: /硬剛/mg, to: '硬肛'}
		,{orig: /鼠標/mg, to: '滑鼠游標'}
		,{orig: /輕松/mg, to: '輕鬆'}
		,{orig: /注：/mg, to: '註：'}
		,{orig: /魷魚杯/mg, to: '魷魚盃'}
		,{orig: /剩余/mg, to: '剩餘'}
		,{orig: /幾率/mg, to: '機率'}
		,{orig: /短信/mg, to: '簡訊'}
		,{orig: /拍賣行/mg, to: '拍賣場'}
		,{orig: /賬戶/mg, to: '帳號'}
		,{orig: /信息/mg, to: '訊息'}
		,{orig: /防御/mg, to: '防禦'}
		,{orig: /博客/mg, to: 'Blog'}
		,{orig: /雜志/mg, to: '雜誌'}
		,{orig: /網游/mg, to: '網路遊戲'}
		,{orig: /關系/mg, to: '關係'}
		,{orig: /軟妹幣/mg, to: '人民幣'}
		,{orig: /RMB/mg, to: '人民幣'}
		,{orig: /美元/mg, to: '美金'}
		,{orig: /美刀/mg, to: '美金'}
		,{orig: /([\d]+)刀/mg, to: '$1美金'}
		,{orig: /干這行/mg, to: '幹這行'}
		,{orig: /70後/mg, to: '六年級生'}
		,{orig: /80後/mg, to: '七年級生'}
		,{orig: /90後/mg, to: '八年級生'}
		,{orig: /刷屏/mg, to: '洗畫面'}
		,{orig: /沖動/mg, to: '衝動'}
		,{orig: /婪欲/mg, to: '婪慾'}
		,{orig: /北京時間/mg, to: '台北時間'}
		,{orig: /　　/mg, to: ''}
		,{orig: /&nbsp;&nbsp;&nbsp;&nbsp;/mg, to: ''}
		/* 資訊用詞 */
		,{orig: /數據庫/mg, to: '資料庫'}
		,{orig: /服務器/mg, to: '伺服器'}
		,{orig: /數字版/mg, to: '數位版'}
		,{orig: /軟件/mg, to: '軟體'}
		,{orig: /掉線/mg, to: '斷線'}
		,{orig: /局域網/mg, to: '區網'}
		,{orig: /機器人程序/mg, to: '機器人程式'}
		,{orig: /程序腳本/mg, to: '程式腳本'}
		,{orig: /編程/mg, to: '寫程式'}
		,{orig: /聯機至/mg, to: '連線至'}
		,{orig: /屏幕/mg, to: '螢幕'}
		,{orig: /注入/mg, to: 'injection'}
		,{orig: /帶寬/mg, to: '頻寬'}
		,{orig: /硬件/mg, to: '硬體'}
		,{orig: /光盤/mg, to: '光碟'}
		,{orig: /硬盤/mg, to: '硬碟'}
		,{orig: /互聯網/mg, to: '網際網路'}
		,{orig: /U盤/mg, to: 'USB'}
		,{orig: /回車/mg, to: 'enter'}
		,{orig: /系統盤/mg, to: '系統槽'}
		,{orig: /進程/mg, to: '處理程序'}
		/* 敏感用詞 */
		,{orig: /國服/mg, to: '中國伺服器'}
		,{orig: /中國香港/mg, to: '香港'}
		,{orig: /臺灣/mg, to: '台灣'}
		,{orig: /台灣地區/mg, to: '台灣'}
		,{orig: /中國台灣/mg, to: '台灣'}
		,{orig: /我國/mg, to: '中國'}
		/*遊戲*/
		,{orig: /開荒號/mg, to: '開荒角色'}
		,{orig: /小號/mg, to: '分身'}
		,{orig: /大號/mg, to: '本尊'}
	];

	termList.StarCraft = [
		{orig: /錄像/mg, to: 'Replay'}
		,{orig: /S.163.COM[:：]?/mg, to: ''}
		,{orig: /星際爭霸/mg, to: '星海爭霸'}
		,{orig: /星海爭霸 ?2/mg, to: '星海爭霸II'}
		,{orig: /星際/mg, to: '星海'}
		,{orig: /國家杯/mg, to: '國家盃'}
		,{orig: /洲際杯/mg, to: '洲際盃'}
		,{orig: /S\.163\.COM/mg, to: '網易遊戲'}
		,{orig: /安提嘉星港/mg, to: '安提卡船廠'}
		// 蟲族
		,{orig: /虫/mg, to: '蟲'}
		,{orig: /異蟲/mg, to: '蟲族'}
		,{orig: /凱瑞甘/mg, to: '凱瑞根'}
		,{orig: /宿主/mg, to: '王蟲'}
		,{orig: /眼蟲/mg, to: '監察王蟲'}
		,{orig: /腐化者/mg, to: '腐化飛蟲'}
		,{orig: /腐蝕者/mg, to: '腐化飛蟲'}
		,{orig: /巢穴領主/mg, to: '寄生王蟲'}
		,{orig: /爆蟲/mg, to: '毒爆'}
		,{orig: /爆蟲巢/mg, to: '毒爆蟲巢'}
		,{orig: /分裂池/mg, to: '孵化池'}
		// 神族
		,{orig: /星靈/mg, to: '神族'}
		,{orig: /狂熱者/mg, to: '狂戰士'}
		,{orig: /叉子/mg, to: '狂戰士'}
		,{orig: /探機/mg, to: '探測機'}
		,{orig: /探針/mg, to: '探測機'}
		,{orig: /光子砲/mg, to: '光砲'}
		// 人族
		,{orig: /惡火/mg, to: '惡狼噴火車'}
		,{orig: /農民/mg, to: '工兵'}
		,{orig: /收割者/mg, to: '死神'}
		,{orig: /幽靈兵/mg, to: '幽靈特務'}
	];

	termList.Diablo = [
		{orig: /公共遊戲/mg, to: '公開遊戲'}
		,{orig: /掉率/mg, to: '掉落率'}
		,{orig: /(爆|暴)率/mg, to: '爆擊率'}
		,{orig: /二(餅|柄)/mg, to: '武僧'}
		,{orig: /舔爺/mg, to: '噬魂獸'}
		,{orig: /魔法師/mg, to: '秘術師'}
		,{orig: /獵魔人/mg, to: '狩魔獵人'}
		,{orig: /蠻子/mg, to: '野蠻人'}
		,{orig: /戰網通行證/mg, to: '戰網帳號'}
		,{orig: /跑酷/mg, to: '炫風'}
		,{orig: /BB們/mg, to: '野蠻人'}
		,{orig: /涅法雷姆之力/mg, to: '涅法雷姆之勇'}
		,{orig: /涅法雷姆勇氣/mg, to: '涅法雷姆之勇'}
		,{orig: /奈法蘭[的之]?勇氣?/mg, to: '涅法雷姆之勇'}
		,{orig: /奈非天(之勇氣)?/mg, to: '涅法雷姆之勇'}
		,{orig: /奈非天(勇氣)?/mg, to: '涅法雷姆之勇'}
		,{orig: /暗黑破壞神 ?3/mg, to: '暗黑破壞神III'}
		,{orig: /惡棍林登/mg, to: '盜賊林登'}
		,{orig: /秘法強化/mg, to: '秘法加持'}
		,{orig: /宝藏携带者/mg, to: '黃金哥布林'}
		,{orig: /艾莉娜/mg, to: '艾蓮娜'}
		,{orig: /手機安全令牌?/mg, to: '手機驗證器'}
		,{orig: /戰網安全令牌?/mg, to: '戰網驗證器'}
		,{orig: /JY/mg, to: '精英'}
		,{orig: /iOS用戶點此觀看/mg, to: ''}
		// 網易暗黑
		,{orig: /凱恩之角影片站/mg, to: ' '}
	];

	//# TeSL新聞
	code.TESL = function() {
		var domain = 'http://www.esports.com.tw/';
		var $root = jQuery('.textEditor');

		// 替圖片加上絕對路徑
		$root.find('img').each(function(index, element) {
			var $self = jQuery(element);

			if ( $self.attr('src').match(/^\//) ) {
				$self.attr('src', domain + $self.attr('src'));
			}
		});

		// 插入copy area
		if ( jQuery('#copyarea').length > 0 ) {
			jQuery('#copyarea').val( $root.html() );
		}
		else {
			var $new_content = jQuery('<textarea>', {
				id: 'copyarea',
				val: $root.html(),
				css: {
					background: 'black',
					padding: '5px',
					color: 'yellow',
					width: '98%',
					height: '300px'
				}
			});

			$root.before( $new_content );
		}
	};

	//# bz官網新聞
	code.twBattleNetSC = function() {
		libMedia.youtube( jQuery('.detail'), 'html');

		jQuery('.community-share').remove();
		jQuery('.keyword-list').remove();
		jQuery('.byline').remove();

		var outerHTML = jQuery('<div>').css({
				'background-color': '#000D20',
				'color': '#6EA6CA',
				'padding': '5px'
			}).html(
				jQuery('#blog').get(0).outerHTML
			).find('a').css({
				'color': '#00D683'
			}).end().get(0).outerHTML;

		var output = '來源: <a target="_blank" href="'+document.location.href+'">Blizzard台灣官方網站</a><br /><br />' + outerHTML;

		// 插入copy area
		if ( jQuery('#copyarea').length > 0 ) {
			jQuery('#copyarea').val( output );
		}
		else {
			var $new_content = jQuery('<textarea>', {
				id: 'copyarea',
				val: output,
				css: {
					background: 'black',
					padding: '5px',
					color: '#009CFF',
					width: '98%',
					height: '300px'
				}
			});

			jQuery('#blog').before( $new_content );
		}
	};

	//# 網易星際
	code.s163 = function() {
		var muilt_page = false;

		//# 如果有第2頁以上...把其他頁面抓進來合併...
			// 未實作
		
		//# 轉文腳本核心
		var sc2_main_program = function() {
				window.Q = jQuery.noConflict();
			//# 去除不必要的東西.
				Q('div.header').remove();
				Q('div.nav').remove();
				Q('div.NTES_nav_').parent().remove();
				Q('#PopWindow').remove();
				Q('div.colR').remove();
				Q('div.colLM').css({width: 'auto'});
				Q('.col-r').remove(); // d3

			//# 初始化
				window.$root        = Q('.endContent');
				window.$script_code = Q('<script>');
				window.$page        = Q('.endPageNum').detach();
				window.hasVod       = false;
				$root.css({ border: '5px solid red', padding: 0, width: 700 - 10 });
				Q('#sc2clan_preview').remove();
				$root.before('<p id="sc2clan_preview">').parent().find('#sc2clan_preview').html('預覽').css({fontSize: 24, color: 'red'}); 

			//*****------------------
			// 檢測
			//------------------*****
			//# 檢測寬度
				var overWidth = $root.find('*').filter(function(index, element) {
					return jQuery(this).outerWidth() > 700;
				});

				// 如果新聞寬度超過了Discuz的寬度限制...
				if (overWidth.size() > 1) {
					// 未實作
					// $script_code.html(
					// $script_code.html() + "jQuery(function(){\
					// 				jQuery('dl.postprofile:eq(0)').hide();\
					// 				jQuery('div.postbody:eq(0)').css('width', '100%');\
					// 		   });");
				}

			//# 檢測是否存在影片
				if ( $root.find('embed[src*=youku], embed[src*=pptv]').size() ) {
					window.hasVod = true;
				}

			//# 清除雜物以符合轉文期待
				$root.find('table').css({
					background: '#000'
				}).find('td,font').css({
					color: '#fff'
				}).end().find('td').filter(function() {
					var $element = jQuery(this);
					return (
					($element.attr('bgcolor')));
				}).attr('bgcolor', '#3c3c3c');
				$root.find('table.f_table[background]').attr('background', '');
				$root.find('img').each(function() {
					var $element = jQuery(this);
					$element.css({
						maxWidth: 700
					});
				});

				// 移掉alink
				$root.find('a').each(function() {
					var $element = jQuery(this);
					var html = $element.html();

					if (html.match(/原(?:帖|文|推)地址/)) {} else if (html.match(/下載/)) {} else if (html.match(/前往原..?(觀|看|查|尋)./)) {} else if (html != '' && html.length > 5) {} else if ($element.is('a[href*=img1.cache]')) {} else if ($element.find('img').size()) {} else {
						jQuery(this).replaceWith(jQuery(this).html());
					}
				});

				$root.find('img[alt=Video]').remove();
				$root.find('img[alt=访谈]').remove();
				$root.find('td[width="50%"][align=right]').empty();
				$root.find('.info').remove();
				$root.find('.summary').remove();
				$root.find('.endPageNum').remove();
				$root.find('td[class=f_right]').filter(function() {
					return (jQuery(this).text().match(/視頻/));
				}).empty();
				$root.find('td').filter(function() {
					return (jQuery(this).html().match(/(采訪)/img));
				}).empty();

				jQuery('\
					 table[cellspacing="0"][cellpadding="0"][width="600"][bgcolor="#14415e"],\
					 table[cellspacing="0"][cellpadding="0"][width="598"][bgcolor="#14415e"]\
					 ').filter(function() {
					return (
					jQuery(this).css('border-color') == "rgb(38, 98, 136)");
				}).remove();

				$root.find('p').filter(function() {
					var $element = jQuery(this);
					return (
					($element.html().match(/S\.[\d]{0,3}\.[com]{0,3}.*轉載.*(注|註)明/img)) || ($element.html().match(/(下一頁|上一頁)/)) || ($element.html().match(/.*S\.[\d]{0,3}\.[com]{0,3}.*繼續.*?關注.*?/)) || ($element.html().match(/.*?請.*?(關注|鎖定).*?S\.[\d]{0,3}\.[com]{0,3}.*?/)) || ($element.html().match(/^&gt;&gt;&gt;點此.*/)) || ($element.html().match(/^&gt;&gt;&gt;更多相關.*鎖定.*專題/)));
				}).remove();

				$root.find('p').filter(function() {
					return (
					jQuery(this).text().match(/^相關.*：$/) || jQuery(this).text().match(/^詳細.*：$/) || jQuery(this).text().match(/^更多.*：$/) || jQuery(this).text().match(/^賽前.*：$/));
				}).nextAll('p').andSelf().remove();

				$root.find('#endText:last').html($root.find('#endText:last').children('*:not(a,img)'));

				// 相容網易圖片播放器
				$root.find('.nph_gallery').each(function() {
					jQuery(this).after('<div style="clear: both;"></div>');
					jQuery(this).replaceWith(
					jQuery(this).find('.nph_list_thumb>li').each(function() {
						var $this = jQuery(this);
						var orig_img_url = $this.find('i[title=img]').html();
						$this.find('img').wrap('<a target="_blank" href="' + orig_img_url + '"></a>');
						$this.find('h2,p,i').remove();
						$this.css({
							float: 'left',
							listStyleType: 'none',
							margin: '3px'
						});
					}));
				});

				$root.find('embed[src*=netease]').each(function() {
					var $element = jQuery(this);
					code = $element.attr('src');
					$element.replaceWith('\r\n\r\n[tv163]' + code + '[/tv163]\r\n\r\n');
				});

				$root.find('embed[src*=youku]').each(function() {
					var $element = jQuery(this);
					var code = $element.attr('src').match(/youku\.com\/.*\/sid\/(.*)\//im);
					code = code ? code[1] : '';
					$element.replaceWith('\r\n\r\n[youku]' + code + '[/youku]\r\n\r\n');
				});

				$root.find('embed[src*=pptv]').each(function() {
					var $element = jQuery(this);
					var code = $element.attr('src').match(/pptv\.com\/v\/(.*)\.swf/);
					code = code ? code[1] : '';
					$element.replaceWith('\r\n\r\n[pptv]' + code + '[/pptv]\r\n\r\n');
				});

			// ------------------------------------------
			// ------------------------------------------
			// ------------------------------------------
			var title   = Q('h1#h1title').html();
			var content = $root.html();

			title   = libTrans.transform(title, termList.common);
			content = libTrans.transform(content, termList.common);
			
			title   = libTrans.transform(title, termList.StarCraft);
			content = libTrans.transform(content, termList.StarCraft);

			title   = title.replace(/\[(?:影片|視頻)\]/, '');
			title   = title.replace(/['"]/g, '＂');
			title   = title.replace(/%/g, '％');

			if ( $root.find('#sc2clan_mark').length == 0 ) {
				content = content + "<p><br /></p><p id='sc2clan_mark'>本文轉自<a id='fetched' target='_blank' href='"+document.location.href+"'>網易遊戲星際爭霸2</a>，並由「星盟《星海爭霸II》電競情報站」進行正體中文化及在地化名詞轉換。</p>";
			}

			Q('h1#h1title').html(title);
			$root.html(content);

			libHelper.createCopyArea(title, content);

			/**
			 * 協助轉帖
			 */
			// 判斷本文該何去何從
			var url = '';
			var keywords = Q('meta[name=Keywords]').attr('content');
			var typeids = {
				'1'  : 'http://sc2clan.tw/bbs/forum.php?mod=post&action=newthread&fid=49&typeid=1&htmlon=1', // 新聞>最新>[新聞]
				'2'  : 'http://sc2clan.tw/bbs/forum.php?mod=post&action=newthread&fid=49&typeid=2&htmlon=1', // 新聞>最新>[官方]
				'65' : 'http://sc2clan.tw/bbs/forum.php?mod=post&action=newthread&fid=49&typeid=65&htmlon=1', // 新聞>最新>[GSL]
				'68' : 'http://sc2clan.tw/bbs/forum.php?mod=post&action=newthread&fid=49&typeid=68&htmlon=1', // 新聞>最新>[WCS]
				'66' : 'http://sc2clan.tw/bbs/forum.php?mod=post&action=newthread&fid=49&typeid=66&htmlon=1', // 新聞>最新>[MLG]
				'69' : 'http://sc2clan.tw/bbs/forum.php?mod=post&action=newthread&fid=49&typeid=69&htmlon=1', // 新聞>最新>[WCG]
				'67' : 'http://sc2clan.tw/bbs/forum.php?mod=post&action=newthread&fid=49&typeid=67&htmlon=1', // 新聞>最新>[NASL]
				'5'  : 'http://sc2clan.tw/bbs/forum.php?mod=post&action=newthread&fid=55&typeid=5&htmlon=1', // 新聞>TESL>[橘子]
				'6'  : 'http://sc2clan.tw/bbs/forum.php?mod=post&action=newthread&fid=55&typeid=6&htmlon=1', // 新聞>TESL>[華義]
				'7'  : 'http://sc2clan.tw/bbs/forum.php?mod=post&action=newthread&fid=55&typeid=7&htmlon=1', // 新聞>TESL>[鋼鐵]
				'8'  : 'http://sc2clan.tw/bbs/forum.php?mod=post&action=newthread&fid=55&typeid=8&htmlon=1', // 新聞>TESL>[太陽]
				'10' : 'http://sc2clan.tw/bbs/forum.php?mod=post&action=newthread&fid=57&typeid=10&htmlon=1', // 新聞>GSL>[新聞]
				'11' : 'http://sc2clan.tw/bbs/forum.php?mod=post&action=newthread&fid=58&typeid=11&htmlon=1', // 新聞>人物>[台灣採訪]
				'12' : 'http://sc2clan.tw/bbs/forum.php?mod=post&action=newthread&fid=58&typeid=12&htmlon=1', // 新聞>人物>[其它採訪]
				'13' : 'http://sc2clan.tw/bbs/forum.php?mod=post&action=newthread&fid=59&typeid=13&htmlon=1', // 新聞>VOD>[VOD]
			};

			// 越優先者排越下面.
			if ( keywords.match(/(图说星际)/g) ) url = typeids[1];
			if ( keywords.match(/(新闻)/g) ) url = typeids[1];
			if ( keywords.match(/(VOD)/g) ) url = typeids[13];
			if ( keywords.match(/(访谈)/g) ) url = typeids[12];
			if ( keywords.match(/(GSL)/g) ) url = typeids[22];
			if ( keywords.match(/(GSL)/g) && keywords.match(/(访谈)/g) ) url = typeids[12];

			// 檢查是否重複貼文
			window.jsonp_callback = function( is_repeat ) {
				console.log('已檢查完成');

				// 未知的url, 請求人工判別..
				if ( url == '' || typeof url == 'undefined' ) {
					var custom_url = prompt(
						"系統未成功偵測文章分類，請手動輸入「數字」以選擇 版面->分類：\n"+
						"'1'  : 新聞>最新>[新聞]\n"+
						"'2'  : 新聞>最新>[官方]\n"+
						"'65' : 新聞>最新>[GSL]\n"+
						"'68' : 新聞>最新>[WCS]\n"+
						"'66' : 新聞>最新>[MLG]\n"+
						"'69' : 新聞>最新>[WCG]\n"+
						"'67' : 新聞>最新>[NASL]\n"+
						"'5'  : 新聞>TESL>[橘子]\n"+
						"'6'  : 新聞>TESL>[華義]\n"+
						"'7'  : 新聞>TESL>[鋼鐵]\n"+
						"'8'  : 新聞>TESL>[太陽]\n"+
						"'10' : 新聞>GSL>[新聞]\n"+
						"'11' : 新聞>人物>[台灣採訪]\n"+
						"'12' : 新聞>人物>[其它採訪]\n"+
						"'13' : 新聞>VOD>[VOD]\n"
					);
					url = typeids[custom_url];
				}

				// 此篇檢查標題後, 未重複, 此篇文章可轉去論壇.
				var alink_tag = Q('<a>', {
					id: 'link_area_title',
					href: url + "&subject=" + encodeURIComponent( Q('#copy_area_title').val() ),
					target: '_blank'
				}).css({
					display: 'block',
					fontSize: 24,
					color: 'yellow'
				}).html( Q('#copy_area_title').val() );

				Q( '#copy_area_title' ).replaceWith( alink_tag );

				if (is_repeat === true) {
					console.error(/注意: 本文已存在於暗盟論壇!/);
					alert( '注意: 本文已存在於暗盟論壇!' );
				}
			};

			// 檢查是否重複貼文
			Q.ajax({
				url        : 'http://sc2clan.tw/api/is_repeat',
				dataType   : 'jsonp',
				beforeSend : function() {
					console.log('正在檢查論壇是否已存在重複文章...');
				},
				data: {
					post_uri: location.href,
					post_title: title,
					callback: 'jsonp_callback'
				}
			});
		};

		var interval2 = setInterval(function(){
			if ( muilt_page === false ) {
				TongWen.convertToTrad( sc2_main_program );
				clearInterval(interval2);
			}
		}, 300);
	};

	//# 網易凱恩之角
	code.d163 = function() {
		var muilt_page = false;

		// 如果有第2頁以上...把其他頁面抓進來合併...
		if ( jQuery('.page').length>0 ) {

			muilt_page = true;

			jQuery('.page a').each(function(){
				var $self = jQuery(this);
				if ($self.html().match(/\d+/)) {
					jQuery.ajax({
						context    : this,
						url        : $self.attr('href'),
						type       : 'get',
						success    : function(data, textStatus, jqXHR) {
							var $else_page = jQuery(data);
							var $this_page = jQuery('.endContent');

							$else_page.find('.page').remove();

							$this_page.find('.page').remove();

							$this_page.append( $else_page.find('.endContent').html() );

							$this_page.find('.info').remove();

							window.$this_page = $this_page;

							muilt_page = false;
						}
					});
				}
			});
		}

		// 轉文腳本核心
		var main_program = function() {
			window.$root        = jQuery('.endContent');
			window.$script_code = jQuery('<script>');
			window.$page        = jQuery('.endPageNum').detach();
			window.hasVod       = false;

			if ( $root.find('embed[src*=youku], embed[src*=pptv]').size() ) {
				window.hasVod = true;
			}

			var download_files = $root.find('a[href*=rar], a[href*=zip], a[href*=7z], a[href*=tar]');
			if ( download_files.length != 0 ) {
				$root.find('a[href*=rar], a[href*=zip], a[href*=7z], a[href*=tar]').each(function(index, element) {
					var $element = jQuery(element);

					if ( jQuery('#download_file_'+index).length == 0 ) {
						$element.before("<p id='download_file_"+index+"'>本帖原址有附帶檔案，暗盟不負責檢測檔案安全，欲下載者請自行評量斟酌。</p>");
					}

				});
			}

			$root.find('embed[src*=youku]').each(function(){
				var $element = jQuery(this);
				var code = $element.attr('src').match(/youku\.com\/.*\/sid\/(.*)\//im);
					code = code ? code[1] : '';
				$element.replaceWith('\r\n\r\n[youku]'+code+'[/youku]\r\n\r\n');
			});

			// 相容網易圖片播放器
			$root.find('.nph_gallery').each(function() {
				jQuery(this).after('<div style="clear: both;"></div>');
				jQuery(this).replaceWith(
						jQuery(this).find('.nph_list_thumb>li').each(function() {
							var $this	   = jQuery(this);
							var orig_img_url = $this.find('i[title=img]').html();
							$this.find('img').wrap('<a target="_blank" href="'+orig_img_url+'"></a>');
							$this.find('h2,p,i').remove();
							$this.css({
								float	    : 'left',
								listStyleType : 'none',
								margin	   : '3px'
							});
						})
					);
			});

			// 移除掉礙事的元素
			jQuery('.col-r').remove();
			jQuery('span.info').remove();

			// 如果有藍帖背景
			jQuery('.warp-blueb').css({
				background: '#0D0D30'
			});

			// 修正必要元素
			jQuery('.maincon.endContent').css({
				width  : 700,
				border : '5px solid yellow'
			});

			// ------------------------------------------
			// ------------------------------------------
			// ------------------------------------------

			var title   = jQuery('h1#h1title').html();
			var content = $root.html();
			
			title   = libTrans.transform(title, termList.common);
			content = libTrans.transform(content, termList.common);
			
			title   = libTrans.transform(title, termList.Diablo);
			content = libTrans.transform(content, termList.Diablo);

			title   = title.replace(/\[(?:影片|視頻)\]/, '');
			title   = title.replace(/['"]/g, '＂');
			title   = title.replace(/%/g, '％');

			if ( $root.find('#d3clan_mark').length == 0 ) {
				content = content + "<p id='d3clan_mark'>本文轉自<a id='fetched' target='_blank' href='"+document.location.href+"'>網易遊戲凱恩之角</a>，並由「暗盟《暗黑破壞神III》電競情報站」進行正體中文化及在地化詞語轉換。</p>";
			}

			jQuery('h1#h1title').html(title);
			$root.html(content);

			libHelper.createCopyArea(title, content);

			/**
			 * 協助轉帖
			 */
			// 判斷本文該何去何從
			var url = '';
			var keywords = $('meta[name=keywords]').attr('content');
			var typeids = {
				'21' : 'http://d3clan.tw/bbs/forum.php?mod=post&action=newthread&fid=54&typeid=21&htmlon=1',
				'22' : 'http://d3clan.tw/bbs/forum.php?mod=post&action=newthread&fid=54&typeid=22&htmlon=1',
				'23' : 'http://d3clan.tw/bbs/forum.php?mod=post&action=newthread&fid=54&typeid=23&htmlon=1',
				'24' : 'http://d3clan.tw/bbs/forum.php?mod=post&action=newthread&fid=55&typeid=24&htmlon=1',
				'25' : 'http://d3clan.tw/bbs/forum.php?mod=post&action=newthread&fid=55&typeid=25&htmlon=1',
				'26' : 'http://d3clan.tw/bbs/forum.php?mod=post&action=newthread&fid=55&typeid=26&htmlon=1',
				'27' : 'http://d3clan.tw/bbs/forum.php?mod=post&action=newthread&fid=55&typeid=27&htmlon=1',
				'28' : 'http://d3clan.tw/bbs/forum.php?mod=post&action=newthread&fid=55&typeid=28&htmlon=1',
				'29' : 'http://d3clan.tw/bbs/forum.php?mod=post&action=newthread&fid=55&typeid=29&htmlon=1',
				'30' : 'http://d3clan.tw/bbs/forum.php?mod=post&action=newthread&fid=56&typeid=30&htmlon=1',
			};

			// 最新消息->藍帖
			if ( keywords.match(/(蓝贴|蓝帖|藍帖|藍貼)/g) ) {url = typeids[22]; }

			// 最新消息->新聞 (官方新聞)
			if ( keywords.match(/官方/g) && keywords.match(/公告/g) ) {url = typeids[21]; }

			// 攻略推薦->野蠻人
			if ( keywords.match(/攻略/g) && keywords.match(/野蛮人/g) ) {url = typeids[24]; }

			// 攻略推薦->秘術師
			if ( keywords.match(/攻略/g) && keywords.match(/魔法师/g) ) {url = typeids[25]; }

			// 攻略推薦->武僧
			if ( keywords.match(/攻略/g) && keywords.match(/武僧/g) ) {url = typeids[26]; }

			// 攻略推薦->狩魔獵人
			if ( keywords.match(/攻略/g) && keywords.match(/猎魔人/g) ) {url = typeids[27]; }

			// 攻略推薦->巫醫
			if ( keywords.match(/攻略/g) && keywords.match(/巫医/g) ) {url = typeids[28]; }

			// 推薦視頻
			if ( keywords.match(/视频/g) ) {url = typeids[30]; }

			// 檢查是否重複貼文
			window.jsonp_callback = function( is_repeat ) {
				console.log('已檢查完成');

				// 未知的url, 請求人工判別..
				if ( url == '' ) {
					var custom_url = prompt("\
						系統未成功偵測文章分類，請手動輸入「數字」以選擇 版面->分類：\n\
						21. 最新消息->新聞\n\
						22. 最新消息->藍帖\n\
						23. 最新消息->圖文\n\
						\n\
						24. 攻略推薦->野蠻人\n\
						25. 攻略推薦->秘術師\n\
						26. 攻略推薦->武僧\n\
						27. 攻略推薦->狩魔獵人\n\
						28. 攻略推薦->巫醫\n\
						29. 攻略推薦->其它\n\
						\n\
						30. 精彩視頻->視頻\n\
						");
					url = typeids[custom_url];
				}

				// 此篇檢查標題後, 未重複, 此篇文章可轉去論壇.
				var alink_tag = jQuery('<a>', {
					id: 'link_area_title',
					href: url + "&subject=" + encodeURIComponent( jQuery('#copy_area_title').val() ),
					target: '_blank'
				}).css({
					display: 'block',
					fontSize: 24
				}).html( jQuery('#copy_area_title').val() );

				jQuery( '#copy_area_title' ).replaceWith( alink_tag );

				if (is_repeat === true) {
					console.error(/注意: 本文已存在於暗盟論壇!/);
					alert( '注意: 本文已存在於暗盟論壇!' );
				}
			};

			// 檢查是否重複貼文
			jQuery.ajax({
				url: 'http://d3clan.tw/api/is-repeat',
				dataType: 'jsonp',
				beforeSend: function() {
					console.log('正在檢查論壇是否已存在重複文章...');
				},
				data: {
					post_uri: location.href,
					post_title: title,
					callback: 'jsonp_callback'
				}
			});
		};

		var interval1 = setInterval(function(){
			if ( muilt_page === false ) {
				TongWen.convertToTrad( main_program );
				clearInterval(interval1);
			}
		}, 300);
	};

	//# 主程式
	app.main_exec = function() {
		jQuery.noConflict();

		var href = document.location.href;

		if (href.match(/(.*www\.esports\.com\.tw.*|203\.70\.11\.190)/)) {
			code.TESL();
		} else if (href.match(/tw\.battle\.net/)) {
			code.twBattleNetSC();
		} else if (href.match(/s\.163\.com/)) {
			code.s163();
		} else if (href.match(/d\.163\.com/)) {
			code.d163();
		}
	};

	//# utils
	libMedia.youtube = function($root, type) {
		if (type === 'bbcode') {
			$root.find('iframe[src*="youtube"]').each(function() {
				var $element = jQuery(this);
				var hash = $element.attr('src').match(/\/embed\/(.*)\?\w?/im);
				hash = hash ? hash[1] : '';
				$element.replaceWith('\r\n\r\n[youtube]' + hash + '[/youtube]\r\n\r\n');
			});

			return true;
		}

		if (type === 'html') {
			$root.find('iframe[src*="youtube"]').each(function() {
				var $element = jQuery(this);
				$element.css({
					width: 700,
					height: 395
				});
			});

			return true;
		}

		alert('[Error] libMedia.youtube()');
	};

	//# 修飾內文以符合台灣用語
	libTrans.transform = function(string, termList) {
		for (var i=0; i < termList.length; i++) {
			var term = termList[i];
			string = string.replace(term.orig, term.to);
		}

		return string;
	};

	//# 創造 textarea 讓編輯可以轉co
	libHelper.createCopyArea = function(title, content) {
		jQuery('#copy_area_title').add('#copy_area_content').remove();

		var $title = jQuery('<input id="copy_area_title">').css({
			fontSize: 18
		}).val(title);
		var $content = jQuery('<textarea id="copy_area_content">').css({
			fontSize: 14,
			height: 300
		}).val(content);

		jQuery('body').prepend(
			$title.add($content.get(0)).css({
				color      : 'white',
				background : 'black',
				width      : '100%',
				padding    : 8
			})
		);
	};

	//# 驗證
	var validate = function() {
		if (window.jQuery === undefined) {
			if (!document.getElementById('jquery_script_element')) {
				console.info('inject jQuery');
				var jquery = document.createElement('script');
				jquery.id = 'jquery_script_element';
				jquery.type = 'text/javascript';
				jquery.src = 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js';
				document.body.appendChild(jquery);
			}

			return false;
		}
		// 簡轉繁核心
		if (document.location.host === 's.163.com' || document.location.host === 'd.163.com') {
			if (!document.getElementById('TongWen_to_TW_instantly')) {
				console.info('inject to_TW_instantly');
				var to_TW_instantly = document.createElement('script');
				to_TW_instantly.id = 'TongWen_to_TW_instantly';
				to_TW_instantly.type = 'text/javascript';
				to_TW_instantly.src = 'http://d3clan.tw/static/js/plugin/TongWen/TongWen.js';
				document.body.appendChild(to_TW_instantly);
			}

			if (window.TongWen === undefined) {
				return false;
			} else {
				return true;
			}
		} else {
			return true;
		}

		return false;
	};

	//# 實例化
	if (window.jQuery === undefined) {
		var Interval = setInterval(function() {
			if (validate()) {
				app.main_exec();
				clearInterval(Interval);
			} else {
				console.info('處理中');
			}
		}, 130);
	} else {
		app.main_exec();
	}

})(window);