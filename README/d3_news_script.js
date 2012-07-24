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
	
	/**
	 * 字詞轉換索引表
	 * @type {Array}
	 */
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
		,{orig: /視頻/mg, to: '影片'}
		,{orig: /菜單/mg, to: '選單'}
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
	];

	termList.StarCraft = [
		{orig: /錄像/mg, to: 'Replay'}
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
		,{orig: /二(餅|柄)/mg, to: '武僧'}
		,{orig: /獵魔人/mg, to: '狩魔獵人'}
		,{orig: /涅法雷姆之力/mg, to: '涅法雷姆之勇'}
		,{orig: /涅法雷姆勇氣/mg, to: '涅法雷姆之勇'}
		,{orig: /奈法蘭[的之]?勇氣?/mg, to: '涅法雷姆之勇'}
		,{orig: /奈非天(勇氣)?/mg, to: '涅法雷姆之勇'}
		,{orig: /暗黑破壞神 ?3/mg, to: '暗黑破壞神III'}
		,{orig: /惡棍林登/mg, to: '盜賊林登'}
		,{orig: /秘法強化/mg, to: '秘法加持'}
		,{orig: /宝藏携带者/mg, to: '黃金哥布林'}
		,{orig: /艾莉娜/mg, to: '艾蓮娜'}
		,{orig: /iOS用戶點此觀看/mg, to: ''}
		// 網易暗黑
		,{orig: /凱恩之角影片站/mg, to: ' '}
	];

	// TeSL新聞
	// =============
	code.TESL = function() {
		alert('未作');
	};

	// bz官網新聞
	// =============
	code.twBattleNetSC = function() {
		libMedia.youtube(jQuery('.detail'), 'html');

		var text_content = '';
		text_content = text_content + jQuery('.header-image').html();
		text_content = text_content + jQuery('.detail').html();

		var output = '來源: [url=' + document.location.href + ']Blizzard[/url]' + '\r\n\r\n' + '[html]\r\n' + text_content + '\r\n\r\n[/html]\r\n';

		console.log(output);
	};

	// 網易星際
	// =============
	code.s163 = function() {
		alert('未作');
	};

	// 網易凱恩之角
	// =============
	code.d163 = function() {
		TongWen.convertToTrad(function() {
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

			// 修正必要元素
			jQuery('.maincon.endContent').css({
				width  : 700,
				border : '5px solid yellow'
			});

			var title   = jQuery('h1#h1title').html();
			var content = $root.html();
			
			title   = libTrans.transform(title, termList.common);
			content = libTrans.transform(content, termList.common);
			
			title   = libTrans.transform(title, termList.Diablo);
			content = libTrans.transform(content, termList.Diablo);

			title   = title.replace(/\[(?:影片|視頻)\]/, '');

			if ( $root.find('#d3clan_mark').length == 0 ) {
				content = content + "<p id='d3clan_mark'>本文轉自<a id='fetched' target='_blank' href='"+document.location.href+"'>網易遊戲凱恩之角</a>，並由「暗盟《暗黑破壞神III》電競情報站」進行正體中文化及在地化詞語轉換。</p>";
			}

			jQuery('h1#h1title').html(title);
			$root.html(content);

			libHelper.createCopyArea(title, content);
		});
	};

	// 主程式
	// =============
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

	// utils
	// =============
	libMedia.youtube = function($root, type) {
		if (type === 'bbcode') {
			$root.find('iframe[src*="youtube"]').each(function() {
				var $element = jQuery(this);
				var hash = $element.attr('src').match(/\/embed\/(.*)\?\w?/im);
				hash = hash ? hash[1] : '';
				$element.replaceWith('[/html]\r\n\r\n[youtube]' + hash + '[/youtube]\r\n\r\n[html]');
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

	// 修飾內文以符合台灣用語
	libTrans.transform = function(string, termList) {
		for (var i=0; i < termList.length; i++) {
			var term = termList[i];
			string = string.replace(term.orig, term.to);
		}

		return string;
	};

	// 創造 textarea 讓編輯可以轉co
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

	// 驗證
	// =============
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

	// 實例化
	// =============
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