<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');
CBitrixComponent::includeComponentClass("component.model:likes");

$objElement = new CIBlockElement;
$likes = new Likes($_POST['ib']);

$like = $likes->isLikedByCurrent($_POST['element']);
if (false === $like) {
	$result['plus'] = true;
	$hash = md5($_SERVER['REMOTE_ADDR']);
	$x = $objElement->Add(array(
		'IBLOCK_ID' => 17,
		'NAME' => 'Like',
		'ACTIVE' => 'Y',
		'PROPERTY_VALUES' => array(
			'IP' => $_SERVER['REMOTE_ADDR'],
			'HASH' => $hash, //setCookie
			'OBJECT_ID' => $_POST['element'],
			'IBLOCK_ID' => $_POST['ib'],
			'USER' => $USER->GetId()
			) 
		));\
	// var_dump($objElement->LAST_ERROR);
	setcookie('muchmore-blog-like', $hash);
} else {
	$result['plus'] = false;
	$objElement->Delete($like);
	unset($_COOKIE['muchmore-blog-like']);
}

$result['val'] = $likes->count($_POST['element']);

echo json_encode($result);
