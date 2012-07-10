<?php 
foreach ( $links as $uri => $link ){
	if ( is_array($link) ) {
		if ( empty( $link['filter'] ) ) $link['filter'] = "t";
		if ( empty( $uri ) ) break;
		if ( empty( $link['text'] ) ) $link['text']     = "[未定義]";
		if ( empty( $link['title'] ) ) $link['title']   = $link['text'];

		echo "<a data-filter='{$link['filter']}' href='/game/{$uri}/{$link['title']}' class='data_link'>{$link['text']}</a>";
	}
	else {
		echo "<div class='data_group'>$link</div>";
	}
}