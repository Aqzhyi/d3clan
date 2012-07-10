<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Ocr {

	public function __construct() {
		$this->CI =& get_instance();
	}

	public function parser( $file_info = array() ) {

		$base_path = FCPATH."extention/ocr/";
		$target    = $file_info['full_path'];
		$output    = $file_info['file_path'] . $file_info['file_name'] . '.ocr';

		// 執行 OCR
		exec( "{$base_path}tesseract.exe {$target} {$output} -l chi_tra -psm 3" );

		// 讀取由 ocr 產生出來的原始商品屬性文件
		$handle  = fopen( $output . '.txt', "r" );
		$content = array();

		// 將每一行存入陣列
		while ( !feof( $handle ) ) {
			$buffer    = fgets( $handle, 4096 );
			$content[] = $buffer;
		}
		fclose( $handle );

		// 進行中文字串整理與分析
		$content = $this->_analyze( $content );

		// 將整理好的文字字串存起來供debug
		file_put_contents($output.'.analyzed.txt', $content['analyzed_item']);

		// 返回成功被辨識的商品屬性
		return $content;
	}

	/**
	 * 整理並分析由ocr解出來的中文字串
	 * @param  array  $content [description]
	 * @return array           返回 'analyzed_item' 與 'identifed_item' 索引，
	 *                         分別代表[整理完畢之商品屬性]與[成功被辨識之商品屬性]。
	 */
	private function _analyze( $content = array() ) {
		
		foreach ($content as $key => $value) {
			// 穩定錯字
			$content[$key] = preg_replace(array(
				'@(...擊速度|攻...速度|攻擊...度|攻擊速...|攻...速...)@',
				'@(...中生命...復|擊...生命...復|擊.........回...)@',
				'@(生命...)$@',
				'@恕氣@',
				'@(...秒恢復|...秒...復|.秒恢復)@',
				'@(...落量|掉...量|掉落...)@',
				'@(...性)@',
				'@(...高)@',
				'@(.*空......孔).*@',
				'@(全元...抗(...)?)@',
				'@(護...值|...甲值|護甲...|譜申債)@',
				'@(昍手|菫手)@',
				'@(次敔|泬黥)@',
				'@(...備)@',
				'@(黠|黯|貼|囈占)@',
				'@杪@',
				'@(傷...|...害|偪書)@',
				'@(祕能)@',
				'@([\d]+)\s?(敏...)$@',
				'@([\d]+)\s?(...能)$@',
				'@([\d]+)\s?(...力)$@',
				'@([\d]+)\s?(力...)$@',
				'@(...擊傷...|爆...傷...)@',
				'@(...秒攻...|每...攻...|...秒...擊)@',
				'@(...秒傷害|...秒傷...|每...傷害)@',
				'@(尋...魔法物品|...獲魔法物品)@',
				'@([\d]*)o@i',
				'@.(\d).件@',
			), array(
				'攻擊速度',
				'擊中生命恢復',
				'生命值',
				'怒氣',
				'每秒恢復',
				'掉落量',
				'抗性',
				'提高',
				'空的鑲孔',
				'全元素抗性',
				'護甲值',
				'單手',
				'次數',
				'裝備',
				'點',
				'秒',
				'傷害',
				'秘能',
				'$1 敏捷',
				'$1 體能',
				'$1 智力',
				'$1 力量',
				'爆擊傷害',
				'每秒攻擊',
				'每秒傷害',
				'尋獲魔法物品',
				"\${1}0",
				"$1件效果",
			), $content[$key]);
			
			// 穩定內容
			$content[$key] = preg_replace(array(
				'@擊中生命恢復 [\.\*-_~]([\d]*[%]?)@',
				'@每秒攻擊次數.*(\.\d*)@i',
				'@[\.\*-_~]([\d]*[%]?)\s?(怪物金幣|怒氣上限)@',
				'@[\.\*-_~](\d*),(\d*)@',
				'@[\.\*-_~]([\d]*[%~]?)\s?(傷害|敏捷|體能|力量|智力|護甲值|(...)*抗性)@',
			), array(
				"擊中生命恢復 +$1",
				'每秒攻擊次數: 1$1',
				"+$1 $2",
				"+$1~$2",
				"+$1 $2",
			), $content[$key]);

			// 無效內容
			$content[$key] = "【無效】" . $content[$key];

			// 辨識內容
			$content[$key] = preg_replace(array(
				'@.*\+([\d]+%?)\s(傷害|敏捷|體能|力量|智力|護甲值|(...)*抗性)@',
				'@.*每秒攻擊次數: ([\d\.]*)@',
				'@.*\+([\d]+)~([\d]+) 點傷害@',
				'@.*\+([\d]+)~([\d]+) 點(.*)傷害@',
				'@.*對精英怪的傷害提高 ([\d]*%?)@',
				'@.*\+([\d]+%?)\s?(怪物金幣掉落量|怒氣上限|最小傷害|最大傷害)@',
				'@.*爆擊傷害提高 ([\d]+%?)@',
				'@.*攻擊速度提高 ([\d]+%?)@',
				'@.*擊中生命恢復 ([\d]+%?)@',
				'@.*需要等級[.:\s]+([\d]+)@',
				'@.*物品等級[.:\s]+([\d]+).*@',
				'@.*(\d+)件效果@',
			), array(
				"+$1 $2",
				"每秒攻擊次數: $1",
				"$1~$2 傷害值",
				"+$1~$2 點$3傷害",
				"對精英怪的傷害提高 $1",
				"+$1 $2",
				"爆擊傷害提高 $1",
				"攻擊速度提高 $1",
				"擊中生命恢復 $1",
				"需要等級 $1",
				"物品等級 $1",
				"$1件效果",
			), $content[$key]);

		}
		
		$item = array();

		foreach ($content as $ind => $itm) {
			if ( ! strpos($content[$ind], "無效")) {
				$item[] = $itm;
			}
		}

		return array(
			'analyzed_item' => $content,
			'identifed_item' => $item,
		);
	}
}
//
//
//
