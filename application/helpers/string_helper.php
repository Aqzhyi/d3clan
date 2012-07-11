<?php

function string_cut( $string = '', $len = 15 ) {
	$string = ( mb_strlen( $string ) > $len ) ? mb_substr( $string, 0, $len-1 ) . '…' : $string;

	return $string;
}

//
