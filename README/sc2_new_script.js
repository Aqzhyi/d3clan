/* 轉新聞專用script code，限制使用 google chrome 瀏覽器。 */
/* 本code同時支持TESL與s.163.com與d.163.com */
/* ctrl+a全選本code，於指定新聞頁面按F12，並於console面版貼入本code按下enter即可 */

(function(window, undefined){

     var youtube_detect = function($root) {
          $root.find('iframe[src*="youtube"]').each(function(){
                              var $element = jQuery(this);
                              var code = $element.attr('src').match(/\/embed\/(.*)/im);
                                   code = code ? code[1] : '';
                              $element.replaceWith('[/html]\r\n\r\n[youtube]'+code+'[/youtube]\r\n\r\n[html]');
                         });
     };

     var main_exec = function(){
          var href = document.location.href;
          /*國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國*/
          /*國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國*/
          /*國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國*/
          if (href.match(/(.*www\.esports\.com\.tw.*|203\.70\.11\.190)/))
          {
               /* TESL */
               /* TESL */
               /* TESL */
               var text_content = '';
               jQuery('.textEditor').each(function(){
                         jQuery(this).find('img').each(function() {
                              var $src = jQuery(this);
                              if ($src.attr('src').match(/^\//)) {
                                   $src.attr('src', 'http://www.esports.com.tw'+$src.attr('src'));
                              }
                         });
                         text_content = text_content + jQuery(this).html();
                    });
            
               var output = '來源: [url='+document.location.href+']TESL[/url]'
               +'\r\n\r\n'
               +'[html]\r\n'
               +text_content
               +'\r\n\r\n[/html]\r\n';

               jQuery('body')
                    .find('#copy_and_paste')
                    .remove()
                    .end()
                    .prepend('<textarea id="copy_and_paste">')
                    .find('#copy_and_paste')
                    .css({width:'100%', height:600, color: '#fff', background:'#000', color: '#fff', zIndex: 1000, position: 'relative'})
                    .text(output)
                    .end()
                    ;
          }
          else if (href.match(/tw\.battle\.net/))
          {
               /* 暴雪台灣官方 */
               /* 暴雪台灣官方 */
               /* 暴雪台灣官方 */
               youtube_detect(jQuery('.detail'));

               var text_content = '';
               text_content = text_content + jQuery('.header-image').html();
               text_content = text_content + jQuery('.detail').html();
            
               var output = '來源: [url='+document.location.href+']Blizzard[/url]'
               +'\r\n\r\n'
               +'[html]\r\n'
               +text_content
               +'\r\n\r\n[/html]\r\n';
            
            
               console.log(output);
          }
          else if (href.match(/.*[ds]\.163\.com.*/))
          {
               /* 網易星際 */
               /* 網易星際 */
               /* 網易星際 */
               TongWen.convertToTrad(function(){
                    /* 用詞翻譯設定 */
                    var termList = [
                         /* 一般 */
                         {orig: /，?轉載請注明出處！?/mg, to: ''}
                         ,{orig: /-?-?S\.163\.COM(編制|制作|編譯)/mg, to: ''}
                         ,{orig: /--詳細報導/mg, to: ''}
                         ,{orig: /網易出品：?/mg, to: ''}
                         ,{orig: /這篇博客/mg, to: '這篇文章'}
                         ,{orig: /網吧/mg, to: '網咖'}
                         ,{orig: /社區/mg, to: '社群'}
                         ,{orig: /網絡/mg, to: '網路'}
                         ,{orig: /點擊/mg, to: '點選'}
                         ,{orig: /視頻/mg, to: '影片'}
                         ,{orig: /采集/mg, to: '採集'}
                         ,{orig: /補丁/mg, to: '更新'}
                         ,{orig: /采訪/mg, to: '採訪'}
                         ,{orig: /游戲/mg, to: '遊戲'}
                         ,{orig: /報道/mg, to: '報導'}
                         ,{orig: /地域/mg, to: '地區'}
                         ,{orig: /戰斗/mg, to: '戰鬥'}
                         ,{orig: /其余/mg, to: '其餘'}
                         ,{orig: /注冊/mg, to: '註冊'}
                         ,{orig: /標志/mg, to: '標誌'}
                         ,{orig: /登陸/mg, to: '登入'}
                         ,{orig: /薪金/mg, to: '薪資'}
                         ,{orig: /我的文檔/mg, to: '我的文件夾'}
                         ,{orig: /谷歌/mg, to: 'Google'}
                         ,{orig: /干什麼/mg, to: '幹什麼'}
                         ,{orig: /鼠標/mg, to: '滑鼠游標'}
                         ,{orig: /注：/mg, to: '註：'}
                         ,{orig: /魷魚杯/mg, to: '魷魚盃'}
                         ,{orig: /剩余/mg, to: '剩餘'}
                         ,{orig: /幾率/mg, to: '機率'}
                         ,{orig: /短信/mg, to: '簡訊'}
                         ,{orig: /賬戶/mg, to: '帳號'}
                         ,{orig: /信息/mg, to: '訊息'}
                         ,{orig: /防御/mg, to: '防禦'}
                         ,{orig: /博客/mg, to: 'Blog'}
                         ,{orig: /教程/mg, to: '教學'}
                         ,{orig: /網上/mg, to: '網路上'}
                         ,{orig: /雜志/mg, to: '雜誌'}
                         ,{orig: /網游/mg, to: '網路遊戲'}
                         ,{orig: /關系/mg, to: '關係'}
                         ,{orig: /軟妹幣/mg, to: '人民幣'}
                         ,{orig: /RMB/mg, to: '人民幣'}
                         ,{orig: /干這行/mg, to: '幹這行'}
                         ,{orig: /70後/mg, to: '六年級生'}
                         ,{orig: /80後/mg, to: '七年級生'}
                         ,{orig: /90後/mg, to: '八年級生'}
                         ,{orig: /沖動/mg, to: '衝動'}
                         ,{orig: /婪欲/mg, to: '婪慾'}
                         ,{orig: /脫銷/mg, to: '缺貨'}
                         ,{orig: /北京時間/mg, to: '台北時間'}
                         ,{orig: /  /mg, to: ''}
                         ,{orig: /&nbsp;&nbsp;&nbsp;&nbsp;/mg, to: ''}

                         /* 資訊用詞 */
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
                         // ,{orig: /注入/mg, to: 'injection'}
                         ,{orig: /帶寬/mg, to: '頻寬'}
                         ,{orig: /硬件/mg, to: '硬體'}
                         ,{orig: /光盤/mg, to: '光碟'}
                         ,{orig: /硬盤/mg, to: '硬碟'}
                         ,{orig: /U盤/mg, to: 'USB'}
                         ,{orig: /系統盤/mg, to: '系統槽'}
                         ,{orig: /回車/mg, to: 'enter'}
                         ,{orig: /進程/mg, to: '處理程序'}
                         ,{orig: /註銷/mg, to: '登出'}
                         ,{orig: /激活/mg, to: '啟動'}
                         ,{orig: /互聯網/mg, to: '網路'}
                         ,{orig: /冰封王座/mg, to: '寒冰霸權'}
                      
                         /* 遊戲用詞 */
                         ,{orig: /BB/mg, to: '寵物'}

                         /* 敏感用詞 */
                         ,{orig: /國服/mg, to: '中國伺服器'}
                         ,{orig: /中國香港/mg, to: '香港'}
                         ,{orig: /臺灣/mg, to: '台灣'}
                         ,{orig: /台灣地區/mg, to: '台灣'}
                         ,{orig: /中國台灣/mg, to: '台灣'}
                         ,{orig: /我國/mg, to: '中國'}
                      
                         /* 星海用詞 */
                         ,{orig: /錄像/mg, to: 'Replay'}
                         ,{orig: /星際爭霸/mg, to: '星海爭霸'}
                         ,{orig: /星海爭霸 ?2/mg, to: '星海爭霸II'}
                         ,{orig: /星際/mg, to: '星海'}
                         ,{orig: /國家杯/mg, to: '國家盃'}
                         ,{orig: /洲際杯/mg, to: '洲際盃'}
                         ,{orig: /S\.163\.COM/mg, to: '網易遊戲'}
                         ,{orig: /安提嘉星港/mg, to: '安提卡船廠'}

                         /* d3用詞 */
                         ,{orig: /公共遊戲/mg, to: '公開遊戲'}
                         ,{orig: /二(餅|柄)/mg, to: '武僧'}
                         ,{orig: /獵魔人/mg, to: '狩魔獵人'}
                         ,{orig: /涅法雷姆之力/mg, to: '涅法雷姆之勇'}
                         ,{orig: /涅法雷姆勇氣/mg, to: '涅法雷姆之勇'}
                         ,{orig: /奈法蘭[的之]?勇氣?/mg, to: '涅法雷姆之勇'}
                         ,{orig: /奈非天(勇氣)?/mg, to: '涅法雷姆之勇'}
                         ,{orig: /暗黑破壞神 ?3/mg, to: '暗黑破壞神III'}
                         ,{orig: /秘法強化/mg, to: '秘法加持'}
                         ,{orig: /宝藏携带者/mg, to: '黃金哥布林'}
                         ,{orig: /iOS用戶點此觀看/mg, to: ''}
                         ,{orig: /安全令牌?/mg, to: '驗證器'}

                         /*網易暗黑*/
                         ,{orig: /凱恩之角影片站/mg, to: ' '}

                         /* 蟲族 */
                         ,{orig: /虫/mg, to: '蟲'}
                         ,{orig: /跳蟲/mg, to: '異化蟲'}
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

                         /* 神族 */
                         ,{orig: /星靈/mg, to: '神族'}
                         ,{orig: /狂熱者/mg, to: '狂戰士'}
                         ,{orig: /叉子/mg, to: '狂戰士'}
                         ,{orig: /探機/mg, to: '探測機'}
                         ,{orig: /探針/mg, to: '探測機'}
                         ,{orig: /光子砲/mg, to: '光砲'}

                         /* 人族 */
                         ,{orig: /惡火/mg, to: '惡狼噴火車'}
                         ,{orig: /農民/mg, to: '工兵'}
                         ,{orig: /收割者/mg, to: '死神'}
                         ,{orig: /幽靈兵/mg, to: '幽靈特務'}
                         ,{orig: /矩陣空投/mg, to: '地毯式空投'}
                    ];

                    jQuery.noConflict();
                    jQuery('div.header').remove();
                    jQuery('div.nav').remove();
                    jQuery('div.NTES_nav_').parent().remove();
                    jQuery('#PopWindow').remove();
                    jQuery('div.colR').remove();
                    jQuery('div.colLM').css({width: 'auto'});
                    jQuery('.col-r').remove(); // d3
                   
                    window.$root        = jQuery('.endContent');
                    window.$script_code = jQuery('<script>');
                    window.$page        = jQuery('.endPageNum').detach();
                    window.hasVod       = false;

                    if ( $root.find('embed[src*=youku], embed[src*=pptv]').size() ) {
                         window.hasVod = true;
                    }
                   
                    $root.css({
                              border: '5px solid red',
                              padding: 0,
                              width: 700-10
                         });
                    jQuery('#sc2clan_preview').remove();
                    $root
                         .before('<p id="sc2clan_preview">')
                         .parent()
                         .find('#sc2clan_preview')
                         .html('預覽')
                         .css({fontSize: 24, color: 'red'});
                   
                    var overWidth = $root.find('*').filter(function(index, element) {
                         return jQuery(this).outerWidth() > 700;
                    });

                    // 如果新聞寬度超過了PHPBB的寬度限制...
                    if (overWidth.size()>1) {
                         $script_code.html(
                                   $script_code.html() +
                                   "jQuery(function(){\
                                        jQuery('dl.postprofile:eq(0)').hide();\
                                        jQuery('div.postbody:eq(0)').css('width', '100%');\
                                   });"
                              );
                    }

                    /* 新聞操作，修飾新聞圖片、連結等，以符合星盟期待的樣子 */
                    /* 新聞操作，修飾新聞圖片、連結等，以符合星盟期待的樣子 */
                    /* 新聞操作，修飾新聞圖片、連結等，以符合星盟期待的樣子 */

                    $root.find('table').css({background: '#000'})
                         .find('td,font').css({color: '#fff'})
                         .end()
                         .find('td')
                         .filter(function(){
                              var $element = jQuery(this);
                              return (
                                        ($element.attr('bgcolor'))
                                   );
                         })
                         .attr('bgcolor', '#3c3c3c');
                    $root.find('table.f_table[background]').attr('background', '');
                    $root.find('img').each(function() {
                              var $element = jQuery(this);
                              $element.css({
                                   maxWidth: 700
                              });
                         });

                    // 移掉alink
                    $root.find('a').each(function(){
                              var $element = jQuery(this);
                              var html = $element.html();

                              if (html.match(/原(?:帖|文|推)地址/)) {}
                              else if (html.match(/下載/)) {}
                              else if (html.match(/前往原..?(觀|看|查|尋)./)) {}
                              else if (html!='' && html.length>5) {}
                              else if ($element.is('a[href*=img1.cache]')){}
                              else if ($element.find('img').size()){}
                              else {
                                   jQuery(this).replaceWith(jQuery(this).html());
                              }
                         });

                    $root.find('img[alt=Video]').remove();
                    $root.find('img[alt=访谈]').remove();
                    $root.find('td[width="50%"][align=right]').remove();
                    $root.find('.info').remove();
                    $root.find('.summary').remove();
                    $root.find('.endPageNum').remove();
                    $root.find('td[class=f_right]').filter(function (){ return (jQuery(this).text().match(/視頻/)); }).empty();
                    $root.find('td').filter(function (){ return (jQuery(this).html().match(/(采訪)/img)); }).empty();

                    jQuery('\
                         table[cellspacing="0"][cellpadding="0"][width="600"][bgcolor="#14415e"],\
                         table[cellspacing="0"][cellpadding="0"][width="598"][bgcolor="#14415e"]\
                         ').filter(function() {
                         return (
                                   jQuery(this).css('border-color')=="rgb(38, 98, 136)"
                              );
                    }).remove();

                    $root.find('p').filter(function (){
                              var $element = jQuery(this);
                              return (
                                        ($element.html().match(/S\.[\d]{0,3}\.[com]{0,3}.*轉載.*(注|註)明/img))
                                        ||
                                        ($element.html().match(/(下一頁|上一頁)/))
                                        ||
                                        ($element.html().match(/.*S\.[\d]{0,3}\.[com]{0,3}.*繼續.*?關注.*?/))
                                        ||
                                        ($element.html().match(/.*?請.*?(關注|鎖定).*?S\.[\d]{0,3}\.[com]{0,3}.*?/))
                                        ||
                                        ($element.html().match(/^&gt;&gt;&gt;點此.*/))
                                        ||
                                        ($element.html().match(/^&gt;&gt;&gt;更多相關.*鎖定.*專題/))
                                   );
                         }).remove();

                    $root.find('p').filter(function() {
                              return (
                                        jQuery(this).text().match(/^相關.*：$/)
                                        ||
                                        jQuery(this).text().match(/^詳細.*：$/)
                                        ||
                                        jQuery(this).text().match(/^更多.*：$/)
                                        ||
                                        jQuery(this).text().match(/^賽前.*：$/)
                                   );
                         }).nextAll('p').andSelf().remove();

                    $root.find('#endText:last').html($root.find('#endText:last').children('*:not(a,img)'));

                    // 相容網易圖片播放器
                    $root.find('.nph_gallery').each(function() {
                         jQuery(this).after('<div style="clear: both;"></div>');
                         jQuery(this).replaceWith(
                                   jQuery(this).find('.nph_list_thumb>li').each(function() {
                                        var $this        = jQuery(this);
                                        var orig_img_url = $this.find('i[title=img]').html();
                                        $this.find('img').wrap('<a target="_blank" href="'+orig_img_url+'"></a>');
                                        $this.find('h2,p,i').remove();
                                        $this.css({
                                             float         : 'left',
                                             listStyleType : 'none',
                                             margin        : '3px'
                                        });
                                   })
                              );
                    });

                    $root.find('embed[src*=netease]').each(function() {
                         var $element = jQuery(this);
                         code = $element.attr('src');
                         $element.replaceWith('[/html]\r\n\r\n[tv163]'+code+'[/tv163]\r\n\r\n[html]');
                    });

                    $root.find('embed[src*=youku]').each(function(){
                              var $element = jQuery(this);
                              var code = $element.attr('src').match(/youku\.com\/.*\/sid\/(.*)\//im);
                                  code = code ? code[1] : '';
                              $element.replaceWith('[/html]\r\n\r\n[youku]'+code+'[/youku]\r\n\r\n[html]');
                         });

                    $root.find('embed[src*=pptv]').each(function(){
                              var $element = jQuery(this);
                              var code = $element.attr('src').match(/pptv\.com\/v\/(.*)\.swf/);
                                   code = code ? code[1] : '';
                              $element.replaceWith('[/html]\r\n\r\n[pptv]'+code+'[/pptv]\r\n\r\n[html]');
                         });

                    /* 如果不只一頁的話 */
                    window.$page = window.$page.find('a').filter(function() {
                         return jQuery(this).html().match(/[^(?:上一頁|^下一頁)]/)
                    });

                    /* 新聞操作，修飾內文以符合台灣用語 */
                    var string = $root.html();
                 
                    for (var i=0; i < termList.length; i++)
                    {
                         var term = termList[i];
                      
                         string = string.replace(term.orig, term.to);
                    }

                    string = string.replace(/&nbsp;&nbsp;&nbsp; /img, '');
                 
                    $root.html(string);

                    if ( !window.$tmp$h1 ) window.$tmp$h1 = $root.find('h1').detach();
                   
                    window.$root = $root;
                   
                    /* 輸出BBCODE來源網址 */
                    var output = '來源： [url='+document.location.href+']網易[/url][hr][/hr]'
                                   +'\r\n\r\n'
                                   +'[html]'
                                   +$script_code[0].outerHTML
                                   +jQuery('.endContent').html()
                                   +'[/html]'
                                   +'\r\n\r\n';
                    window.$script_code = $script_code;
                    if (window.hasVod) window.$tmp$h1.text( '(VOD) '+window.$tmp$h1.text() );

                    jQuery('body')
                         .find('#copy_and_paste, #copy_and_paste_title')
                         .remove()
                         .end()
                         .prepend('<textarea id="copy_and_paste">')
                         .find('#copy_and_paste')
                         .css({width:'100%', height:600, color: '#fff', background:'#000'})
                         .text(output)
                         .css({color:'white'})
                         .end()
                         .prepend('<input id="copy_and_paste_title">')
                         .find('#copy_and_paste_title')
                         .css({width:'100%', lineHeight:'22px', color: 'red', background:'#000'})
                         .val(window.$tmp$h1.text());
                   
                    if (window.$page.size()) {
                         jQuery('body')
                              .prepend('<div id="pages">')
                              .find('#pages')
                              .html(window.$page)
                              .find('a')
                              .css({
                                   fontSize: 18,
                                   color: '#0ff',
                                   background: '#000'
                              })

                             
                    }
                 
               });
          }
          /*國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國*/
          /*國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國*/
          /*國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國國*/
     };
  
     if (window.jQuery === undefined)
     {
          var Interval = setInterval(function(){
               if (validate())
               {
                    main_exec();
                    clearInterval(Interval);
               }
               else
               {
                    console.info('處理中');
               }
          }, 130);
     }
     else
     {
          main_exec();
     }
  
     /* 驗證 */
     var validate = function(){
          if (window.jQuery === undefined)
          {
               if (!document.getElementById('jquery_script_element'))
               {
                    console.info('inject jQuery');
                    var jquery = document.createElement('script');
                    jquery.id = 'jquery_script_element';
                    jquery.type = 'text/javascript';
                    jquery.src = 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js';
                    document.body.appendChild(jquery);
               }
            
               return false;
          }
  
          if (document.location.host === 's.163.com' || document.location.host === 'd.163.com')
          {
               /* 簡轉繁核心 */
               if (!document.getElementById('TongWen_to_TW_instantly'))
               {
                    console.info('inject to_TW_instantly');
                    var to_TW_instantly = document.createElement('script');
                    to_TW_instantly.id = 'TongWen_to_TW_instantly';
                    to_TW_instantly.type = 'text/javascript';
                    to_TW_instantly.src = 'http://d3clan.tw/static/js/plugin/TongWen/TongWen.js';
                    document.body.appendChild(to_TW_instantly);
               }
            
               if (window.TongWen === undefined)
               {
                    return false;
               }
               else
               {
                    return true;
               }
          }
          else
          {
               return true;
          }
       
          return false;
     };
  
})(window);