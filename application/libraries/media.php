<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

/**
 * 
 */
class Media {
	function __construct() {
		// code...
	}

	/**
	 * [embed_flash description]
	 * @param  array  $setting [description]
	 * @return [type]          [description]
	 */
	public function embed_flash( $setting = array() ) {

		$setting['width']  = ( ! is_null( $setting['width'] ) ) ? $setting['width'] : '100%';
		$setting['height'] = ( ! is_null( $setting['height'] ) ) ? $setting['height'] : '100%';
		$setting['class']  = ( ! is_null( $setting['class'] ) ) ? $setting['class'] : '';
		$setting['src']    = ( ! is_null( $setting['src'] ) ) ? $setting['src'] : NULL;

		$output = "<embed width='{$setting['width']}' height='{$setting['height']}' class='{$setting['class']}' type='application/x-shockwave-flash' src='{$setting['src']}' allowscriptaccess='always' allowfullscreen='true' wmode='transparent'>";
	
		return $output;
	}

	/**
	 * 簡單依影片形態創造出內嵌視頻元素
	 *
	 * @param array   $setting [description]
	 * @return string          組合好的embed或object元素
	 */
	public function embed_vod( $setting = array() ) {

		$setting['type']    = ( ! empty( $setting['type'] ) ) ? $setting['type'] : '';
		$setting['code']    = ( ! empty( $setting['code'] ) ) ? $setting['code'] : '';
		$setting['width']   = ( ! empty( $setting['width'] ) ) ? $setting['width'] : 400;
		$setting['height']  = ( ! empty( $setting['height'] ) ) ? $setting['height'] : 300;
		$setting['channel'] = ( ! empty( $setting['channel'] ) ) ? $setting['channel'] : NULL;

		switch ( $setting['type'] ) {
		case 'justin':
		case 'justintv':
		case 'jtv':
			if ( empty( $setting['channel'] ) ) return "error_media_embed_channel";
			$embed_string = "
					<object height='{$setting['height']}' width='{$setting['width']}' type='application/x-shockwave-flash' style='background: #000000' id='live_embed_player_flash' data='http://www.justin.tv/widgets/live_embed_player.swf?channel={$setting['channel']}'>
						<param name='allowFullScreen' value='true' />
						<param name='allowScriptAccess' value='always' />
						<param name='allowNetworking' value='all' />
						<param name='movie' value='http://www.justin.tv/widgets/live_embed_player.swf' />
						<param name='flashvars' value='channel={$setting['channel']}&auto_play=true&start_volume=100' />
					</object>
				";
			break;
		case 'ustream':
			if ( empty( $setting['channel'] ) ) return "error_media_embed_channel";
			$embed_string = "<iframe height='{$setting['height']}' width='{$setting['width']}' src='http://www.ustream.tv/embed/{$setting['channel']}' scrolling='no' frameborder='0' style='border: 0px none transparent;'></iframe>";
			break;
		case 'own3d':
			if ( empty( $setting['channel'] ) ) return "error_media_embed_channel";
			$embed_string = "<iframe height='{$setting['height']}' width='{$setting['width']}' frameborder='0' src='http://www.own3d.tv/liveembed/{$setting['channel']}?autoPlay=true'></iframe>";
			break;
		case 'youtube':
			if ( empty( $setting['code'] ) ) return "error_media_embed_code";
			$embed_string = "<iframe width='{$setting['width']}' height='{$setting['height']}' src='http://www.youtube.com/embed/{$setting['code']}?hd=1' frameborder='0' allowfullscreen></iframe>";
			break;
		case 'youku':
			if ( empty( $setting['code'] ) ) return "error_media_embed_code";
			$embed_string = "<object width='{$setting['width']}' height='{$setting['height']}'><param name='movie' value='http://player.youku.com/player.php/sid/{$setting['code']}/v.swf'></param><param name='wmode' value='transparent'></param><embed src='http://player.youku.com/player.php/sid/{$setting['code']}/v.swf' quality='high' width='{$setting['width']}' height='{$setting['height']}' align='middle' allowScriptAccess='sameDomain' type='application/x-shockwave-flash'></embed></object>";
			break;
		default:
			return "error_media_embed_type";
		}

		return $embed_string;
	}

	/**
	 * 內嵌直播媒體聊天室
	 *
	 * @param array   $setting [description]
	 * @return [type]          [description]
	 */
	public function embed_chatroom( $setting = array() ) {

		$setting['type']    = ( ! empty( $setting['type'] ) ) ? $setting['type'] : '';
		$setting['width']   = ( ! empty( $setting['width'] ) ) ? $setting['width'] : 320;
		$setting['height']  = ( ! empty( $setting['height'] ) ) ? $setting['height'] : 600;
		$setting['channel'] = ( ! empty( $setting['channel'] ) ) ? $setting['channel'] : NULL;

		if ( empty( $setting['type'] ) ) {
			return "error_media_embed_type";
		}

		switch ( $setting['type'] ) {
		case 'justin':
		case 'justintv':
		case 'jtv':
			$embed_string = "<iframe frameborder='0' width='{$setting['width']}' height='{$setting['height']}' style='min-height:330px ;background:#fff;border:0;' src='http://zh-tw.twitch.tv/chat/embed?channel={$setting['channel']}'></iframe>";
			break;
		case 'ustream':
			$embed_string = "<iframe width='{$setting['width']}' height='{$setting['height']}' scrolling='no' frameborder='0' style='border: 0px none transparent;' src='http://www.ustream.tv/socialstream/{$setting['channel']}'></iframe>";
			break;
		case 'own3d':
			$embed_string = "ownd3不支持聊天室.";
			break;
		default:
			return "error_media_embed_type";
		}

		return $embed_string;
	}
}

//
