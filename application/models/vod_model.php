<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Vod_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	public function get_vod( $setting = array() ) {

		$this->load->helper( 'string' );

		$setting['limit']  = ( ! empty( $setting['limit'] ) ) ? $setting['limit'] : 10;
		$setting['offset'] = ( ! empty( $setting['offset'] ) ) ? $setting['offset'] : 0;

		// 為了抓取 youku優酷略縮圖
		$this->load->library( 'simple_html_dom' );

		// query builder
		$this->db->where( 'first', 1 );
		$this->db->where( 'fid', 56 );
		$this->db->limit( $setting['limit'], $setting['offset'] );
		$this->db->order_by( 'dateline', 'desc' );
		// $sql = "SELECT * FROM `d3bbs_forum_post` AS post WHERE `first` = 1 AND `fid` = 56 ORDER BY `dateline` DESC LIMIT 10;";
		$sql = $this->db->get( 'd3bbs_forum_post' );
		$result = $sql->result_array();

		// 檢測首篇影片為哪種平台的影片, 抓取略縮圖, 以及其唯一識別碼.
		foreach ( $result as $index => $vod ) {
			$result[$index]['subject_thumb'] = string_cut( $vod['subject'], 15 );  

			// 辨識 youtube
			if ( preg_match( '@\[youtube\](.*)\[\/youtube\]@m', $vod['message'], $vod_type ) ) {
				$result[$index]['first_video_type'] = 'youtube';
				$result[$index]['first_video_code'] = $vod_type[1];
				$result[$index]['first_video_thumb'] = "http://i".rand( 1, 4 ).".ytimg.com/vi/{$vod_type[1]}/1.jpg";
			}
			// 辨識 youku優酷
			elseif ( preg_match( '@\[youku\](.*)\[\/youku\]@m', $vod['message'], $vod_type ) ) {
				$result[$index]['first_video_type'] = 'youku';
				$result[$index]['first_video_code'] = $vod_type[1];

				// 抓取 youku優酷略縮圖
				$DOM = file_get_html( "http://v.youku.com/player/getPlayList/VideoIDS/{$vod_type[1]}" );

				$youku = json_decode( $DOM->plaintext, true );
				$result[$index]['first_video_thumb'] = $youku['data']['0']['logo'];
			}
			// 無法辨識, 移除.
			else {
				$result[$index] = NULL;
				unset( $result[$index] );
			}
		}

		return $result;
	}

}
//
//
