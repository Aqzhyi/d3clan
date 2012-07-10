<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class News_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_circle_loop( $setting = array() ) {
        
        return array(
            'circle_loop_data' => array(
                array(
                    'img'   => '/static/img/unsorted/CTipI.png',
                    'title' => '暗盟《暗黑破壞神III》電競情報站 開張!!',
                    'descr' => '由星盟電競團隊全力開發，全台唯一以《暗黑破壞神III》為主題之專屬網站，提供各類暗黑破壞神的全球新訊、直播頻道、交易平台、約戰中心，與電競賽事等專業服務。',
                    'link'  => '',
                ),
            ),
        );
    }

    /**
     * 取得新聞資訊流條目
     *
     * @param array   $setting [description]
     * @return [type]          [description]
     */
    public function get_flow( $setting = array() ) {

        if ( ! is_array( $setting ) ) {
            unset( $setting );
            $setting = array();
        }

        if ( empty( $setting['fid'] ) or ! is_array( $setting['fid'] ) ) {
            $this->db->where_in( 'fid', array(
                    '44', // 硬體設備評測分享
                    '45', // 週邊設備新聞
                    '54', // 最新消息
                    '55', // 攻略推薦
                    '56', // 精彩視頻
                    // '39', // 約團專區
                    // '40', // 交易專區
                    // '60', // 站務公告
                    // '59', // 網站功能教學
                    // '41', // 暗盟活動專區
                    // '47', // 週邊物品買賣
                )
            );
        }
        else {
            $this->db->where_in( 'fid', $setting['fid'] );
        }

        // $this->db->or_where('fid', '54');

        return $this->db->get( 'd3bbs_forum_thread', 15, 0 )->result_array();
    }
}
