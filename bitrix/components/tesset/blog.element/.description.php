<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Эврика. Блог. Статья детально.",
	"DESCRIPTION" => "Эврика. Блог. Статья детально.",
	"CACHE_PATH" => "Y",
	"SORT" => 30,
	"PATH" => array(
		"ID" => "tesset",
		"CHILD" => array(
			"ID" => "tesset",
			"NAME" => "Статьи",
			"SORT" => 30,
			"CHILD" => array(
				"ID" => "tesset_articles",
			),
		),
	),
);
?>