/*
powered by  wang qi . co m
author:qhq,wxw,miluduo
*/




$(document).ready(function() {
	if($.browser.msie){
		var iever=$.browser.version
	}
	//ie6初始化表单样式
	if(iever=="6.0"){
		$("input[type='button']").addClass("btn");
		$("input[type='submit']").addClass("btn");
		$("input[type='reset']").addClass("btn");
		$("input[type='text']").addClass("inputbg");
		$("textarea").addClass("inputbg");
		$("input[type='password']").addClass("inputbg");
		$("input[type='text']").focus(function(){ 
		$(this).addClass('inputfocus'); 
		}).blur(function(){ 
		$(this).removeClass('inputfocus'); 
		}); 
		$("textarea").focus(function(){ 
		$(this).addClass('inputfocus'); 
		}).blur(function(){ 
		$(this).removeClass('inputfocus'); 
		}); 
		$("input[type='password']").focus(function(){ 
		$(this).addClass('inputfocus'); 
		}).blur(function(){ 
		$(this).removeClass('inputfocus'); 
		}); 
		
		$(".triptop li").hover(function(){
			$(this).addClass("thisli");
		}, function(){
			$(this).removeClass("thisli");	
		});
		$(".triplist li").hover(function(){
			$(this).addClass("thisli");
		}, function(){
			$(this).removeClass("thisli");	
		});
		$(".hotelist tr").hover(function(){
			$(this).addClass("thistr");
		}, function(){
			$(this).removeClass("thistr");	
		});
		
		$("#tripshow .mbox img").each(function(){
			if($(this).width()>690){
				$(this).css({"width":"690px" ,"height":"auto"});
				}
			})
		
	}
	
	$("span[id^=tips]").html("<img src='/style/images/tips.png' align='absmiddle' alt='' />&nbsp;").addClass("tips").hover(function(){
		$(this).css("z-index", "98").append("<div class=tips1>◆</div><div class=tips2>◆</div><p></p>");
		$(this).find("p").html($(this).attr("help"));
		},function(){
		$(this).css("z-index", "0");
		$(this).html("<img src='/style/images/tips.png' alt='' />&nbsp;");
	});
	
	var navIndex=0;
	switch ($(document.body).attr("id")){
		case "scene":
		    navIndex=1;
			break;
		case "news":
			navIndex=2;
			break;
		case "hotel":
			navIndex=3;
			break;
		case "ticket":
			navIndex=4;
			break;
	    case "visa":
		    navIndex=5;
			break;
		case "trip1":
			navIndex=6;
			break;
		case "trip2":
			navIndex=7;
			break;
		case "trip3":
			navIndex=8;
			break;
		case "topic":
			navIndex=9;
			break;
		default:
			navIndex=0;
			break;
	}
	
	$("#navbar a:first").addClass("thisclass");
	$("#navbar a").removeClass("thisclass").eq(navIndex).addClass("thisclass");
	
/*	$("#navbar a").mouseover(function(){
		var l=$(this).offset().left-$("#navbar").offset().left;
		var i=Math.floor(Math.random()*10+1);
		$("#navbar p").stop(true).attr("class","navp"+i).animate({left:l},{easing: 'easeInOutElastic'});
		$("#navbar a").eq(navIndex).removeClass("thisclass");
		$(this).addClass("thisclass");
		}).mouseleave(function(){
			var l=$("#navbar a").eq(navIndex).offset().left-$("#navbar").offset().left;
			$("#navbar p").stop(true).animate({left:l},{easing: 'easeInOutElastic'});
			$(this).removeClass("thisclass");
			$("#navbar a").eq(navIndex).addClass("thisclass");
	});
	$("#navbar a").removeClass("thisclass").eq(navIndex).addClass("thisclass");
	if(navIndex>0){
		var l=$("#navbar a").eq(navIndex).offset().left-$("#navbar").offset().left;
		$("#navbar p").css({left:l});
	}*/
	
	
	
	
	
	//table
	$(".simpleT tr:even").addClass("even");
	$(".simpleT tr").hover(function(){
		$(this).addClass("thistr");
		}, function(){
			$(this).removeClass("thistr");
			})
	$(".simpleT tr td:last-child").css("border-right", "none");
	$(".simpleT th td:last-child").css("border-right", "none");
	
	$(".hotelist li:even").addClass("evenli");
	$(".triplist li:even").addClass("evenli");

   
	$("#tripshow .mbox .titcon").append("<a class='moreR'>全部</a>");
	$("#tripshow .mbox .titcon a.moreR").hover(function(){
		$("#tripshow .mbox .cont dl").attr("style","")
		})
	
})


//整站插入flash，Flash(路径,宽,高)
function Flash(file, w, h)
{document.writeln(" <object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\" http:\/\/fpdownload.macromedia.com\/pub\/shockwave\/cabs\/flash\/swflash.cab#version=9,0,0,0\" width=\"",w,"\" height=\"",h,"\"  align=\"middle\">    <param name=\"allowScriptAccess\" value=\"sameDomain\" \/>    <param name=\"movie\" value=\"",file,"\" \/>    <param name=\"quality\" value=\"high\" \/>    <param name=\"wmode\" value=\"transparent\">    <embed src=\"",file,"\" wmode=\"transparent\" width=\"",w,"\" height=\"",h,"\" quality=\"high\" swLiveConnect=\"true\"  align=\"middle\"  allowScriptAccess=\"sameDomain\" type=\"application\/x-shockwave-flash\" pluginspage=\" http:\/\/ www.macromedia.com\/go\/getflashplayer\" \/>    <\/object>");}

//openWin
function openWin(title, url, w, h, level, drag, scrol) {
    if (!$("#openWin").html()) {
        switch (level) {
        case 1:
            var bg = "whitesha"
            break
        case 2:
            var bg = "shadow"
            break
        default:
            var bg = "nosha"
        }
		var winH=$(window).height();
		var winW=$(document).width();
		w=(w<winW)?w:(winW-80);
		h=(h<winH)?h:(winH-80);
		
        $("body").append("<div class=" + bg + "></div>");
        $("." + bg).css("height", $(document).height());
        $("." + bg).fadeIn();
        $("body").append("<table id=openWin cellpadding=0 cellspacing=0 border=0><tr><td class=tl></td><td class=tm></td><td class=tr></td></tr><tr><td class=ml></td><td class=mm></td><td class=mr></td></tr><tr><td class=bl></td><td class=bm></td><td class=br></td></tr>");
        $("#openWin .mm").append("<div class=tipbox><div class=titcon><em></em></div><div class=cont></div></div>");
        $("#openWin .tipbox").append("<div class=closethis>X</div>");
        $("#openWin").css({
            "width": w,
            "margin-left": ( - 1) * w / 2,
			"left":$(window).width()/2
        });
        $("#openWin .tipbox").css({
            "width": w - 16
        });
        if (h) {
            $("#openWin").css("height", h);
			$("#openWin").animate({top:($(window).height()-h)/2});
            $("#openWin .tipbox .cont").css("height", h - 40);
        }
        if (title) {
            $("#openWin .tipbox .titcon em").html(title);
        } else {
            $("#openWin .tipbox .titcon").css("display", "none");
        }
        if (url) {
			if(scrol){
				var scrolling="no";
				}else{
				var scrolling="auto";	
					}
            var tipiframe = '<iframe src="' + url + '" width="' + (w - 36) + 'px"  height="' + (h - 50) + 'px" frameborder=0 scrolling="' + scrolling + '"></iframe>';
			$("#openWin .cont").html("正在加载。。。");
            $("#openWin .cont").append(tipiframe);
            $("#openWin iframe").load(function() {
				$("#openWin .cont").html(tipiframe);
            })
        }
        $(".closethis").click(function() {
            $("." + bg).remove();
            $("#openWin").remove()
        });
        if (!drag) {
            var _move = false;
            var _x,_y;
			$("#openWin .tipbox .titcon").css("cursor","move");
            $("#openWin .tipbox .titcon").mousedown(function(e) {
                _move = true;
                _x = e.pageX - parseInt($("#openWin").css("left"));
                _y = e.pageY - parseInt($("#openWin").css("top"));
                //$("#openWin").fadeTo(20, 0.8)
            });
            $(document).mousemove(function(e) {
                if (_move) {
                    var x = e.pageX - _x;
                    var y = e.pageY - _y;
                    $("#openWin").css({
                        top: y,
                        left: x
                    })
                }
            }).mouseup(function() {
                _move = false;
                //$("#openWin").fadeTo("fast", 1)
            })
        }
    }
}


//check
function creatCheck(){
	$("form dd").each(function(e){
		var dd=$(this);
		var inp=$(dd).find("textarea:first").length>0?$(dd).find("textarea:first"):($(dd).find("select:first").length>0?$(dd).find("select:first"):$(dd).find("input:first"));
		var check=$(dd).attr("check");
		var required=$(dd).find("span").hasClass("required");
		var error=$(dd).attr("error")?$(dd).attr("error"):"<img src='/images/x.png' />";
		var pass=$(dd).attr("pass")?$(dd).attr("pass"):"v";
		var plus=$(dd).attr("plus");
		var tip=$(dd).attr("tip");
		var tip2=$(dd).attr("tip2")?$(dd).attr("tip2"):$(dd).attr("tip");
			if(check||required||plus||tip||tip2)
			{
				$(dd).append("<span class='tip"+e+"'></span>");
				$(".tip"+e).html(tip);
				$(inp).focus(function()
									  {
						$(".tip"+e).removeClass("required").html(tip2);
					}).blur(function(){
						checkForm(inp,check,required,error,pass,plus,e)
					});
			}
	});
}

function clientCheck(){
	var reVal;
	$("form dd").each(function(e){
		try{
				reVal="";
				var dd=$(this);
				var inp=$(dd).find("textarea:first").length>0?$(dd).find("textarea:first"):($(dd).find("select:first").length>0?$(dd).find("select:first"):$(dd).find("input:first"));
				var check=$(dd).attr("check");
				var required=$(dd).find("span").hasClass("required");
				var error=$(dd).attr("error")?$(dd).attr("error"):"<img src='/images/x.png' />";
				var pass=$(dd).attr("pass")?$(dd).attr("pass"):"v";
				var plus=$(dd).attr("plus");
				if(check||required||plus)
				{
					if(!checkForm(inp,check,required,error,pass,plus,e))
					{
						throw false;
					}
				}
			}
		catch(i){
			reVal="1";
			return i;
			}
			
		//return true;
	});
	if(reVal=="1")
	{
		return false;
	}
	else
	{
		return true;
	}
  
};

function checkForm(obj,check,required,error,pass,plus,e){
	var objVal=$(obj).val();
	if(objVal==""&&required){
		$(".tip"+e).addClass("required").html("<img src='/images/x.png' />不能为空！");
		return false;
	}
	if(objVal!=""){
		if(check){
			var reg,n,func;
			switch (check){
				case "num":
					reg=new RegExp("^[0-9]*$");
					break;
				case "han":
					reg=new RegExp("^[\u4E00-\u9FA5]+$");
					break;
				case "name":
					reg=new RegExp("^\w+$");
					break;		
				case "len4":
					if(!checkLen(objVal,4)){
						func="字母不小于4位汉字不小于2位";
						}
					break;
				case "len6":
					if(!checkLen(objVal,6)){
						func="不能小于6位";
						}
					break;
				case "url":
					reg=new RegExp("^[a-zA-z]+://[^\s]+$");
					break;
				case "email":
					if(objVal) reg=new RegExp("^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$");
					break;
				case "phone":
					reg=new RegExp("(^(0[0-9]{2,3}-)?[0-9]{7,8}(-[0-9]{1,4})?$)|(^0?1[358][0-9]{9}$)|(^[48]00(-)?[0-9]{7}$)");
					break;
				case "pwagain":
					if(!checkPw(obj,e)){
						func="两次输入密码不一致！"
						}
					break;
				case "drag":
					if ($("#spanDrag").html()==("请把横条拖动到右边")) {
						$("#spanDrag").addClass("required").html("<img src='/images/x.png' />"+"不拖过来不行哦");
						return false
					}else{
						pass="不错"
						}
					break;
	
				}	
			if(reg){
					if(!reg.test(objVal)){
						$(".tip"+e).addClass("required").html("<img src='/images/x.png' />"+error);
						return false;
					}
				}
			if(func){
						$(".tip"+e).addClass("required").html("<img src='/images/x.png' />"+func);
						return false;
				}
			
			}
			
		if(plus){
			if(plus=="username"){
					CheckUserName(obj,e);
					if($(".tip"+e).html()=="x"){
						$(".tip"+e).addClass("required").html("<img src='/images/x.png' />此用户名已存在！")
						 return false;
					}else if($(".tip"+e).html()=="v"){
						$(".tip"+e).remove("required").html("<img src='/images/v.png' />本用户名可以注册")
						 return true;
						}
				}
			if(plus=="code"){
					CheckCode(obj,e);
					if($(".tip"+e).html()=="x"){
						$(".tip"+e).addClass("required").html("<img src='/images/x.png' />验证码错误")
						 return false;
					}else if($(".tip"+e).html()=="v"){
						$(".tip"+e).remove("required").html("<img src='/images/v.png' />");
						 return true;
						}
				}
			if(plus=="scate"){
					return checksCate(obj,e);
				}		
			}
			
			
		
		if(pass=="v"){
			$(".tip"+e).removeClass("required").html("<img src='/images/v.png' />");
		}else{
			$(".tip"+e).removeClass("required").html(pass);
			}
	}
	
	return true;
}
function checkPw(obj,e){
	return $(obj).val()==$(obj).parent().parent().prev().find("dd").find("input:first").val()
}
function checkLen(objVal,n){
	return len(objVal)>=n;
}
function len(s) {
	if(s=="") return 0;
	var l = 0;
	var a = s.split("");
	for (var i=0;i<a.length;i++) 
	{
 		if (a[i].charCodeAt(0)<299) {
  		l++;
 		}
		else 
		{
  		l+=2;
 		}
	}
	return l;
} 
function checksCate(){
    var smallCate=document.getElementById("sel");    
    var list="";
	if(smallCate!=null)
	{
		for(var i=0;i<smallCate.options.length;i++)
		{
			if(i==0)
				list=list+smallCate.options[i].value;
			else
			  list=list+","+smallCate.options[i].value;
	
		}  
	}
    if(list.length>0)
    {    
       $("#hfSmallCateIDList").val(list);
    }
    else
    {
       $("#mess_Small").html("＊<img src='/images/x.png' />行业小类不能为空!");
	    return false;
    }
	 $("#mess_Small").html("＊<img src='/images/v.png' />");
	
    return true;	
}
function checkDrag(){
	 var wq_move=false;
	 var drag_x;
	$("#wq_srollx #wq_drag").mousedown(function(e) {
           drag_x = e.pageX - parseInt($("#wq_srollx #wq_drag").css("left"));
		   wq_move=true;
    });
	$(document).mousemove(function(e) {
		 if (wq_move) {
           var dragx = e.pageX - drag_x;
		   var dragLeft=$("#wq_srollx").width()-$("#wq_srollx #wq_drag").width()
		   if(dragx>0&&dragx<=(dragLeft-10)){
           $("#wq_srollx #wq_drag").css("left", dragx);
		   }
		   if(dragx>(dragLeft-10)){
			   $("#wq_srollx #wq_drag").css("left", dragLeft);
			   $("#spanDrag").addClass("required").html("<img src='/images/v.png' />"); 
			   wq_move = false;
			   }else{
				$("#spanDrag").removeClass("required").html("请把横条拖动到右边");    
			}
			if(dragx<10){
			   $("#wq_srollx #wq_drag").css("left", 0);
		 	}
		}
	}).mouseup(function() {
                wq_move = false;
	});
}
function CheckUserName(obj,e) {
	$.ajax({
		type: "POST",
		url: "/member/CheckUserName.aspx",
		data: "userName=" +escape($(obj).val()) + "&&" + Math.random(),
		dataType: 'html',
		async: false,
		cache: false,
		beforeSend: function() {    
		},
		success: function(msg) {
			if (msg == "0")
			{
				$(".tip"+e).html("x");
				
			} 
			else
			{ 
				$(".tip"+e).html("v");
			} 
		},
		error: function() { }
	});		
}
function CheckCode(obj,e) {
	$.ajax({
		type: "POST",
		url: "/member/checkUserCode.aspx",
		data: "",
		dataType: 'html',
		async: false,
		cache: false,
		beforeSend: function() {    
		},
		success: function(msg) {
			if (msg != $(obj).val())
			{
				$(".tip"+e).html("x");
				
			} 
			else
			{ 
				$(".tip"+e).html("v");
			} 
		},
		error: function() { }
	});		
}
$()

//Tab
function tab(a,b,c){
	var d=$(a).parent().find("li");
	var e=d.length+c;
	var g=b-c;
	var f;
	for(i=c ; i<e ;i++){
		f=e-i-1;
		$(d).eq(f).attr("class","Off");
		$("#etrip_"+i).css("display", "none");
	}
	$(d).eq(g).attr("class","On");
	$("#etrip_"+b).css("display", "block");
}


//share
function shareMe(title,sum,url,pic){
	
	$("#share").html("分享至：<a href='javascript:void(0)' name='qq' title='转播到腾讯微博'><img src='/images/share/qq.png' align='absmiddle' border='0' alt='转播到腾讯微博' /></a> <a href='javascript:void(0)' name='sina' title='转播到新浪微博'><img src='/images/share/sina.gif' align='absmiddle' border='0' alt='转播到新浪微博' /></a> <a href='javascript:void(0)' name='qzone' title='分享到QQ空间'><img src='/images/share/qzone.gif' align='absmiddle' border='0' alt='分享到QQ空间' /></a> <a href='javascript:void(0)' name='renren' title='分享到人人网'><img src='/images/share/renren.gif' align='absmiddle' border='0' alt='分享到人人网' /></a> <a href='javascript:void(0)' name='163' title='转播到网易微博'><img src='/images/share/163.gif' align='absmiddle' border='0' alt='转播到网易微博' /></a> <a href='javascript:void(0)' name='kaixin' title='分享到开心网'><img src='/images/share/kaixin.gif' align='absmiddle' border='0' alt='分享到开心网' /></a> <a href='javascript:void(0)' name='douban' title='分享到豆瓣'><img src='/images/share/douban.png' align='absmiddle' border='0' alt='分享到豆瓣' /></a> <a href='javascript:void(0)' name='qqpeng' title='分享到腾讯朋友'><img src='/images/share/pengyou.png' align='absmiddle' border='0' alt='分享到腾讯朋友' /></a> <a href='javascript:void(0)' name='baidu' title='分享到百度i贴吧'><img src='/images/share/baidu.png' align='absmiddle' border='0' alt='分享到百度i贴吧' /></a> <a href='javascript:void(0)' name='taobao' title='分享到淘江湖'><img src='/images/share/taobao.png' align='absmiddle' border='0' alt='分享到淘江湖' /></a>");

	$("#share a").click(function(){
			var _site = '';//网站地址
			var _name=$(this).attr("name");
			var _t = title;//信息标题
			var _sum= sum;//信息简介
			var _url = url;//地址
			var wqpic=pic;//图片
			if(wqpic){
				var _pic = encodeURI(wqpic);
				switch (_name){
					case "qq":
						_u = 'http://v.t.qq.com/share/share.php?url='+_url+'&site='+_site+'&pic='+_site+_pic+'&title='+_t+_sum;
						break;
					case "sina":
						_u = 'http://v.t.sina.com.cn/share/share.php?url='+_url+'&pic='+_site+_pic+'&title='+_t+_sum;
						break;
					case "qzone":
						_u = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+_url+'&title='+_t+'&summary='+_sum+'&imgPanel='+_site+_pic;
						break;
					case "renren":
						_u = 'http://share.renren.com/share/buttonshare?link='+_url+'&title='+_t;
						break;
					case "163":
						_u = 'http://t.163.com/article/user/checkLogin.do?link='+_url+'&title='+_site+'&info='+_t+_sum+' '+_url+'&images='+_site+_pic;
						break;
					case "kaixin":
						_u = 'http://www.kaixin001.com/repaste/bshare.php?rurl='+_url+'&rtitle='+_t+'&rcontent='+_sum;
						break;
					case "douban":
						_u = 'http://shuo.douban.com/!service/share?image='+_site+_pic+'&href='+_url+'&name='+_t;
						break;
					case "qqpeng":
						_u = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?to=pengyou&url='+_url+'&title='+_t+'&summary='+_sum+'&imgPanel='+_site+_pic;
						break;
					case "baidu":
						_u = 'http://tieba.baidu.com/i/sys/share?link='+_url+'&title='+_t+'&content='+_sum;
						break;
					case "taobao":
						_u = 'http://share.jianghu.taobao.com/share/addShare.htm?title='+_t+'&url='+_url+'&content='+_sum;
						break;
					}
			
			
			}else{
				
				switch (_name){
					case "qq":
						_u = 'http://v.t.qq.com/share/share.php?url='+_url+'&site='+_site+'&title='+_t+_sum;
						break;
					case "sina":
						_u = 'http://v.t.sina.com.cn/share/share.php?url='+_url+'&title='+_t+_sum;
						break;
					case "qzone":
						_u = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+_url+'&title='+_t+'&summary='+_sum;
						break;
					case "renren":
						_u = 'http://share.renren.com/share/buttonshare?link='+_url+'&rtitle='+_t;
						break;
					case "163":
						_u = 'http://t.163.com/article/user/checkLogin.do?link='+_url+'&title='+_site+'&info='+_t+_sum+' '+_url;
						break;
					case "kaixin":
						_u = 'http://www.kaixin001.com/repaste/bshare.php?rurl='+_url+'&rtitle='+_t+'&rcontent='+_sum;
						break;
					case "douban":
						_u = 'http://shuo.douban.com/!service/share?image=&href='+_url+'&name='+_t;
						break;
					case "qqpeng":
						_u = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?to=pengyou&url='+_url+'&title='+_t+'&summary='+_sum;
						break;
					case "baidu":
						_u = 'http://tieba.baidu.com/i/sys/share?link='+_url+'&title='+_t+'&content='+_sum;
						break;
					case "taobao":
						_u = 'http://share.jianghu.taobao.com/share/addShare.htm?title='+_t+'&url='+_url+'&content='+_sum;
						break;
					}
			
			
			}
			if(_u){
			window.open( _u,'', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no' );
			}
		})
	}


//cookie
jQuery.cookie = function(name, value, options) {
	if (typeof value != 'undefined') {
		options = options || {};
		if (value === null) {
			value = '';
			options = $.extend({}, options);
			options.expires = -1;
		}
		var expires = '';
		if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
			var date;
			if (typeof options.expires == 'number') {
				date = new Date();
				date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
			} else {
				date = options.expires;
			}
			expires = '; expires=' + date.toUTCString();
		}
		var path = options.path ? '; path=' + (options.path) : '';
		var domain = options.domain ? '; domain=' + (options.domain) : '';
		var secure = options.secure ? '; secure' : '';
		document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
		} else {
			var cookieValue = null;
			if (document.cookie && document.cookie != '') {
			var cookies = document.cookie.split(';');
			for (var i = 0; i < cookies.length; i++) {
				var cookie = jQuery.trim(cookies[i]);
				if (cookie.substring(0, name.length + 1) == (name + '=')) {
					cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
					break;
				}
			}
		}
		return cookieValue;
	}
};


jQuery.extend( jQuery.easing,
{
	def: 'easeOutQuad',
	swing: function (x, t, b, c, d) {
		//alert(jQuery.easing.default);
		return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
	},
	easeInQuad: function (x, t, b, c, d) {
		return c*(t/=d)*t + b;
	},
	easeOutQuad: function (x, t, b, c, d) {
		return -c *(t/=d)*(t-2) + b;
	},
	easeInOutQuad: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t + b;
		return -c/2 * ((--t)*(t-2) - 1) + b;
	},
	easeInCubic: function (x, t, b, c, d) {
		return c*(t/=d)*t*t + b;
	},
	easeOutCubic: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t + 1) + b;
	},
	easeInOutCubic: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t + b;
		return c/2*((t-=2)*t*t + 2) + b;
	},
	easeInQuart: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t + b;
	},
	easeOutQuart: function (x, t, b, c, d) {
		return -c * ((t=t/d-1)*t*t*t - 1) + b;
	},
	easeInOutQuart: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
		return -c/2 * ((t-=2)*t*t*t - 2) + b;
	},
	easeInQuint: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t*t + b;
	},
	easeOutQuint: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t*t*t + 1) + b;
	},
	easeInOutQuint: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
		return c/2*((t-=2)*t*t*t*t + 2) + b;
	},
	easeInSine: function (x, t, b, c, d) {
		return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
	},
	easeOutSine: function (x, t, b, c, d) {
		return c * Math.sin(t/d * (Math.PI/2)) + b;
	},
	easeInOutSine: function (x, t, b, c, d) {
		return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
	},
	easeInExpo: function (x, t, b, c, d) {
		return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
	},
	easeOutExpo: function (x, t, b, c, d) {
		return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
	},
	easeInOutExpo: function (x, t, b, c, d) {
		if (t==0) return b;
		if (t==d) return b+c;
		if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
		return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
	},
	easeInCirc: function (x, t, b, c, d) {
		return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
	},
	easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	},
	easeInOutCirc: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
		return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
	},
	easeInElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
	},
	easeOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
	},
	easeInOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
	},
	easeInBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*(t/=d)*t*((s+1)*t - s) + b;
	},
	easeOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	},
	easeInOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158; 
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
	},
	easeInBounce: function (x, t, b, c, d) {
		return c - jQuery.easing.easeOutBounce (x, d-t, 0, c, d) + b;
	},
	easeOutBounce: function (x, t, b, c, d) {
		if ((t/=d) < (1/2.75)) {
			return c*(7.5625*t*t) + b;
		} else if (t < (2/2.75)) {
			return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
		} else if (t < (2.5/2.75)) {
			return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
		} else {
			return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
		}
	},
	easeInOutBounce: function (x, t, b, c, d) {
		if (t < d/2) return jQuery.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
		return jQuery.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
	}
});




