<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Производители. Список.",
	"DESCRIPTION" => "Производители. Список.",
	"CACHE_PATH" => "Y",
	"SORT" => 30,
	"PATH" => array(
		"ID" => "tesset",
		"CHILD" => array(
			"ID" => "tesset",
			"NAME" => "Магазин",
			"SORT" => 30,
			"CHILD" => array(
				"ID" => "tesset_vendors",
			),
		),
	),
);
?>