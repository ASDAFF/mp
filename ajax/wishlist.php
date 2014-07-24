<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$objElement = new CIBlockElement;
$wish = CIBlockElement::GetList(false, array(
	'IBLOCK_ID' => 23,
	'ACTIVE' => 'Y',
	'PROPERTY_OBJECT' => $_POST['element']
	), false, false, array('ID'))->Fetch();

if (false === $wish) {
	global $USER;
	$result['plus'] = true;
	$x = $objElement->Add(array(
		'IBLOCK_ID' => 23,
		'ACTIVE' => 'Y',
		'NAME' => 'WishItem',
		'PROPERTY_VALUES' => array(
			'USER' => $USER->GetId(),
			'OBJECT' => $_POST['element']
			)
		));
} else {
	$result['plus'] = false;
	$objElement->Delete($wish['ID']);
}

echo json_encode($result);
