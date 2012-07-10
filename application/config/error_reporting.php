<?php 
if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		// 開發環境
		case 'development':
			error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
		break;
		
		// 測試站環境
		case 'testing':
			error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
		break;

		// 主站環境
		case 'production':
			error_reporting(0);
		break;

		default:
			exit('The application environment is not set correctly.');
	}
}