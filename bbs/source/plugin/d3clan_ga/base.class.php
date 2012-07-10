<?php

//全局嵌入点类（必须存在）
class plugin_d3clan_ga {

	function global_footer() {
		global $_G;

		return file_get_contents(template('d3clan_ga:code'));
	}

}

//脚本嵌入点类
class plugin_d3clan_ga_forum extends plugin_d3clan_ga {
	
}
// 