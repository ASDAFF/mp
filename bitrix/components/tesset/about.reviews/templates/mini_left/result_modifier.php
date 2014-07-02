<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$size = 150;
foreach ($arResult['REVIEWS'] as &$item) {
	if (strlen($item['PROPERTY_REVIEW_VALUE']['TEXT']) > $size) {
		$item['PROPERTY_REVIEW_VALUE']['TEXT'] = substr(
			\HTMLToTxt($item['PROPERTY_REVIEW_VALUE']['TEXT']), 
			0, strripos(
				substr(
					\HTMLToTxt($item['PROPERTY_REVIEW_VALUE']['TEXT']), 
					0, $size
					), " "
				)
			) . "...";
	}
}